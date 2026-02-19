<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentRequest;
use App\Models\User; 
use App\Models\AuditLog;
use App\Notifications\DocumentRequestStatusUpdated; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
        $this->middleware('check.admin'); 
    }

    public function showDocumentsPage(Request $request)
    {
        $query = DocumentRequest::with([
            'resident', 
            'certificateData', 
            'clearanceData', 
            'indigencyData', 
            'residencyData'
        ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('resident', function($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('resident_id', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }

        $documentRequests = $query->latest()->paginate(10)->withQueryString();

        $totalRequests   = DocumentRequest::count();
        $pendingCount    = DocumentRequest::where('status', 'pending')->count();
        $processingCount = DocumentRequest::where('status', 'in progress')->count();
        $completedCount  = DocumentRequest::where('status', 'completed')->count();

        return view('admin.documents.index', compact(
            'documentRequests',
            'totalRequests',
            'pendingCount', 
            'processingCount', 
            'completedCount'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $documentRequest = DocumentRequest::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,in progress,completed,rejected',
        ]);

        $documentRequest->status = $request->status;

        if ($request->status !== 'pending') {
            $documentRequest->processed_by = Auth::id();
        }
        
        if ($documentRequest->status === 'completed') {
            $documentRequest->date_completed = now();
        }

        $documentRequest->save();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Document Request Status Updated',
            'description' => "Updated to '{$request->status}' for tracking number {$documentRequest->tracking_number}",
        ]);

        $user = $documentRequest->resident; 
        
        if ($user) {
            $user->notify(new DocumentRequestStatusUpdated($documentRequest));
        }

        return back()->with('success', "Request updated to '{$request->status}' successfully.");
    }

    public function getDocumentRequest($id)
    {
        $documentRequest = DocumentRequest::with([
            'resident',
            'certificateData', 
            'clearanceData', 
            'indigencyData', 
            'residencyData'
        ])->findOrFail($id);

        return response()->json([
            'request' => $documentRequest,
            'resident' => $documentRequest->resident,
            'details' => [
                'certificate' => $documentRequest->certificateData,
                'clearance'   => $documentRequest->clearanceData,
                'indigency'   => $documentRequest->indigencyData,
                'residency'   => $documentRequest->residencyData,
            ]
        ]);
    }
}