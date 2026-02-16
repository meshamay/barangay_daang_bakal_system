@extends('admin.layouts.app') 

@section('content')

<main class="flex-1 p-8 fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
  <div class="flex items-center justify-between mb-8">
    <div>
      <h1 class="text-4xl font-bold bg-gradient-to-r from-[#134573] to-[#0f3a5f] bg-clip-text text-transparent">DASHBOARD</h1>
      <p class="text-gray-500 text-sm mt-1">Welcome back! Here's your system overview.</p>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg hover:border-blue-200 transition duration-300 ease-in-out">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm font-medium">Total Users</p>
          <p class="text-4xl font-bold text-gray-900 mt-2">{{ $stats['totalUsers'] ?? 0 }}</p>
        </div>
        <div class="bg-blue-100 p-4 rounded-lg">
          <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 6a3 3 0 11-6 0 3 3 0 016 0zM6 20h12a6 6 0 00-6-6 6 6 0 00-6 6z" />
          </svg>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg hover:border-emerald-200 transition duration-300 ease-in-out">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm font-medium">Document Requests</p>
          <p class="text-4xl font-bold text-gray-900 mt-2">{{ $stats['totalRequests'] ?? 0 }}</p>
        </div>
        <div class="bg-emerald-100 p-4 rounded-lg">
          <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg hover:border-amber-200 transition duration-300 ease-in-out">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm font-medium">Complaints</p>
          <p class="text-4xl font-bold text-gray-900 mt-2">{{ $stats['totalComplaints'] ?? 0 }}</p>
        </div>
        <div class="bg-amber-100 p-4 rounded-lg">
          <svg class="h-8 w-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg hover:border-green-200 transition duration-300 ease-in-out">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm font-medium">Completed</p>
          <p class="text-4xl font-bold text-gray-900 mt-2">{{ $stats['completed'] ?? 0 }}</p>
        </div>
        <div class="bg-green-100 p-4 rounded-lg">
          <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
      </div>
    </div>
  </div>

  {{-- SEARCH AND FILTERS --}}
  <form method="GET" action="{{ route('admin.dashboard') }}" class="flex justify-between items-center mb-6 gap-4">
    <div class="flex items-center gap-3 flex-1">
      <div class="relative w-full max-w-md">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <input type="text" name="search" placeholder="Search transactions..." value="{{ request('search') }}" class="w-full h-10 border border-gray-200 rounded-lg pl-11 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition" />
      </div>

      <select name="type" class="h-10 px-4 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition bg-white w-44" onchange="this.form.submit()">
        <option value="">All Types</option>
        <option value="Document Request" {{ request('type') == 'Document Request' ? 'selected' : '' }}>Document Request</option>
        <option value="Complaint" {{ request('type') == 'Complaint' ? 'selected' : '' }}>Complaint</option>
      </select>
      <select name="status" class="h-10 px-4 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition bg-white w-40" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
        <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
      </select>

      @if(request('search') || request('type') || request('status'))
        <a href="{{ route('admin.dashboard') }}" class="h-10 px-4 flex items-center gap-2 text-sm border border-gray-300 rounded-lg bg-white hover:bg-gray-50 text-gray-700 transition">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
          Clear
        </a>
      @endif
    </div>
  </form>

  {{-- TABLE (Live Transaction Data) --}}
  <div class="bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden">
    {{-- Fixed Header --}}
    <table class="w-full text-sm" style="table-layout: fixed;">
      <thead style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%); color: white;" class="shadow-sm">
        <tr class="text-xs font-semibold uppercase tracking-widest text-center">
          <th class="py-5 px-6 w-1/8">Transaction No.</th>
          <th class="py-5 px-6 w-1/8">Last Name</th>
          <th class="py-5 px-6 w-1/8">First Name</th>
          <th class="py-5 px-6 w-1/8">Service Type</th>
          <th class="py-5 px-6 w-1/8">Description</th>
          <th class="py-5 px-6 w-1/8">Date Filed</th>
          <th class="py-5 px-6 w-1/8">Date Completed</th>
          <th class="py-5 px-6 w-1/8 pr-14 text-center pl-16">Status</th>
        </tr>
      </thead>
    </table>
    {{-- Scrollable Body --}}
    <div class="overflow-x-auto overflow-y-auto max-h-[360px]">
      <table class="w-full text-sm" style="table-layout: fixed;">
        <tbody class="divide-y divide-gray-100">
        
        @forelse ($transactions as $item)
            @php
                // Determine if it's a Document Request or Complaint
                $isDoc = $item instanceof \App\Models\DocumentRequest;
                
                // Get the User/Resident object
                $user = $isDoc ? ($item->resident ?? null) : ($item->user ?? null); 
                
                // Fields mapping
                $transNo = $isDoc ? ($item->tracking_number ?? 'N/A') : ($item->transaction_no ?? 'N/A');
                $serviceType = $isDoc ? 'Document Request' : 'Complaint';
                $description = $isDoc ? ($item->document_type ?? 'N/A') : ($item->complaint_type ?? 'N/A');
                
                // Status Color Logic (case-insensitive)
                $statusLower = strtolower($item->status);
                $statusColor = match($statusLower) {
                    'pending' => 'bg-amber-100 text-amber-800 border border-amber-300',
                    'in progress' => 'bg-blue-100 text-blue-800 border border-blue-300',
                    'completed' => 'bg-emerald-100 text-emerald-800 border border-emerald-300',
                    default => 'bg-gray-100 text-gray-800 border border-gray-300'
                };
                // Normalize status display with proper capitalization
                $statusDisplay = ucfirst(strtolower($item->status));
            @endphp

            <tr class="hover:bg-blue-50/70 transition-colors duration-150 ease-in-out text-center">
                <td class="py-5 px-6 w-1/8 font-semibold text-gray-900">{{ $transNo }}</td>
                <td class="py-5 px-6 w-1/8 text-gray-700">{{ $user->last_name ?? 'N/A' }}</td>
                <td class="py-5 px-6 w-1/8 text-gray-700">{{ $user->first_name ?? 'N/A' }}</td>
                <td class="py-5 px-6 w-1/8">
                    <span class="font-semibold text-sm {{ $isDoc ? 'text-blue-700' : 'text-red-700' }}">
                        {{ $serviceType }}
                    </span>
                </td>
                <td class="py-5 px-6 w-1/8 text-gray-600">{{ $description }}</td>
                <td class="py-5 px-6 w-1/8 text-gray-600 text-sm">{{ $item->created_at->format('d/m/Y') }}</td>
                <td class="py-5 px-6 w-1/8 text-gray-600 text-sm">
                    {{ $item->date_completed ? \Carbon\Carbon::parse($item->date_completed)->format('d/m/Y') : '—' }}
                </td>
                <td class="py-5 px-6 w-1/8">
                    <span class="{{ $statusColor }} text-xs font-bold px-3 py-2 rounded-full inline-block whitespace-nowrap shadow-sm">
                        {{ $statusDisplay }}
                    </span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="py-16 text-center text-gray-400 text-sm">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="font-medium">No transactions found</p>
                    <p class="text-xs mt-1">Try adjusting your search filters</p>
                </td>
            </tr>
        @endforelse

        </tbody>
      </table>
    </div>
  </div>
</main>

@endsection