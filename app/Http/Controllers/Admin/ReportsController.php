<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DocumentRequest;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));

        // Total Requests & Complaints for the selected month/year
        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        // Stats for cards
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
            'totalUsers' => $totalStaff + $totalApprovedResidents,
            // Only users with status 'accepted' (or 'approved'/'Active') are counted as registered residents
            'registeredResidents' => $totalApprovedResidents,
            'totalStaff' => $totalStaff,
            'archivedAccounts' => User::whereIn('role', ['user', 'resident'])->onlyTrashed()->count(),
        ];

        // Population by Gender (filtered by year and month - residents only)
        $populationByGender = [
            'male' => User::whereIn('role', ['user', 'resident'])
                        ->where('status', 'approved')
                        ->whereRaw('LOWER(gender) = ?', ['male'])
                        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                        ->count(),
            'female' => User::whereIn('role', ['user', 'resident'])
                        ->where('status', 'approved')
                        ->whereRaw('LOWER(gender) = ?', ['female'])
                        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                        ->count(),
        ];

        $requestsComplaints = [
            'documents' => DocumentRequest::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'complaints' => Complaint::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
        ];

        // Most Requested Document
        $documentTypes = [
            'Barangay Clearance' => DocumentRequest::where('document_type', 'Barangay Clearance')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'Barangay Certificate' => DocumentRequest::where('document_type', 'Barangay Certificate')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'Indigency' => DocumentRequest::where('document_type', 'Certificate of Indigency')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'Certificate of Residency' => DocumentRequest::where('document_type', 'Certificate of Residency')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
        ];

        // Request Status Summary
        $requestStatusSummary = [
            'pending' => DocumentRequest::where('status', 'pending')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'processing' => DocumentRequest::where('status', 'in progress')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'approved' => DocumentRequest::where('status', 'completed')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
        ];

        // Most Reported Complaints
        $complaintTypes = [
            'Community Issues' => Complaint::where('complaint_type', 'Community Issues')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'Physical Harrasments' => Complaint::where('complaint_type', 'Physical Harrasments')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'Neighbor Dispute' => Complaint::where('complaint_type', 'Neighbor Dispute')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'Money Problems' => Complaint::where('complaint_type', 'Money Problems')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'Misbehavior' => Complaint::where('complaint_type', 'Misbehavior')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'Others' => Complaint::where('complaint_type', 'Others')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
        ];

        // Complaints Status Summary
        $complaintsStatusSummary = [
            'pending' => Complaint::where('status', 'Pending')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'investigating' => Complaint::where('status', 'In Progress')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'resolved' => Complaint::where('status', 'Completed')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
        ];

        $monthName = Carbon::createFromDate($year, $month, 1)->format('F');

        return view('admin.reports.index', compact(
            'stats',
            'populationByGender',
            'requestsComplaints',
            'documentTypes',
            'requestStatusSummary',
            'complaintTypes',
            'complaintsStatusSummary',
            'year',
            'monthName'
        ));
    }

    public function export(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        $format = $request->get('format', 'pdf'); // pdf or excel
        $sections = $request->get('sections', []); // Array of selected sections

        // If no sections selected, select all by default
        if (empty($sections)) {
            $sections = [
                'population_gender',
                'requests_complaints',
                'most_requested_document',
                'request_status_summary',
                'most_reported_complaints',
                'complaint_status_summary'
            ];
        }

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
            'totalUsers' => $totalStaff + $totalApprovedResidents,
            // Only users with status 'accepted' (or 'approved'/'Active') are counted as registered residents
            'registeredResidents' => $totalApprovedResidents,
            'totalStaff' => $totalStaff,
            'archivedAccounts' => User::where('role', 'user')->onlyTrashed()->count(),
        ];

        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $data = [
            'stats' => $stats,
            'year' => $year,
            'month' => $month,
            'monthName' => Carbon::createFromDate($year, $month, 1)->format('F'),
            'sections' => $sections,
        ];

        // Only load data for selected sections
        if (in_array('population_gender', $sections)) {
            $data['populationByGender'] = [
                'male' => User::where('gender', 'Male')->count(),
                'female' => User::where('gender', 'Female')->count(),
            ];
        }

        if (in_array('requests_complaints', $sections)) {
            $data['requestsComplaints'] = [
                'documents' => DocumentRequest::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                'complaints' => Complaint::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            ];
        }

        if (in_array('most_requested_document', $sections)) {
            $data['documentTypes'] = [
                'Barangay Clearance' => DocumentRequest::where('document_type', 'Barangay Clearance')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                'Barangay Certificate' => DocumentRequest::where('document_type', 'Barangay Certificate')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                'Indigency' => DocumentRequest::where('document_type', 'Certificate of Indigency')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                'Certificate of Residency' => DocumentRequest::where('document_type', 'Certificate of Residency')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            ];
        }

        if (in_array('request_status_summary', $sections)) {
            $data['requestStatusSummary'] = [
                'pending' => DocumentRequest::where('status', 'pending')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                'processing' => DocumentRequest::where('status', 'in progress')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                'approved' => DocumentRequest::where('status', 'completed')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            ];
        }

        if (in_array('most_reported_complaints', $sections)) {
            $data['complaintTypes'] = [
                'Community Issues' => Complaint::where('complaint_type', 'Community Issues')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                'Physical Harrasments' => Complaint::where('complaint_type', 'Physical Harrasments')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                'Neighbor Dispute' => Complaint::where('complaint_type', 'Neighbor Dispute')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                'Money Problems' => Complaint::where('complaint_type', 'Money Problems')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                'Misbehavior' => Complaint::where('complaint_type', 'Misbehavior')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                'Others' => Complaint::where('complaint_type', 'Others')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            ];
        }

        if (in_array('complaint_status_summary', $sections)) {
            $data['complaintsStatusSummary'] = [
                'pending' => Complaint::where('status', 'pending')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                'investigating' => Complaint::where('status', 'in progress')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                'resolved' => Complaint::where('status', 'completed')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            ];
        }

        if ($format == 'pdf') {
            $pdf = Pdf::loadView('admin.reports.pdf', $data);
            return $pdf->download('BARIS_Report_' . $year . '_' . $data['monthName'] . '.pdf');
        } elseif ($format == 'excel') {
            // Generate Excel-compatible CSV
            return $this->generateExcelCsv($data);
        }

        return response()->json(['message' => 'Export format not supported.']);
    }

    private function generateExcelCsv($data)
    {
        $filename = 'BARIS_Report_' . $data['year'] . '_' . $data['monthName'] . '.csv';
        $headers = [
            'Content-Type' => 'text/csv;charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, ['Barangay Daang Bakal - Reports & Analytics']);
            fputcsv($file, ['Report for ' . $data['monthName'] . ' ' . $data['year']]);
            fputcsv($file, []);

            // KPI Cards
            fputcsv($file, ['KPI Cards']);
            fputcsv($file, ['Metric', 'Value']);
            fputcsv($file, ['Total Users', $data['stats']['totalUsers']]);
            fputcsv($file, ['Total Registered Residents', $data['stats']['totalResidents']]);
            fputcsv($file, ['Total Registered Staffs', $data['stats']['totalStaff']]);
            fputcsv($file, ['Archived Accounts', $data['stats']['archivedAccounts']]);
            fputcsv($file, []);

            // Population by Gender
            if (in_array('population_gender', $data['sections'])) {
                fputcsv($file, ['Population by Gender']);
                fputcsv($file, ['Gender', 'Count']);
                fputcsv($file, ['Male', $data['populationByGender']['male'] ?? 0]);
                fputcsv($file, ['Female', $data['populationByGender']['female'] ?? 0]);
                fputcsv($file, []);
            }

            // Total Requests & Complaints
            if (in_array('requests_complaints', $data['sections'])) {
                fputcsv($file, ['Total Requests & Complaints']);
                fputcsv($file, ['Type', 'Count']);
                fputcsv($file, ['Document Requests', $data['requestsComplaints']['documents'] ?? 0]);
                fputcsv($file, ['Complaints', $data['requestsComplaints']['complaints'] ?? 0]);
                fputcsv($file, []);
            }

            // Most Requested Document
            if (in_array('most_requested_document', $data['sections'])) {
                fputcsv($file, ['Most Requested Document']);
                fputcsv($file, ['Document Type', 'Count']);
                foreach ($data['documentTypes'] ?? [] as $type => $count) {
                    fputcsv($file, [$type, $count]);
                }
                fputcsv($file, []);
            }

            // Request Status Summary
            if (in_array('request_status_summary', $data['sections'])) {
                fputcsv($file, ['Request Status Summary']);
                fputcsv($file, ['Status', 'Count']);
                fputcsv($file, ['Pending', $data['requestStatusSummary']['pending'] ?? 0]);
                fputcsv($file, ['In Progress', $data['requestStatusSummary']['processing'] ?? 0]);
                fputcsv($file, ['Completed', $data['requestStatusSummary']['approved'] ?? 0]);
                fputcsv($file, []);
            }

            // Most Reported Complaints
            if (in_array('most_reported_complaints', $data['sections'])) {
                fputcsv($file, ['Most Reported Complaints']);
                fputcsv($file, ['Complaint Type', 'Count']);
                foreach ($data['complaintTypes'] ?? [] as $type => $count) {
                    fputcsv($file, [$type, $count]);
                }
                fputcsv($file, []);
            }

            // Complaints Status Summary
            if (in_array('complaint_status_summary', $data['sections'])) {
                fputcsv($file, ['Complaints Status Summary']);
                fputcsv($file, ['Status', 'Count']);
                fputcsv($file, ['Pending', $data['complaintsStatusSummary']['pending'] ?? 0]);
                fputcsv($file, ['In Progress', $data['complaintsStatusSummary']['investigating'] ?? 0]);
                fputcsv($file, ['Completed', $data['complaintsStatusSummary']['resolved'] ?? 0]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}


