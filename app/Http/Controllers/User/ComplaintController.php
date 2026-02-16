<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Notifications\ComplaintSubmitted;
use App\Notifications\ComplaintSubmittedAdmin;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the user's complaints and summary counts.
     */
    public function index()
    {
        $user = Auth::user();

        $complaints = Complaint::where('user_id', $user->id)
            ->latest()
            ->get();

        $stats = [
            'pending' => $complaints->where('status', 'Pending')->count(),
            'in_progress' => $complaints->where('status', 'In Progress')->count(),
            'completed' => $complaints->where('status', 'Completed')->count(),
        ];

        return view('user.complaints.index', compact('complaints', 'stats', 'user'));
    }

    /**
     * Handle complaint submission (AJAX POST).
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'incident_date'         => 'required|date',
            'incident_time'         => 'required|date_format:H:i',
            'defendant_name'        => 'required|string|max:255',
            'defendant_address'     => 'required|string|max:255',
            'level_urgency'         => 'required|in:High,Medium,Low', 
            'description'           => 'required|string|max:255',    
            'specifyInput'          => 'nullable|string|max:255',    
            'complaint_statement'   => 'required|string|min:10',
        ]);

        DB::beginTransaction();

        try {
            $trackingNumber = 'CMP-' . strtoupper(Str::random(8));
            
            $complaintType = $validatedData['description'];
            if ($complaintType === 'Others' && !empty($validatedData['specifyInput'])) {
                $complaintType = $validatedData['specifyInput'];
            }
            $complaintType = Str::limit($complaintType, 255); 

            $complaintData = [
                'user_id'             => Auth::id(),
                'transaction_no'      => $trackingNumber,
                'incident_date'       => $validatedData['incident_date'],
                'incident_time'       => $validatedData['incident_time'],
                'defendant_name'      => $validatedData['defendant_name'],
                'defendant_address'   => $validatedData['defendant_address'],
                
                'level_urgency'       => $validatedData['level_urgency'], 
                'complaint_type'      => $complaintType, 
                
                'complaint_statement' => $validatedData['complaint_statement'],
                'status'              => 'Pending',
            ];
            
            $complaint = Complaint::create($complaintData);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'Complaint Submitted',
                'description' => $complaintType,
            ]);

            $user = Auth::user();
            if ($user) {
                $user->notify(new ComplaintSubmitted($complaint));
            }

            $admins = User::whereIn('role', ['admin', 'super admin', 'super_admin'])
                          ->orWhereIn('user_type', ['admin', 'super admin', 'super_admin'])
                          ->get();
            foreach ($admins as $admin) {
                $admin->notify(new ComplaintSubmittedAdmin($complaint));
            }

            DB::commit();

            return response()->json([
                'message' => 'Complaint filed successfully.',
                'tracking_number' => $trackingNumber
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->errors()], 422);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to file complaint.', 'details' => $e->getMessage()], 500);
        }
    }
    
}