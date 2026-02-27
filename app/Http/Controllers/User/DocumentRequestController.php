<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\DocumentRequest;
use App\Models\User;
use App\Models\AuditLog;
use App\Notifications\DocumentRequestSubmitted;
use App\Notifications\DocumentRequestSubmittedAdmin;
use Illuminate\Support\Facades\Log; // Added for diagnostics

class DocumentRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     * 🚀 FIX: This method must be named 'index' to match the route.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $residentId = Auth::id();

        // Get filter parameters
        $search = $request->get('search');
        $status = $request->get('status');

        // Build query
        $query = DocumentRequest::where('resident_id', $residentId);

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('tracking_number', 'like', '%' . $search . '%')
                  ->orWhere('document_type', 'like', '%' . $search . '%')
                  ->orWhere('purpose', 'like', '%' . $search . '%');
            });
        }

        // Apply status filter (case-insensitive)
        if ($status) {
            $query->whereRaw('LOWER(status) = ?', [strtolower($status)]);
        }

        // Fetch filtered requests
        $myRequests = $query->latest()->get();

        // Calculate stats from all requests (not filtered)
        $allRequests = DocumentRequest::where('resident_id', $residentId)->get();
        $stats = [
            'pending'    => $allRequests->where('status', 'pending')->count(),
            'processing' => $allRequests->where('status', 'in progress')->count(),
            'completed'  => $allRequests->where('status', 'completed')->count(),
        ];

        return view('user.document-requests.index', compact('myRequests', 'stats'));
    }

    /**
     * Handle document request submission.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $documentType = $request->input('document_type');

        if (!$documentType) {
            return response()->json([
                'message' => 'Document type is required.',
            ], 422);
        }

        DB::beginTransaction();

        try {
            $trackingNumber = 'DOC-' . strtoupper(Str::random(8));

            // Base payload for the parent record
            $docRequestData = [
                'resident_id'     => Auth::id(),
                'tracking_number' => $trackingNumber,
                'document_type'   => $documentType,
                'status'          => 'pending',
                'date_requested'  => now(),
            ];

            $childRelation = null;
            $childData = [];

            switch ($documentType) {
                case 'Barangay Certificate':
                case 'Barangay Clearance':
                    $validated = $request->validate([
                        'document_type'       => 'required|string',
                        'length_of_residency' => 'required|string|max:100',
                        'valid_id_number'     => 'required|string|max:50',
                        'registered_voter'    => 'required|string|in:Yes,No',
                        'purpose'             => 'required|string|max:255',
                        'cedula_no'           => 'nullable|string|max:255',
                        'is_voter'            => 'nullable|string',
                    ]);

                    $docRequestData = array_merge($docRequestData, [
                        'length_of_residency' => $validated['length_of_residency'],
                        'valid_id_number'     => $validated['valid_id_number'],
                        'registered_voter'    => $validated['registered_voter'],
                        'purpose'             => $validated['purpose'],
                    ]);

                    $childRelation = $documentType === 'Barangay Certificate' ? 'certificateData' : 'clearanceData';
                    $childData = [
                        'purpose'   => $validated['purpose'],
                        'cedula_no' => $request->input('cedula_no'),
                    ];
                    break;

                case 'Certificate of Indigency':
                    $validated = $request->validate([
                        'document_type'    => 'required|string',
                        'purpose'          => 'required|string',
                        'indigency_category' => 'nullable|string',
                        'other_purpose'    => 'nullable|string',
                        'proof_file'       => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                    ]);

                    $docRequestData = array_merge($docRequestData, [
                        'length_of_residency' => null,
                        'valid_id_number'     => 'N/A',
                        'registered_voter'    => 'N/A',
                        'purpose'             => $validated['purpose'],
                    ]);

                    if ($request->hasFile('proof_file')) {
                        $path = $request->file('proof_file')->store('documents', 'public');
                        $docRequestData['proof_file_path'] = $path;
                    }

                    $childRelation = 'indigencyData';
                    $childData = [
                        'purpose'           => $validated['purpose'],
                        'indigency_category'=> $request->input('indigency_category'),
                        'other_purpose'     => $request->input('other_purpose'),
                    ];
                    break;

                case 'Certificate of Residency':
                    $validated = $request->validate([
                        'document_type'    => 'required|string',
                        'resident_years'   => 'required|string',
                        'civil_status'     => 'required|string',
                        'citizenship'      => 'required|string',
                        'purpose'          => 'required|string',
                        'valid_id_number'  => 'required|string|max:50',
                        'registered_voter' => 'required|string|in:Yes,No',
                    ]);

                    $docRequestData = array_merge($docRequestData, [
                        'length_of_residency' => $validated['resident_years'],
                        'valid_id_number'     => $validated['valid_id_number'],
                        'registered_voter'    => $validated['registered_voter'],
                        'purpose'             => $validated['purpose'],
                    ]);

                    $childRelation = 'residencyData';
                    $childData = [
                        'resident_years' => $validated['resident_years'],
                        'civil_status'   => $validated['civil_status'],
                        'citizenship'    => $validated['citizenship'],
                        'purpose'        => $validated['purpose'],
                    ];
                    break;

                default:
                    return response()->json([
                        'message' => 'Unsupported document type.',
                    ], 422);
            }

            // Create the parent request now that validation passed
            $docRequest = DocumentRequest::create($docRequestData);

            // Log audit
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'Document Request Submitted',
                'description' => $documentType,
            ]);

            // Attach child record
            if ($childRelation && !empty($childData)) {
                $docRequest->{$childRelation}()->create($childData);
            }
            
            // 5. DISPATCH THE NOTIFICATION
            $user = Auth::user();
            if ($user) {
                $user->notify(new DocumentRequestSubmitted($docRequest));
            }

            // Notify admins - Load the resident relationship
            $docRequest->load('resident');
            $admins = User::whereIn('role', ['admin', 'super admin', 'super_admin'])
                          ->orWhereIn('user_type', ['admin', 'super admin', 'super_admin'])
                          ->get();
            foreach ($admins as $admin) {
                $admin->notify(new DocumentRequestSubmittedAdmin($docRequest));
            }

            DB::commit();

            return response()->json([
                'message' => 'Request submitted successfully! A notification has been sent.',
                'tracking_number' => $trackingNumber
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('DOCUMENT SUBMISSION FAILED: ' . $e->getMessage(), ['exception' => $e]);
            $message = property_exists($e, 'validator')
                ? implode("\n", $e->validator->errors()->all())
                : $e->getMessage();
            return response()->json([
                'message' => 'Server Error: ' . $message,
                'type' => 'diagnostic'
            ], 500);
        }
    }
}