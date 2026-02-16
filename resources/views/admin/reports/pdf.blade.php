<!DOCTYPE html>
<html>
<head>
    <title>BARIS Report {{ $year }} {{ $monthName }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { text-align: center; }
        h2 { margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Barangay Daang Bakal Reports & Analytics</h1>
    <p>Report for {{ $monthName }} {{ $year }}</p>

    <h2>KPI Cards</h2>
    <table>
        <tr><th>Metric</th><th>Value</th></tr>
        <tr><td>Total Users</td><td>{{ $stats['totalUsers'] }}</td></tr>
        <tr><td>Total Registered Residents</td><td>{{ $stats['totalResidents'] }}</td></tr>
        <tr><td>Total Registered Staffs</td><td>{{ $stats['totalStaff'] }}</td></tr>
        <tr><td>Archived Accounts</td><td>{{ $stats['archivedAccounts'] }}</td></tr>
    </table>

    @if(in_array('population_gender', $sections ?? []))
    <h2>Population by Gender</h2>
    <table>
        <tr><th>Gender</th><th>Count</th></tr>
        <tr><td>Male</td><td>{{ $populationByGender['male'] ?? 0 }}</td></tr>
        <tr><td>Female</td><td>{{ $populationByGender['female'] ?? 0 }}</td></tr>
    </table>
    @endif

    @if(in_array('requests_complaints', $sections ?? []))
    <h2>Total Requests & Complaints</h2>
    <table>
        <tr><th>Type</th><th>Count</th></tr>
        <tr><td>Document Requests</td><td>{{ $requestsComplaints['documents'] ?? 0 }}</td></tr>
        <tr><td>Complaints</td><td>{{ $requestsComplaints['complaints'] ?? 0 }}</td></tr>
    </table>
    @endif

    @if(in_array('most_requested_document', $sections ?? []))
    <h2>Most Requested Document</h2>
    <table>
        <tr><th>Document Type</th><th>Count</th></tr>
        @foreach($documentTypes ?? [] as $type => $count)
        <tr><td>{{ $type }}</td><td>{{ $count }}</td></tr>
        @endforeach
    </table>
    @endif

    @if(in_array('request_status_summary', $sections ?? []))
    <h2>Request Status Summary</h2>
    <table>
        <tr><th>Status</th><th>Count</th></tr>
        <tr><td>Pending</td><td>{{ $requestStatusSummary['pending'] ?? 0 }}</td></tr>
        <tr><td>In Progress</td><td>{{ $requestStatusSummary['processing'] ?? 0 }}</td></tr>
        <tr><td>Completed</td><td>{{ $requestStatusSummary['approved'] ?? 0 }}</td></tr>
    </table>
    @endif

    @if(in_array('most_reported_complaints', $sections ?? []))
    <h2>Most Reported Complaints</h2>
    <table>
        <tr><th>Complaint Type</th><th>Count</th></tr>
        @foreach($complaintTypes ?? [] as $type => $count)
        <tr><td>{{ $type }}</td><td>{{ $count }}</td></tr>
        @endforeach
    </table>
    @endif

    @if(in_array('complaint_status_summary', $sections ?? []))
    <h2>Complaints Status Summary</h2>
    <table>
        <tr><th>Status</th><th>Count</th></tr>
        <tr><td>Pending</td><td>{{ $complaintsStatusSummary['pending'] ?? 0 }}</td></tr>
        <tr><td>In Progress</td><td>{{ $complaintsStatusSummary['investigating'] ?? 0 }}</td></tr>
        <tr><td>Completed</td><td>{{ $complaintsStatusSummary['resolved'] ?? 0 }}</td></tr>
    </table>
    @endif
</body>
</html>