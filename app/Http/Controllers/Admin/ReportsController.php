<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DocumentRequest;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

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

                // Population by Gender (filtered by year and month - approved, active residents only)
        $populationByGender = [
                        'male' => User::where(function ($q) {
                                                        $q->whereIn('role', ['user', 'resident'])
                                                            ->orWhereRaw('LOWER(COALESCE(user_type, "")) = ?', ['resident']);
                                                })
                                                ->whereRaw('LOWER(COALESCE(status, "")) = ?', ['approved'])
                                                ->whereNull('deleted_at')
                                                ->whereRaw('LOWER(TRIM(COALESCE(gender, ""))) = ?', ['male'])
                        ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                        ->count(),
                        'female' => User::where(function ($q) {
                                                        $q->whereIn('role', ['user', 'resident'])
                                                            ->orWhereRaw('LOWER(COALESCE(user_type, "")) = ?', ['resident']);
                                                })
                                                ->whereRaw('LOWER(COALESCE(status, "")) = ?', ['approved'])
                                                ->whereNull('deleted_at')
                                                ->whereRaw('LOWER(TRIM(COALESCE(gender, ""))) = ?', ['female'])
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
            'Physical Harassment' => Complaint::where('complaint_type', 'Physical Harassment')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'Neighbor Dispute' => Complaint::where('complaint_type', 'Neighbor Dispute')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'Money Problems' => Complaint::where('complaint_type', 'Money Problems')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'Misbehavior' => Complaint::where('complaint_type', 'Misbehavior')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            'Others' => Complaint::whereIn('complaint_type', ['Others', 'Other'])->whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
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

        $range = $request->get('range', 'monthly');
        $week = $request->get('week');
        $sixMonthStart = $request->get('six_month_start');
        $sixMonthEnd = $request->get('six_month_end');

        // ...existing code...

        // Determine date range based on export range
        $startDate = null;
        $endDate = null;
        if ($range === 'weekly') {
            // Week 1: 1-7, Week 2: 8-14, Week 3: 15-21, Week 4: 22-28, Week 5: 29-end
            $selectedWeek = (int)($week ?? 1);
            $firstOfMonth = Carbon::createFromDate($year, $month, 1);
            switch ($selectedWeek) {
                case 1:
                    $startDate = $firstOfMonth->copy();
                    $endDate = $firstOfMonth->copy()->addDays(6);
                    break;
                case 2:
                    $startDate = $firstOfMonth->copy()->addDays(7);
                    $endDate = $firstOfMonth->copy()->addDays(13);
                    break;
                case 3:
                    $startDate = $firstOfMonth->copy()->addDays(14);
                    $endDate = $firstOfMonth->copy()->addDays(20);
                    break;
                case 4:
                    $startDate = $firstOfMonth->copy()->addDays(21);
                    $endDate = $firstOfMonth->copy()->addDays(27);
                    break;
                case 5:
                    $startDate = $firstOfMonth->copy()->addDays(28);
                    $endDate = $firstOfMonth->copy()->endOfMonth();
                    break;
                default:
                    $startDate = $firstOfMonth->copy();
                    $endDate = $firstOfMonth->copy()->addDays(6);
            }
            // Clamp endDate to end of month
            $lastOfMonth = $firstOfMonth->copy()->endOfMonth();
            if ($endDate->gt($lastOfMonth)) {
                $endDate = $lastOfMonth;
            }
            // If exporting the current week, set endDate to now if it's before end of week
            $now = Carbon::now();
            if ($now->between($startDate, $endDate)) {
                $endDate = $now;
            }
        } elseif ($range === 'monthly') {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        } elseif ($range === 'six_months') {
            // Custom 6-month range, possibly crossing years
            $startMonth = (int)($sixMonthStart ?? 1);
            $endMonth = (int)($sixMonthEnd ?? 6);
            if ($startMonth <= $endMonth) {
                // Same year
                $startDate = Carbon::createFromDate($year, $startMonth, 1)->startOfMonth();
                $endDate = Carbon::createFromDate($year, $endMonth, 1)->endOfMonth();
            } else {
                // Crosses year boundary: e.g., Dec to May
                $startDate = Carbon::createFromDate($year - 1, $startMonth, 1)->startOfMonth();
                $endDate = Carbon::createFromDate($year, $endMonth, 1)->endOfMonth();
            }
        } elseif ($range === 'yearly') {
            $startDate = Carbon::createFromDate($year, 1, 1)->startOfYear();
            $endDate = Carbon::createFromDate($year, 12, 31)->endOfYear();
        } else {
            // Default to monthly
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        }

        // Determine date range based on export range
        $startDate = null;
        $endDate = null;
        $sixMonthStart = $request->get('six_month_start');
        $sixMonthEnd = $request->get('six_month_end');
        if ($range === 'weekly') {
            // Use selected week (1-5) of the month
            $selectedWeek = (int)($week ?? 1);
            $firstOfMonth = Carbon::createFromDate($year, $month, 1);
            $startDate = $firstOfMonth->copy()->addWeeks($selectedWeek - 1)->startOfWeek();
            $endDate = $startDate->copy()->endOfWeek();
            // Clamp to month
            if ($startDate->month != $month) $startDate = $firstOfMonth->copy();
            $lastOfMonth = $firstOfMonth->copy()->endOfMonth();
            if ($endDate->month != $month) $endDate = $lastOfMonth;
        } elseif ($range === 'monthly') {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        } elseif ($range === 'six_months') {
            // Custom 6-month range, possibly crossing years
            $startMonth = (int)($sixMonthStart ?? 1);
            $endMonth = (int)($sixMonthEnd ?? 6);
            if ($startMonth <= $endMonth) {
                // Same year
                $startDate = Carbon::createFromDate($year, $startMonth, 1)->startOfMonth();
                $endDate = Carbon::createFromDate($year, $endMonth, 1)->endOfMonth();
            } else {
                // Crosses year boundary: e.g., Dec to May
                $startDate = Carbon::createFromDate($year - 1, $startMonth, 1)->startOfMonth();
                $endDate = Carbon::createFromDate($year, $endMonth, 1)->endOfMonth();
            }
        } elseif ($range === 'yearly') {
            $startDate = Carbon::createFromDate($year, 1, 1)->startOfYear();
            $endDate = Carbon::createFromDate($year, 12, 31)->endOfYear();
        } else {
            // Default to monthly
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        }

        // Calculate total records in the selected range
        $totalRecords = 0;
        $totalRecords += User::where(function ($q) {
            $q->whereIn('user_type', ['admin', 'super admin', 'super_admin'])
              ->orWhereIn('role', ['admin', 'super admin', 'super_admin', 'superadmin', 'user', 'resident']);
        })
        ->whereRaw('LOWER(status) IN (?, ?)', ['approved', 'active'])
        ->whereNull('deleted_at')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count();
        $totalRecords += DocumentRequest::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalRecords += Complaint::whereBetween('created_at', [$startDate, $endDate])->count();

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

        $data = [
            'stats' => $stats,
            'year' => $year,
            'month' => $month,
            'monthName' => Carbon::createFromDate($year, $month, 1)->format('F'),
            'sections' => $sections,
            'exportRange' => $range,
            'exportStartDate' => $startDate->format('Y-m-d'),
            'exportEndDate' => $endDate->format('Y-m-d'),
            'totalExported' => $totalRecords,
        ];

        // Only load data for selected sections
        if (in_array('population_gender', $sections)) {
            $data['populationByGender'] = [
                                'male' => User::where(function ($q) {
                                                                $q->whereIn('role', ['user', 'resident'])
                                                                    ->orWhereRaw('LOWER(COALESCE(user_type, "")) = ?', ['resident']);
                                                        })
                                                        ->whereRaw('LOWER(COALESCE(status, "")) = ?', ['approved'])
                                                        ->whereNull('deleted_at')
                                                        ->whereRaw('LOWER(TRIM(COALESCE(gender, ""))) = ?', ['male'])
                                                        ->whereBetween('created_at', [$startDate, $endDate])
                                                        ->count(),
                                'female' => User::where(function ($q) {
                                                                $q->whereIn('role', ['user', 'resident'])
                                                                    ->orWhereRaw('LOWER(COALESCE(user_type, "")) = ?', ['resident']);
                                                        })
                                                        ->whereRaw('LOWER(COALESCE(status, "")) = ?', ['approved'])
                                                        ->whereNull('deleted_at')
                                                        ->whereRaw('LOWER(TRIM(COALESCE(gender, ""))) = ?', ['female'])
                                                        ->whereBetween('created_at', [$startDate, $endDate])
                                                        ->count(),
            ];
        }

        if (in_array('requests_complaints', $sections)) {
            $data['requestsComplaints'] = [
                'documents' => DocumentRequest::whereBetween('created_at', [$startDate, $endDate])->count(),
                'complaints' => Complaint::whereBetween('created_at', [$startDate, $endDate])->count(),
            ];
        }

        if (in_array('most_requested_document', $sections)) {
            $data['documentTypes'] = [
                'Barangay Clearance' => DocumentRequest::where('document_type', 'Barangay Clearance')->whereBetween('created_at', [$startDate, $endDate])->count(),
                'Barangay Certificate' => DocumentRequest::where('document_type', 'Barangay Certificate')->whereBetween('created_at', [$startDate, $endDate])->count(),
                'Indigency' => DocumentRequest::where('document_type', 'Certificate of Indigency')->whereBetween('created_at', [$startDate, $endDate])->count(),
                'Certificate of Residency' => DocumentRequest::where('document_type', 'Certificate of Residency')->whereBetween('created_at', [$startDate, $endDate])->count(),
            ];
        }

        if (in_array('request_status_summary', $sections)) {
            $data['requestStatusSummary'] = [
                'pending' => DocumentRequest::where('status', 'pending')->whereBetween('created_at', [$startDate, $endDate])->count(),
                'processing' => DocumentRequest::where('status', 'in progress')->whereBetween('created_at', [$startDate, $endDate])->count(),
                'approved' => DocumentRequest::where('status', 'completed')->whereBetween('created_at', [$startDate, $endDate])->count(),
            ];
        }

        if (in_array('most_reported_complaints', $sections)) {
            $data['complaintTypes'] = [
                'Community Issues' => Complaint::where('complaint_type', 'Community Issues')->whereBetween('created_at', [$startDate, $endDate])->count(),
                'Physical Harassment' => Complaint::where('complaint_type', 'Physical Harassment')->whereBetween('created_at', [$startDate, $endDate])->count(),
                'Neighbor Dispute' => Complaint::where('complaint_type', 'Neighbor Dispute')->whereBetween('created_at', [$startDate, $endDate])->count(),
                'Money Problems' => Complaint::where('complaint_type', 'Money Problems')->whereBetween('created_at', [$startDate, $endDate])->count(),
                'Misbehavior' => Complaint::where('complaint_type', 'Misbehavior')->whereBetween('created_at', [$startDate, $endDate])->count(),
                'Others' => Complaint::whereIn('complaint_type', ['Others', 'Other'])->whereBetween('created_at', [$startDate, $endDate])->count(),
            ];
        }

        if (in_array('complaint_status_summary', $sections)) {
            $data['complaintsStatusSummary'] = [
                'pending' => Complaint::where('status', 'Pending')->whereBetween('created_at', [$startDate, $endDate])->count(),
                'investigating' => Complaint::where('status', 'In Progress')->whereBetween('created_at', [$startDate, $endDate])->count(),
                'resolved' => Complaint::where('status', 'Completed')->whereBetween('created_at', [$startDate, $endDate])->count(),
            ];
        }

        if ($format == 'pdf') {
            $pdf = Pdf::loadView('admin.reports.pdf', $data);
            return $pdf->download('ARIS_Report_' . $year . '_' . $data['monthName'] . '.pdf');
        } elseif ($format == 'excel') {
            return $this->generateExcelXlsx($data);
        }

        return response()->json(['message' => 'Export format not supported.']);
    }

    private function generateExcelXlsx(array $data)
    {
        $filename = 'ARIS_Report_' . $data['year'] . '_' . $data['monthName'] . '.xlsx';

        $spreadsheet = new Spreadsheet();
        $dataSheet = $spreadsheet->getActiveSheet();
        $dataSheet->setTitle('Report Data');

        $dataSheet->setCellValue('A1', 'Barangay Daang Bakal - Reports & Analytics');
        $dataSheet->setCellValue('A2', 'Export Range: ' . ($data['exportRange'] ?? '') . ' (' . ($data['exportStartDate'] ?? '') . ' to ' . ($data['exportEndDate'] ?? '') . ')');
        $dataSheet->setCellValue('A3', 'Total Exported Records: ' . ($data['totalExported'] ?? 0));
        $dataSheet->setCellValue('A4', 'Report for ' . $data['monthName'] . ' ' . $data['year']);
        $dataSheet->mergeCells('A1:B1');
        $dataSheet->mergeCells('A2:B2');
        $dataSheet->mergeCells('A3:B3');
        $dataSheet->mergeCells('A4:B4');
        $dataSheet->getStyle('A1:A4')->getFont()->setBold(true)->setSize(13);

        $row = 6;
        $dataSheet->setCellValue("A{$row}", 'KPI Cards');
        $dataSheet->getStyle("A{$row}")->getFont()->setBold(true);
        $row++;
        $dataSheet->fromArray(['Metric', 'Value'], null, "A{$row}");
        $dataSheet->getStyle("A{$row}:B{$row}")->getFont()->setBold(true);
        $row++;

        $kpiRows = [
            ['Total Users', $data['stats']['totalUsers'] ?? 0],
            ['Registered Residents', $data['stats']['registeredResidents'] ?? 0],
            ['Registered Staffs', $data['stats']['totalStaff'] ?? 0],
            ['Archived Accounts', $data['stats']['archivedAccounts'] ?? 0],
        ];

        foreach ($kpiRows as $kpiRow) {
            $dataSheet->fromArray($kpiRow, null, "A{$row}");
            $row++;
        }

        $row += 2;

        if (in_array('population_gender', $data['sections'])) {
            $row = $this->writeSectionTable(
                $dataSheet,
                $row,
                'Population by Gender',
                ['Gender', 'Count'],
                [
                    ['Male', $data['populationByGender']['male'] ?? 0],
                    ['Female', $data['populationByGender']['female'] ?? 0],
                ]
            );
        }

        if (in_array('requests_complaints', $data['sections'])) {
            $row = $this->writeSectionTable(
                $dataSheet,
                $row,
                'Total Requests and Complaints',
                ['Service Type', 'Count'],
                [
                    ['Document Requests', $data['requestsComplaints']['documents'] ?? 0],
                    ['Complaints', $data['requestsComplaints']['complaints'] ?? 0],
                ]
            );
        }

        if (in_array('most_requested_document', $data['sections'])) {
            $documentRows = [];
            foreach ($data['documentTypes'] ?? [] as $type => $count) {
                $displayType = str_replace(['Indigency', 'Certificate of Residency'], ['Indigency Clearance', 'Resident Certificate'], $type);
                $documentRows[] = [$displayType, $count];
            }

            $row = $this->writeSectionTable(
                $dataSheet,
                $row,
                'Most Requested Document',
                ['Document Type', 'Count'],
                $documentRows
            );
        }

        if (in_array('request_status_summary', $data['sections'])) {
            $row = $this->writeSectionTable(
                $dataSheet,
                $row,
                'Request Status Summary',
                ['Status', 'Count'],
                [
                    ['Pending', $data['requestStatusSummary']['pending'] ?? 0],
                    ['In Progress', $data['requestStatusSummary']['processing'] ?? 0],
                    ['Completed', $data['requestStatusSummary']['approved'] ?? 0],
                ]
            );
        }

        if (in_array('most_reported_complaints', $data['sections'])) {
            $complaintRows = [];
            foreach ($data['complaintTypes'] ?? [] as $type => $count) {
                $complaintRows[] = [$type, $count];
            }

            $row = $this->writeSectionTable(
                $dataSheet,
                $row,
                'Most Reported Complaints',
                ['Complaint Type', 'Count'],
                $complaintRows
            );
        }

        if (in_array('complaint_status_summary', $data['sections'])) {
            $row = $this->writeSectionTable(
                $dataSheet,
                $row,
                'Complaints Status Summary',
                ['Status', 'Count'],
                [
                    ['Pending', $data['complaintsStatusSummary']['pending'] ?? 0],
                    ['In Progress', $data['complaintsStatusSummary']['investigating'] ?? 0],
                    ['Completed', $data['complaintsStatusSummary']['resolved'] ?? 0],
                ]
            );
        }

        $dataSheet->getColumnDimension('A')->setAutoSize(true);
        $dataSheet->getColumnDimension('B')->setAutoSize(true);

        $spreadsheet->setActiveSheetIndex(0);

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function writeSectionTable(Worksheet $sheet, int $row, string $title, array $headers, array $rows): int
    {
        $sheet->setCellValue("A{$row}", $title);
        $sheet->getStyle("A{$row}")->getFont()->setBold(true);
        $row++;

        $sheet->fromArray($headers, null, "A{$row}");
        $sheet->getStyle("A{$row}:B{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $row++;

        foreach ($rows as $item) {
            $sheet->fromArray($item, null, "A{$row}");
            $row++;
        }

        $row += 2;
        return $row;
    }

    private function writeSectionTableWithoutTitle(Worksheet $sheet, int $row, array $headers, array $rows): int
    {
        $sheet->fromArray($headers, null, "A{$row}");
        $sheet->getStyle("A{$row}:B{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $row++;

        foreach ($rows as $item) {
            $sheet->fromArray($item, null, "A{$row}");
            $row++;
        }

        $row += 2;
        return $row;
    }

    private function addChart(
        Worksheet $chartSheet,
        string $title,
        string $type,
        string $dataSheetName,
        string $labelRange,
        string $categoryRange,
        string $valueRange,
        int $chartIndex
    ): void {
        $seriesLabel = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $labelRange, null, 1),
        ];

        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $categoryRange, null),
        ];

        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $valueRange, null),
        ];

        $chartType = DataSeries::TYPE_BARCHART;
        $grouping = DataSeries::GROUPING_CLUSTERED;

        if ($type === 'pie') {
            $chartType = DataSeries::TYPE_PIECHART;
            $grouping = null;
        }

        if ($type === 'column') {
            $chartType = DataSeries::TYPE_BARCHART;
            $grouping = DataSeries::GROUPING_CLUSTERED;
        }

        if ($type === 'bar') {
            $chartType = DataSeries::TYPE_BARCHART;
            $grouping = DataSeries::GROUPING_CLUSTERED;
        }

        $series = new DataSeries(
            $chartType,
            $grouping,
            range(0, count($dataSeriesValues) - 1),
            $seriesLabel,
            $xAxisTickValues,
            $dataSeriesValues
        );

        if ($type === 'bar') {
            $series->setPlotDirection(DataSeries::DIRECTION_BAR);
        } else {
            $series->setPlotDirection(DataSeries::DIRECTION_COL);
        }

        $layout = new Layout();
        $layout->setShowVal(true);

        $plotArea = new PlotArea($layout, [$series]);
        $legend = new Legend(Legend::POSITION_RIGHT, null, false);
        $chartTitle = new Title($title);

        $chart = new Chart(
            'chart_' . md5($title . $chartIndex . $dataSheetName),
            $chartTitle,
            $legend,
            $plotArea,
            true,
            0,
            null,
            null
        );

        $baseRow = 3 + ($chartIndex * 18);
        $chart->setTopLeftPosition("A{$baseRow}");
        $chart->setBottomRightPosition('H' . ($baseRow + 14));
        $chartSheet->addChart($chart);
    }
}


