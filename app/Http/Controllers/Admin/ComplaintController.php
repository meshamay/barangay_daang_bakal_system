<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\AuditLog;
use App\Notifications\ComplaintStatusUpdated; 
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    /**
     * Display the list of complaints (Index).
     */
    public function index(Request $request)
    {
        
        $query = Complaint::with('user'); 
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_no', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('first_name', 'like', "%{$search}%")
                          ->orWhere('last_name', 'like', "%{$search}%")
                          ->orWhere('resident_id', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('complaint_type')) {
            $query->where('complaint_type', $request->complaint_type);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $complaints = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total'       => Complaint::count(),
            'pending'     => Complaint::where('status', 'Pending')->count(),
            'in_progress' => Complaint::where('status', 'In Progress')->count(),
            'completed'   => Complaint::where('status', 'Completed')->count(),
        ];

        return view('admin.complaints.index', compact('complaints', 'stats'));
    }

    /**
     * Display the specific complaint details (Show).
     */
    public function show(Complaint $complaint)
    {
        $complaint->load('user');

        return view('admin.complaints.show', compact('complaint'));
    }

    /**
     * Update the complaint status (Update).
     */
    public function update(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,In Progress,Completed',
        ]);

        $complaint->status = $validated['status'];

        if ($validated['status'] === 'Completed') {
            $complaint->date_completed = Carbon::now();
        } else {
            $complaint->date_completed = null;
        }

        $complaint->save();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Complaint Status Updated',
            'description' => "Updated to '{$validated['status']}' for tracking number {$complaint->transaction_no}",
        ]);

        $user = $complaint->user; 
        if ($user) {
            $user->notify(new ComplaintStatusUpdated($complaint));
        }

        return redirect()->route('admin.complaints.index')
            ->with('success', "Complaint {$complaint->transaction_no} updated to {$complaint->status}.");
    }
}