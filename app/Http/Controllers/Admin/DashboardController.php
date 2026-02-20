<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DocumentRequest;
use App\Models\Complaint;
use App\Models\BarangayOfficial; 

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('check.admin');
    }

    public function index(Request $request)
    {
        $totalDocumentRequests = DocumentRequest::count();
        $totalComplaints = Complaint::count();
        
        // Count staff accounts
        $totalStaff = User::where(function ($q) {
                            $q->whereIn('user_type', ['admin', 'super admin', 'super_admin'])
                              ->orWhereIn('role', ['admin', 'super admin', 'super_admin', 'superadmin']);
                        })
                        ->whereRaw('LOWER(status) IN (?, ?)', ['approved', 'active'])
                        ->whereNull('deleted_at')
                        ->count();
        
        // Count approved residents
        $totalApprovedResidents = User::whereIn('role', ['user', 'resident'])
                                ->where('status', 'approved')
                                ->whereNull('deleted_at')
                                ->count();
        
        $stats = [
            'totalUsers'      => $totalStaff + $totalApprovedResidents, // Count staff + approved residents
            // Only users with status 'accepted' (or 'approved'/'Active') are counted as registered residents
            'registeredResidents' => $totalApprovedResidents,
            'totalRequests'   => $totalDocumentRequests,
            'totalComplaints' => $totalComplaints,
            'completed'       => DocumentRequest::where('status', 'completed')->count() 
                               + Complaint::where('status', 'completed')->count(),
        ];

        // Get filter parameters
        $type = $request->get('type');
        $status = $request->get('status');
        $search = $request->get('search');

        // Build queries based on filters
        $documentsQuery = DocumentRequest::with('resident:id,first_name,last_name,resident_id');
        $complaintsQuery = Complaint::with('user:id,first_name,last_name,resident_id');

        // Apply status filter (case-insensitive)
        if ($status) {
            $documentsQuery->whereRaw('LOWER(status) = ?', [strtolower($status)]);
            $complaintsQuery->whereRaw('LOWER(status) = ?', [strtolower($status)]);
        }

        // Apply search filter
        if ($search) {
            $documentsQuery->where(function($q) use ($search) {
                $q->where('tracking_code', 'like', '%' . $search . '%')
                  ->orWhereHas('resident', function($q2) use ($search) {
                      $q2->where('first_name', 'like', '%' . $search . '%')
                         ->orWhere('last_name', 'like', '%' . $search . '%');
                  });
            });
            $complaintsQuery->where(function($q) use ($search) {
                $q->where('transaction_no', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('first_name', 'like', '%' . $search . '%')
                         ->orWhere('last_name', 'like', '%' . $search . '%');
                  });
            });
        }

        // Apply type filter and fetch results
        if ($type === 'Document Request') {
            $documents = $documentsQuery->latest('created_at')->take(15)->get();
            $transactions = $documents;
        } elseif ($type === 'Complaint') {
            $complaints = $complaintsQuery->latest('created_at')->take(15)->get();
            $transactions = $complaints;
        } else {
            // Show both types
            $documents = $documentsQuery->latest('created_at')->take(10)->get();
            $complaints = $complaintsQuery->latest('created_at')->take(10)->get();
            $transactions = $documents->concat($complaints)->sortByDesc('created_at')->take(15);
        }
        
        return view('admin.dashboard.index', compact(
            'stats', 
            'transactions'
        ));
    }
}