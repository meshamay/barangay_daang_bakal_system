@extends('admin.layouts.app')

@section('content')

@push('styles')
	<link rel="stylesheet" href="{{ asset('css/admin-modals.css') }}">
@endpush

<style>
</style>

<main class="flex-1 p-8 fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] overflow-y-auto bg-gradient-to-br from-gray-50 to-gray-100">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-[#134573] to-[#0f3a5f] bg-clip-text text-transparent">Reports & Analytics</h1>
            <p class="text-gray-500 text-sm mt-1">Explore key metrics, trends, and export monthly summaries.</p>
        </div>
        <button onclick="openModal('exportModal')" class="flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold px-6 py-2.5 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 transform hover:scale-105">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v12m6-6H6" />
            </svg>
            <span class="text-sm font-semibold">Export</span>
        </button>
    </div>

  {{-- Top Row Filters --}}
  <div class="flex justify-start items-center mb-8 gap-3">
      <form method="GET" action="{{ route('admin.reports.index') }}" class="flex items-center gap-3 flex-wrap">
          <select name="year" onchange="this.form.submit()" class="h-10 px-4 pr-10 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-400 min-w-[9rem]">
              @foreach (range(date('Y'), date('Y') - 4) as $y)
                  <option value="{{ $y }}" {{ (int) request('year', $year) === (int) $y ? 'selected' : '' }}>{{ $y }}</option>
              @endforeach
          </select>
          <select name="month" onchange="this.form.submit()" class="h-10 px-4 pr-10 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-400 min-w-[11rem]">
              @for ($i = 1; $i <= 12; $i++)
                  <option value="{{ $i }}" {{ request('month', date('m')) == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
              @endfor
          </select>

          @if(request('year') != date('Y') || request('month') != date('m'))
              <a href="{{ route('admin.reports.index') }}" class="h-10 px-4 flex items-center gap-2 text-sm border border-gray-300 rounded-lg bg-white hover:bg-gray-50 text-gray-700 transition">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                  Clear
              </a>
          @endif
      </form>
  </div>


  {{-- KPI Cards --}}
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

      <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg hover:border-blue-200 transition duration-300 ease-in-out">
          <div class="flex items-center justify-between">
              <div>
                  <p class="text-gray-500 text-sm font-medium">Total Users</p>
                  <p class="text-4xl font-bold text-gray-900 mt-2">{{ $stats['totalUsers'] }}</p>
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
                  <p class="text-gray-500 text-sm font-medium">Registered Residents</p>
                  <p class="text-4xl font-bold text-gray-900 mt-2">{{ $stats['registeredResidents'] }}</p>
              </div>
              <div class="bg-emerald-100 p-4 rounded-lg">
                  <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                  </svg>
              </div>
          </div>
      </div>

      <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg hover:border-blue-200 transition duration-300 ease-in-out">
          <div class="flex items-center justify-between">
              <div>
                  <p class="text-gray-500 text-sm font-medium">Registered Staff</p>
                  <p class="text-4xl font-bold text-gray-900 mt-2">{{ $stats['totalStaff'] }}</p>
              </div>
              <div class="bg-blue-100 p-4 rounded-lg">
                  <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 20v-1a4 4 0 014-4h4a4 4 0 014 4v1" />
                  </svg>
              </div>
          </div>
      </div>

      <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg hover:border-red-200 transition duration-300 ease-in-out">
          <div class="flex items-center justify-between">
              <div>
                  <p class="text-gray-500 text-sm font-medium">Archived Accounts</p>
                  <p class="text-4xl font-bold text-gray-900 mt-2">{{ $stats['archivedAccounts'] }}</p>
              </div>
              <div class="bg-red-100 p-4 rounded-lg">
                  <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7H4m0 0l1-3h14l1 3zm-1 3h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2v-7zm4 3h6" />
                  </svg>
              </div>
          </div>
      </div>

  </div>


  {{-- Charts Grid --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- CHART 1: PIE CHART --}}
            <div class="bg-gradient-to-br from-white to-gray-50 p-6 rounded-2xl shadow-2xl border border-gray-200">
                <h2 class="text-lg font-bold mb-6 text-gray-800 text-center">POPULATION BY GENDER</h2>
                <div class="relative flex flex-col items-center justify-center" style="height: 380px;">
                    @php
                        $total = $populationByGender['female'] + $populationByGender['male'];
                        $femalePercent = $total > 0 ? ($populationByGender['female'] / $total) * 100 : 0;
                        $malePercent = $total > 0 ? ($populationByGender['male'] / $total) * 100 : 0;
                    @endphp
                    <svg viewBox="0 0 200 200" style="width: 260px; height: 260px;" class="mb-6 drop-shadow-lg">
                        <defs>
                            <linearGradient id="femaleGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#FF6B9D;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#C2185B;stop-opacity:1" />
                            </linearGradient>
                            <linearGradient id="maleGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color:#42A5F5;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#1E88E5;stop-opacity:1" />
                            </linearGradient>
                        </defs>
                        <circle cx="100" cy="100" r="90" fill="none" stroke="url(#femaleGradient)" stroke-width="50" stroke-dasharray="{{ $femalePercent * 5.65 }} 565" stroke-linecap="round" transform="rotate(-90 100 100)"></circle>
                        <circle cx="100" cy="100" r="90" fill="none" stroke="url(#maleGradient)" stroke-width="50" stroke-dasharray="{{ $malePercent * 5.65 }} 565" stroke-linecap="round" stroke-dashoffset="{{ -$femalePercent * 5.65 }}" transform="rotate(-90 100 100)"></circle>
                    </svg>
                    <div class="flex gap-8 justify-center">
                        <div class="text-center p-3 rounded-xl bg-gradient-to-br from-pink-50 to-transparent">
                            <p class="text-xs font-semibold text-gray-600 mb-2">Female</p>
                            <p class="text-2xl font-bold bg-gradient-to-r from-pink-600 to-rose-600 bg-clip-text text-transparent">{{ $populationByGender['female'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ number_format($femalePercent, 1) }}%</p>
                        </div>
                        <div class="text-center p-3 rounded-xl bg-gradient-to-br from-blue-50 to-transparent">
                            <p class="text-xs font-semibold text-gray-600 mb-2">Male</p>
                            <p class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">{{ $populationByGender['male'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ number_format($malePercent, 1) }}%</p>
                        </div>
                    </div>
                    <div class="text-center text-xs font-semibold text-gray-500 mt-4">{{ $monthName }} | Total: {{ $total }}</div>
                </div>
            </div>


            {{-- CHART 2: DONUT CHART --}}
            <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-100">
                <h2 class="text-xl text-center font-bold mb-4 text-gray-800">TOTAL REQUEST & COMPLAINTS</h2>
                <div class="relative flex flex-col items-center justify-center" style="height: 450px;">
                    @php
                        $total = $requestsComplaints['documents'] + $requestsComplaints['complaints'];
                        $docPercent = $total > 0 ? ($requestsComplaints['documents'] / $total) * 100 : 0;
                        $complPercent = $total > 0 ? ($requestsComplaints['complaints'] / $total) * 100 : 0;
                    @endphp
                    <svg viewBox="0 0 200 200" style="width: 300px; height: 300px;" class="mb-4">
                        <circle cx="100" cy="100" r="80" fill="none" stroke="#FCE6C9" stroke-width="35" stroke-dasharray="{{ $docPercent * 5.03 }} 503" stroke-dashoffset="0" transform="rotate(-90 100 100)"></circle>
                        <circle cx="100" cy="100" r="80" fill="none" stroke="#C5E3B1" stroke-width="35" stroke-dasharray="{{ $complPercent * 5.03 }} 503" stroke-dashoffset="{{ -$docPercent * 5.03 }}" transform="rotate(-90 100 100)"></circle>
                        <circle cx="100" cy="100" r="50" fill="white"></circle>
                        <text x="100" y="105" text-anchor="middle" font-size="18" font-weight="bold" fill="#1e2e3d">{{ $total }}</text>
                        <text x="100" y="125" text-anchor="middle" font-size="10" fill="#666">Total</text>
                    </svg>
                    <div class="flex gap-8 justify-center">
                        <div class="text-center p-3 rounded-lg">
                            <div class="w-6 h-6 bg-[#FCE6C9] rounded mb-2 mx-auto"></div>
                            <p class="text-xs font-semibold text-gray-700">Document Requests</p>
                            <p class="text-sm font-bold text-gray-900">{{ $requestsComplaints['documents'] }} ({{ number_format($docPercent, 1) }}%)</p>
                        </div>
                        <div class="text-center p-3 rounded-lg">
                            <div class="w-6 h-6 bg-[#C5E3B1] rounded mb-2 mx-auto"></div>
                            <p class="text-xs font-semibold text-gray-700">Complaints</p>
                            <p class="text-sm font-bold text-gray-900">{{ $requestsComplaints['complaints'] }} ({{ number_format($complPercent, 1) }}%)</p>
                        </div>
                    </div>
                    <div class="text-center text-sm font-bold text-gray-700 mt-2">Month of {{ $monthName }}</div>
                </div>
            </div>


            {{-- CHART 3: HORIZONTAL BAR CHART --}}
            <div class="bg-gradient-to-br from-white to-gray-50 p-6 rounded-2xl shadow-2xl border border-gray-200">
                <h2 class="text-lg font-bold mb-6 text-gray-800 text-center">MOST REQUESTED DOCUMENT</h2>
                <div class="relative flex flex-col justify-center p-4" style="height: 380px;">
                    @php
                        $maxDoc = max(array_values($documentTypes));
                        $maxDoc = $maxDoc > 0 ? $maxDoc : 1;
                    @endphp
                    <div class="space-y-5">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold w-32 text-right text-gray-600">Barangay Clearance</span>
                            <div class="flex-grow bg-gradient-to-r from-gray-200 to-gray-100 rounded-full h-10 relative overflow-hidden shadow-md">
                                <div class="bg-gradient-to-r from-red-400 to-red-500 h-full rounded-full flex items-center justify-end pr-3 shadow-lg" style="width: {{ ($documentTypes['Barangay Clearance'] / $maxDoc) * 100 }}%;"><span class="text-xs font-bold text-white">{{ $documentTypes['Barangay Clearance'] }}</span></div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold w-32 text-right text-gray-600">Barangay Certificate</span>
                            <div class="flex-grow bg-gradient-to-r from-gray-200 to-gray-100 rounded-full h-10 relative overflow-hidden shadow-md">
                                <div class="bg-gradient-to-r from-blue-400 to-blue-500 h-full rounded-full flex items-center justify-end pr-3 shadow-lg" style="width: {{ ($documentTypes['Barangay Certificate'] / $maxDoc) * 100 }}%;"><span class="text-xs font-bold text-white">{{ $documentTypes['Barangay Certificate'] }}</span></div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold w-32 text-right text-gray-600">Indigency</span>
                            <div class="flex-grow bg-gradient-to-r from-gray-200 to-gray-100 rounded-full h-10 relative overflow-hidden shadow-md">
                                <div class="bg-gradient-to-r from-green-400 to-green-500 h-full rounded-full flex items-center justify-end pr-3 shadow-lg" style="width: {{ ($documentTypes['Indigency'] / $maxDoc) * 100 }}%;"><span class="text-xs font-bold text-white">{{ $documentTypes['Indigency'] }}</span></div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold w-32 text-right text-gray-600">Residency</span>
                            <div class="flex-grow bg-gradient-to-r from-gray-200 to-gray-100 rounded-full h-10 relative overflow-hidden shadow-md">
                                <div class="bg-gradient-to-r from-purple-400 to-purple-500 h-full rounded-full flex items-center justify-end pr-3 shadow-lg" style="width: {{ ($documentTypes['Certificate of Residency'] / $maxDoc) * 100 }}%;"><span class="text-xs font-bold text-white">{{ $documentTypes['Certificate of Residency'] }}</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center text-xs font-semibold text-gray-500 mt-6">{{ $monthName }}</div>
                </div>
            </div>


            {{-- CHART 4: AREA CHART --}}
            <div class="bg-gradient-to-br from-white to-gray-50 p-6 rounded-2xl shadow-2xl border border-gray-200">
                <h2 class="text-lg font-bold mb-6 text-gray-800 text-center">REQUEST STATUS SUMMARY</h2>
                <div class="relative flex flex-col justify-center" style="height: 380px;">
                    @php
                        $maxStatus = max(array_values($requestStatusSummary));
                        $maxStatus = $maxStatus > 0 ? $maxStatus : 1;
                        $pendingHeight = ($requestStatusSummary['pending'] / $maxStatus) * 250;
                        $processingHeight = ($requestStatusSummary['processing'] / $maxStatus) * 250;
                        $approvedHeight = ($requestStatusSummary['approved'] / $maxStatus) * 250;
                    @endphp
                    <svg viewBox="0 0 400 300" style="width: 100%; height: 300px;" preserveAspectRatio="xMidYMid meet" class="mb-4 drop-shadow-lg">
                        <defs>
                            <linearGradient id="areaGradient1" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" style="stop-color:#C084FC;stop-opacity:0.6" />
                                <stop offset="100%" style="stop-color:#C084FC;stop-opacity:0.1" />
                            </linearGradient>
                            <linearGradient id="areaGradient2" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" style="stop-color:#60A5FA;stop-opacity:0.6" />
                                <stop offset="100%" style="stop-color:#60A5FA;stop-opacity:0.1" />
                            </linearGradient>
                        </defs>
                        <!-- Grid -->
                        <line x1="50" y1="50" x2="50" y2="280" stroke="#e5e7eb" stroke-width="2"/>
                        <line x1="50" y1="280" x2="400" y2="280" stroke="#e5e7eb" stroke-width="2"/>
                        <!-- Areas with gradients -->
                        <path d="M 80 {{ 280 - $pendingHeight }} L 150 {{ 280 - $pendingHeight }} L 220 {{ 280 - $pendingHeight }} L 280 {{ 280 - $pendingHeight }} L 350 {{ 280 - $pendingHeight }} L 350 280 L 80 280 Z" fill="url(#areaGradient1)" stroke="#C084FC" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M 80 {{ 280 - $processingHeight }} L 150 {{ 280 - $processingHeight }} L 220 {{ 280 - $processingHeight }} L 280 {{ 280 - $processingHeight }} L 350 {{ 280 - $processingHeight }} L 350 280 L 80 280 Z" fill="url(#areaGradient2)" stroke="#60A5FA" stroke-width="2" stroke-linejoin="round"/>
                        <!-- Data points -->
                        <circle cx="80" cy="{{ 280 - $pendingHeight }}" r="4" fill="#C084FC" stroke="#fff" stroke-width="2"/>
                        <circle cx="150" cy="{{ 280 - $pendingHeight }}" r="4" fill="#C084FC" stroke="#fff" stroke-width="2"/>
                    </svg>
                    <div class="flex gap-6 justify-center mt-4 flex-wrap">
                        <div class="flex items-center gap-3 bg-purple-50 px-4 py-2 rounded-lg">
                            <div class="w-3 h-3 bg-purple-500 rounded"></div>
                            <div>
                                <p class="text-xs font-semibold text-gray-600">Pending</p>
                                <p class="text-lg font-bold text-purple-600">{{ $requestStatusSummary['pending'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 bg-blue-50 px-4 py-2 rounded-lg">
                            <div class="w-3 h-3 bg-blue-500 rounded"></div>
                            <div>
                                <p class="text-xs font-semibold text-gray-600">In Progress</p>
                                <p class="text-lg font-bold text-blue-600">{{ $requestStatusSummary['processing'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 bg-green-50 px-4 py-2 rounded-lg">
                            <div class="w-3 h-3 bg-green-500 rounded"></div>
                            <div>
                                <p class="text-xs font-semibold text-gray-600">Completed</p>
                                <p class="text-lg font-bold text-green-600">{{ $requestStatusSummary['approved'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center text-xs font-semibold text-gray-500 mt-4">{{ $monthName }}</div>
                </div>
            </div>


            {{-- CHART 5: BUBBLE CHART --}}
            <div class="bg-gradient-to-br from-white to-gray-50 p-6 rounded-2xl shadow-2xl border border-gray-200">
                <h2 class="text-lg font-bold mb-6 text-gray-800 text-center">MOST REPORTED COMPLAINTS</h2>
                <div class="relative flex flex-col items-center justify-center" style="height: 380px;">
                    @php
                        $maxComplaint = max(array_values($complaintTypes));
                        $maxComplaint = $maxComplaint > 0 ? $maxComplaint : 1;
                    @endphp
                    <svg viewBox="0 0 500 350" style="width: 100%; height: 350px;" preserveAspectRatio="xMidYMid meet" class="mb-4">
                        <!-- Background circles -->
                        <circle cx="250" cy="175" r="150" fill="#f3f4f6" opacity="0.5"/>
                        <!-- Bubbles -->
                        @php
                            $complaints = $complaintTypes;
                            $positions = [
                                ['x' => 100, 'y' => 80],
                                ['x' => 250, 'y' => 50],
                                ['x' => 400, 'y' => 100],
                                ['x' => 150, 'y' => 230],
                                ['x' => 350, 'y' => 260],
                                ['x' => 250, 'y' => 180],
                            ];
                            $colors = ['#F9D3DA', '#A2C4D9', '#FCE6C9', '#C5E3B1', '#D4A5F9', '#F9E5A5'];
                            $index = 0;
                        @endphp
                        @foreach($complaints as $type => $count)
                            @php
                                $radius = ($count / $maxComplaint) * 45 + 15;
                                $pos = $positions[$index] ?? ['x' => 250, 'y' => 175];
                            @endphp
                            <circle cx="{{ $pos['x'] }}" cy="{{ $pos['y'] }}" r="{{ $radius }}" fill="{{ $colors[$index] }}" opacity="0.8" stroke="#fff" stroke-width="2"/>
                            <text x="{{ $pos['x'] }}" y="{{ $pos['y'] - 5 }}" text-anchor="middle" font-size="14" font-weight="bold" fill="#333">{{ $count }}</text>
                            @php $index++; @endphp
                        @endforeach
                    </svg>
                    <div class="grid grid-cols-3 gap-3 mt-4 w-full px-4">
                        @php $index = 0; @endphp
                        @foreach($complaintTypes as $type => $count)
                            @php
                                $shortType = str_replace(['Community Issues', 'Physical Harassment', 'Neighbor Dispute', 'Money Problems', 'Misbehavior'], ['Community', 'Physical', 'Neighbor', 'Money', 'Misbehavior'], $type);
                            @endphp
                            <div class="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200 cursor-pointer">
                                <div class="w-4 h-4 rounded-full" style="background-color: {{ $colors[$index] }};"></div>
                                <p class="text-xs font-semibold text-gray-700">{{ $shortType }}</p>
                            </div>
                            @php $index++; @endphp
                        @endforeach
                    </div>
                    <div class="text-center text-sm font-bold text-gray-700 mt-3">Month of {{ $monthName }}</div>
                </div>
            </div>


            {{-- CHART 6: STACKED BAR CHART --}}
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <h2 class="text-xl text-center font-bold mb-4">COMPLAINTS STATUS SUMMARY</h2>
                <div class="relative flex flex-col justify-center p-4" style="height: 450px;">
                    @php
                        $maxComplaintStatus = max(array_values($complaintsStatusSummary));
                        $maxComplaintStatus = $maxComplaintStatus > 0 ? $maxComplaintStatus : 1;
                        $total = array_sum($complaintsStatusSummary);
                        $pendingPct = $total > 0 ? ($complaintsStatusSummary['pending'] / $total) * 100 : 0;
                        $investigatingPct = $total > 0 ? ($complaintsStatusSummary['investigating'] / $total) * 100 : 0;
                        $resolvedPct = $total > 0 ? ($complaintsStatusSummary['resolved'] / $total) * 100 : 0;
                    @endphp
                    <div class="space-y-8">
                        <!-- Stacked Bar 1 -->
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm font-semibold text-gray-700">Status Distribution</span>
                                <span class="text-sm font-bold text-gray-900">Total: {{ $total }}</span>
                            </div>
                            <div class="flex h-12 rounded-lg overflow-hidden shadow-md">
                                <div class="bg-[#FCE6C9] flex items-center justify-center" style="width: {{ $pendingPct }}%;">
                                    @if($pendingPct > 15)
                                        <span class="text-xs font-bold text-gray-700">{{ $complaintsStatusSummary['pending'] }}</span>
                                    @endif
                                </div>
                                <div class="bg-[#C5E3B1] flex items-center justify-center" style="width: {{ $investigatingPct }}%;">
                                    @if($investigatingPct > 15)
                                        <span class="text-xs font-bold text-gray-700">{{ $complaintsStatusSummary['investigating'] }}</span>
                                    @endif
                                </div>
                                <div class="bg-[#A8F5D8] flex items-center justify-center" style="width: {{ $resolvedPct }}%;">
                                    @if($resolvedPct > 15)
                                        <span class="text-xs font-bold text-gray-700">{{ $complaintsStatusSummary['resolved'] }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Legend -->
                        <div class="grid grid-cols-3 gap-4 pt-4">
                            <div class="border border-gray-200 rounded-lg p-3">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-3 h-3 bg-[#FCE6C9] rounded-full"></div>
                                    <p class="text-xs font-semibold text-gray-700">Pending</p>
                                </div>
                                <p class="text-lg font-bold text-gray-900">{{ $complaintsStatusSummary['pending'] }}</p>
                                <p class="text-xs text-gray-500">{{ number_format($pendingPct, 1) }}%</p>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-3">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-3 h-3 bg-[#C5E3B1] rounded-full"></div>
                                    <p class="text-xs font-semibold text-gray-700">Investigating</p>
                                </div>
                                <p class="text-lg font-bold text-gray-900">{{ $complaintsStatusSummary['investigating'] }}</p>
                                <p class="text-xs text-gray-500">{{ number_format($investigatingPct, 1) }}%</p>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-3">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-3 h-3 bg-[#A8F5D8] rounded-full"></div>
                                    <p class="text-xs font-semibold text-gray-700">Resolved</p>
                                </div>
                                <p class="text-lg font-bold text-gray-900">{{ $complaintsStatusSummary['resolved'] }}</p>
                                <p class="text-xs text-gray-500">{{ number_format($resolvedPct, 1) }}%</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center text-sm font-bold text-gray-700 mt-4">Month of {{ $monthName }}</div>
                </div>
            </div>
        </div>
        <br><br>
</main>

    <!-- =============================== -->
    <!-- 1. EXPORT SETTINGS MODAL -->
    <!-- =============================== -->
    <div id="exportModal" class="modal-container hidden fixed top-0 left-0 w-full h-full flex items-center justify-center z-[9999] font-poppins" style="left: 240px; width: calc(100vw - 240px); top: 80px; height: calc(100vh - 80px);">
        <div class="bg-white w-[650px] rounded-3xl shadow-2xl overflow-hidden border-2 border-gray-100 flex flex-col relative">
           
            <!-- Gradient Header -->
            <div class="px-6 py-4 flex items-center gap-3" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
                <h1 class="text-white font-bold text-xl tracking-wide font-['Barlow_Semi_Condensed']">Export Report</h1>
            </div>


            <!-- Body Content -->
            <form id="exportForm" method="POST" action="{{ route('admin.reports.export') }}" class="p-8">
                @csrf
                <p class="text-gray-500 text-sm mb-6">Select the format and section to include in your export.</p>


                <!-- Dropdowns -->
                <div class="flex gap-4 mb-6">
                    <div class="flex-1 relative">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Year:</label>
                        <select id="exportYear" name="year" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 font-medium appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                            @foreach (range(date('Y'), date('Y') - 4) as $y)
                                <option value="{{ $y }}" {{ (int) request('year', $year) === (int) $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 relative">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Month:</label>
                        <select id="exportMonth" name="month" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 font-medium appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ (int) request('month', date('m')) === (int) $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->format('F') }}</option>
                            @endfor
                        </select>
                    </div>
                </div>


                <!-- Export Format -->
                <h3 class="text-sm font-bold text-gray-800 mb-3">Export Format</h3>
                <div class="space-y-2 mb-6">
                    <label class="flex items-center p-3 bg-gray-50 rounded-xl border-2 border-gray-200 hover:border-blue-300 hover:bg-blue-50 cursor-pointer transition">
                        <div class="w-8 h-8 mr-3 flex-shrink-0">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" fill="#EA4335"/><path d="M14 2V8H20" fill="#E6E6E6"/><text x="6" y="17" fill="white" font-size="6" font-weight="bold">PDF</text></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">PDF Document</p>
                            <p class="text-gray-500 text-xs">Print friendly format with charts and tables.</p>
                        </div>
                        <input type="radio" name="format" value="pdf" class="ml-auto w-4 h-4 text-red-600 focus:ring-red-500" checked>
                    </label>
                    <label class="flex items-center p-3 bg-gray-50 rounded-xl border-2 border-gray-200 hover:border-green-300 hover:bg-green-50 cursor-pointer transition">
                        <div class="w-8 h-8 mr-3 flex-shrink-0">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" fill="#34A853"/><path d="M14 2V8H20" fill="#E6E6E6"/><path d="M8 12H16M8 16H16M8 8H10" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Excel Spreadsheet</p>
                            <p class="text-gray-500 text-xs">Editable data tables and charts.</p>
                        </div>
                        <input type="radio" name="format" value="excel" class="ml-auto w-4 h-4 text-green-600 focus:ring-green-500">
                    </label>
                </div>


                <!-- Section to Include -->
                <h3 class="text-sm font-bold text-gray-800 mb-3">Section to Include</h3>
                <div class="space-y-2.5 mb-8 bg-gray-50 rounded-xl p-4 border border-gray-200">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="sections[]" value="population_gender" class="w-4 h-4 bg-white border-gray-300 rounded text-blue-600 focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        <span class="ml-3 text-sm font-medium text-gray-700">Population by Gender</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="sections[]" value="requests_complaints" class="w-4 h-4 bg-white border-gray-300 rounded text-blue-600 focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        <span class="ml-3 text-sm font-medium text-gray-700">Total Request & Complaint</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="sections[]" value="most_requested_document" class="w-4 h-4 bg-white border-gray-300 rounded text-blue-600 focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        <span class="ml-3 text-sm font-medium text-gray-700">Most Requested Document</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="sections[]" value="request_status_summary" class="w-4 h-4 bg-white border-gray-300 rounded text-blue-600 focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        <span class="ml-3 text-sm font-medium text-gray-700">Request Status Summary</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="sections[]" value="most_reported_complaints" class="w-4 h-4 bg-white border-gray-300 rounded text-blue-600 focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        <span class="ml-3 text-sm font-medium text-gray-700">Most Reported Complaints</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="sections[]" value="complaint_status_summary" class="w-4 h-4 bg-white border-gray-300 rounded text-blue-600 focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        <span class="ml-3 text-sm font-medium text-gray-700">Complaint Status Summary</span>
                    </label>
                </div>


                <!-- Footer Buttons -->
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal('exportModal')" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-all duration-200 border border-gray-300 hover:shadow-md">CANCEL</button>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-lg transform hover:scale-105">EXPORT</button>
                </div>
            </form>
        </div>
    </div>


    <!-- =============================== -->
    <!-- 2. SUCCESS MODAL (PIXEL PERFECT) -->
    <!-- =============================== -->
    <div id="exportSuccessModal" class="modal-container hidden fixed top-0 left-0 w-full h-full flex items-center justify-center z-[9999] font-poppins" style="left: 240px; width: calc(100vw - 240px); top: 80px; height: calc(100vh - 80px);">
        <!-- Gray Card Container -->
        <div class="bg-[#E5E5E5] w-[600px] rounded-3xl p-10 shadow-2xl relative border-2 border-black">
           
            <!-- Header Text -->
            <h2 class="text-3xl font-extrabold text-black mb-1">Export Completed</h2>
            <p class="text-gray-600 text-sm font-semibold mb-6">Your report have been successfully generated and downloaded.</p>


            <!-- Cream/Beige Inner Box -->
            <div class="bg-[#F9F5F0] rounded-3xl p-8 mb-8">
                <h3 class="font-extrabold text-black text-lg mb-4">File Details</h3>
               
                <!-- File Details List -->
                <div class="space-y-3 text-sm text-black mb-6 pl-2">
                    <p class="flex items-start"><span class="w-24">File Name:</span> <span>BARIS_Report_2025.pdf</span></p>
                    <p class="flex items-start"><span class="w-24">Format:</span> <span>PDF</span></p>
                    <p class="flex items-start"><span class="w-24">Size:</span> <span>245 KB</span></p>
                    <p class="flex items-start"><span class="w-24">Generated:</span> <span>10/25/2025, 1:12 PM</span></p>
                </div>


                <!-- White Notification Box -->
                <div class="bg-white rounded-lg py-3 text-center shadow-sm">
                    <p class="font-bold text-black text-sm">The file has been downloaded to your default folder.</p>
                </div>
            </div>


            <!-- Done Button -->
            <div class="flex justify-center">
                <button onclick="closeModal('exportSuccessModal')"
                        class="bg-[#58A576] hover:bg-[#468c62] text-black font-extrabold text-sm py-3 px-24 rounded-full shadow-md transition">
                    DONE
                </button>
            </div>
        </div>
    </div>


    <!-- JavaScript to Handle Modals -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Modern Doughnut Chart for Gender Distribution
    const genderCtx = document.getElementById('genderPieChart').getContext('2d');
    const genderPieChart = new Chart(genderCtx, {
        type: 'doughnut',
        data: {
            labels: ['Female', 'Male'],
            datasets: [{
                data: [
                    {{ $populationByGender['female'] }},
                    {{ $populationByGender['male'] }}
                ],
                backgroundColor: [
                    'rgba(236, 72, 153, 0.85)',  // Modern Pink for Female
                    'rgba(59, 130, 246, 0.85)'   // Modern Blue for Male
                ],
                borderColor: [
                    '#ffffff',
                    '#ffffff'
                ],
                borderWidth: 4,
                hoverOffset: 15,
                hoverBorderWidth: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: {
                            size: 14,
                            weight: '700',
                            family: "'Inter', 'Segoe UI', sans-serif"
                        },
                        color: '#374151',
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 1500,
                easing: 'easeInOutQuart'
            }
        },
        plugins: [{
            id: 'centerText',
            beforeDraw: function(chart) {
                const width = chart.width;
                const height = chart.height;
                const ctx = chart.ctx;
                ctx.restore();
                
                const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                const fontSize = (height / 114).toFixed(2);
                ctx.font = `bold ${fontSize}em Inter, sans-serif`;
                ctx.textBaseline = 'middle';
                ctx.fillStyle = '#1f2937';
                
                const text = total.toString();
                const textX = Math.round((width - ctx.measureText(text).width) / 2);
                const textY = height / 2 - 10;
                ctx.fillText(text, textX, textY);
                
                ctx.font = `${fontSize * 0.4}em Inter, sans-serif`;
                ctx.fillStyle = '#6b7280';
                const subText = 'Total';
                const subTextX = Math.round((width - ctx.measureText(subText).width) / 2);
                const subTextY = height / 2 + 20;
                ctx.fillText(subText, subTextX, subTextY);
                
                ctx.save();
            }
        }]
    });

    // Modern Trend Chart for Requests & Complaints
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    
    // Create gradient for Document Requests
    const gradientDoc = trendCtx.createLinearGradient(0, 0, 0, 400);
    gradientDoc.addColorStop(0, 'rgba(251, 146, 60, 0.3)');
    gradientDoc.addColorStop(1, 'rgba(251, 146, 60, 0)');
    
    // Create gradient for Complaints
    const gradientComp = trendCtx.createLinearGradient(0, 0, 0, 400);
    gradientComp.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
    gradientComp.addColorStop(1, 'rgba(59, 130, 246, 0)');
    
    const trendChart = new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: ['Document Requests', 'Complaints'],
            datasets: [{
                label: 'Document Requests',
                data: [{{ $requestsComplaints['documents'] }}],
                borderColor: '#fb923c',
                backgroundColor: gradientDoc,
                borderWidth: 4,
                fill: true,
                tension: 0.5,
                pointRadius: 7,
                pointBackgroundColor: '#fb923c',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 3,
                pointHoverRadius: 10,
                pointHoverBorderWidth: 3,
                pointHoverShadowBlur: 10,
                pointHoverShadowOffsetX: 2,
                pointHoverShadowOffsetY: 2,
                segment: {
                    borderDash: function(ctx) {
                        return ctx.p0DataIndex === ctx.p1DataIndex ? [] : undefined;
                    }
                }
            }, {
                label: 'Complaints',
                data: [{{ $requestsComplaints['complaints'] }}],
                borderColor: '#3b82f6',
                backgroundColor: gradientComp,
                borderWidth: 4,
                fill: true,
                tension: 0.5,
                pointRadius: 7,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 3,
                pointHoverRadius: 10,
                pointHoverBorderWidth: 3,
                pointHoverShadowBlur: 10,
                pointHoverShadowOffsetX: 2,
                pointHoverShadowOffsetY: 2,
                segment: {
                    borderDash: function(ctx) {
                        return ctx.p0DataIndex === ctx.p1DataIndex ? [] : undefined;
                    }
                }
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        padding: 20,
                        font: {
                            size: 13,
                            weight: '700',
                            family: "'Inter', 'Segoe UI', sans-serif"
                        },
                        color: '#1f2937',
                        usePointStyle: true,
                        pointStyle: 'circle',
                        borderRadius: 8
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.9)',
                    padding: 14,
                    titleFont: {
                        size: 14,
                        weight: 'bold',
                        family: "'Inter', sans-serif"
                    },
                    bodyFont: {
                        size: 13,
                        family: "'Inter', sans-serif"
                    },
                    cornerRadius: 10,
                    displayColors: true,
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            const label = context.dataset.label || '';
                            const value = context.parsed.y;
                            return label + ': ' + value;
                        },
                        labelColor: function(context) {
                            return {
                                borderColor: context.dataset.borderColor,
                                backgroundColor: context.dataset.borderColor,
                                borderWidth: 2,
                                borderRadius: 4
                            };
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.06)',
                        drawBorder: false,
                        lineWidth: 1.5
                    },
                    ticks: {
                        color: '#9ca3af',
                        font: {
                            size: 12,
                            weight: '500'
                        },
                        padding: 10
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        color: '#6b7280',
                        font: {
                            size: 13,
                            weight: '600'
                        },
                        padding: 12
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart',
                delay: function(context) {
                    let delay = 0;
                    if (context.type === 'data') {
                        delay = context.dataIndex * 100 + context.datasetIndex * 50;
                    }
                    return delay;
                }
            }
        }
    });

    // Modern Professional Funnel Chart
    const docCtx = document.getElementById('documentTrendChart').getContext('2d');
    
    const docData = [
        {{ $documentTypes['Barangay Clearance'] }},
        {{ $documentTypes['Barangay Certificate'] }},
        {{ $documentTypes['Indigency'] }},
        {{ $documentTypes['Certificate of Residency'] }}
    ];
    
    const maxValue = Math.max(...docData);
    
    // Create subtle gradient backgrounds
    const createModernGradient = (color1, color2) => {
        const gradient = docCtx.createLinearGradient(0, 0, maxValue, 0);
        gradient.addColorStop(0, color1);
        gradient.addColorStop(1, color2);
        return gradient;
    };
    
    const documentTrendChart = new Chart(docCtx, {
        type: 'bar',
        data: {
            labels: [
                'Barangay Clearance',
                'Barangay Certificate',
                'Indigency Certificate',
                'Resident Certificate'
            ],
            datasets: [{
                label: 'Document Requests',
                data: docData,
                backgroundColor: [
                    createModernGradient('rgba(220, 38, 38, 0.85)', 'rgba(185, 28, 28, 0.7)'),
                    createModernGradient('rgba(37, 99, 235, 0.85)', 'rgba(29, 78, 216, 0.7)'),
                    createModernGradient('rgba(16, 185, 129, 0.85)', 'rgba(5, 150, 105, 0.7)'),
                    createModernGradient('rgba(217, 119, 6, 0.85)', 'rgba(180, 83, 9, 0.7)')
                ],
                borderColor: [
                    '#7f1d1d',
                    '#1e3a8a',
                    '#065f46',
                    '#78350f'
                ],
                borderWidth: 2,
                borderRadius: [12, 10, 8, 6],
                borderSkipped: false,
                hoverBackgroundColor: [
                    'rgba(220, 38, 38, 0.95)',
                    'rgba(37, 99, 235, 0.95)',
                    'rgba(16, 185, 129, 0.95)',
                    'rgba(217, 119, 6, 0.95)'
                ],
                hoverBorderWidth: 3
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(31, 41, 55, 0.9)',
                    padding: 16,
                    titleFont: {
                        size: 14,
                        weight: 'bold',
                        family: "'Inter', sans-serif"
                    },
                    bodyFont: {
                        size: 13,
                        weight: '500',
                        family: "'Inter', sans-serif"
                    },
                    cornerRadius: 8,
                    displayColors: false,
                    borderColor: 'rgba(255, 255, 255, 0.15)',
                    borderWidth: 1,
                    boxPadding: 10,
                    callbacks: {
                        title: function(context) {
                            return context[0].label;
                        },
                        label: function(context) {
                            const value = context.parsed.x;
                            const total = docData.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return 'Requests: ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    max: maxValue,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false,
                        lineWidth: 1,
                        drawTicks: false
                    },
                    ticks: {
                        color: '#6b7280',
                        font: {
                            size: 11,
                            weight: '500',
                            family: "'Inter', sans-serif"
                        },
                        padding: 12
                    }
                },
                y: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        color: '#1f2937',
                        font: {
                            size: 13,
                            weight: '600',
                            family: "'Inter', sans-serif"
                        },
                        padding: 16
                    }
                }
            },
            animation: {
                duration: 1800,
                easing: 'easeInOutCubic',
                delay: function(context) {
                    let delay = 0;
                    if (context.type === 'data') {
                        delay = context.dataIndex * 200;
                    }
                    return delay;
                }
            },
            layout: {
                padding: {
                    top: 20,
                    right: 25,
                    bottom: 20,
                    left: 25
                }
            }
        },
        plugins: [
            {
                id: 'modernLabel',
                afterDatasetsDraw: function(chart) {
                    const ctx = chart.ctx;
                    const meta = chart.getDatasetMeta(0);
                    
                    meta.data.forEach((bar, index) => {
                        const data = chart.data.datasets[0].data[index];
                        const total = docData.reduce((a, b) => a + b, 0);
                        const percentage = ((data / total) * 100).toFixed(1);
                        
                        ctx.fillStyle = '#ffffff';
                        ctx.font = '600 13px Inter, sans-serif';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';
                        
                        const x = bar.x - 40;
                        const y = bar.y;
                        ctx.fillText(data + ' (' + percentage + '%)', x, y);
                    });
                }
            }
        ]
    });

    // Clean Professional Triangle Pyramid Chart
    const pyramidCtx = document.getElementById('statusPyramidChart').getContext('2d');
    
    const statusData = [
        {{ $requestStatusSummary['pending'] ?? 0 }},
        {{ $requestStatusSummary['processing'] ?? 0 }},
        {{ $requestStatusSummary['approved'] ?? 0 }}
    ];
    
    const statusLabels = ['Pending', 'In Progress', 'Completed'];
    const total = statusData.reduce((a, b) => a + b, 0);
    
    const colors = [
        { fill: '#f97316', border: '#ea580c', light: 'rgba(249, 115, 22, 0.9)' },
        { fill: '#3b82f6', border: '#2563eb', light: 'rgba(59, 130, 246, 0.9)' },
        { fill: '#22c55e', border: '#16a34a', light: 'rgba(34, 197, 94, 0.9)' }
    ];
    
    // Custom plugin to draw clean triangle pyramid
    const pyramidPlugin = {
        id: 'pyramidPlugin',
        beforeDraw(chart) {
            const ctx = chart.ctx;
            const canvas = chart.canvas;
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        },
        afterDraw(chart) {
            const ctx = chart.ctx;
            const width = chart.width;
            const height = chart.height;
            
            const centerX = width / 2;
            const pyramidTop = 60;
            const pyramidBottom = height - 60;
            const pyramidHeight = pyramidBottom - pyramidTop;
            const maxWidth = Math.min(width * 0.7, 350);
            
            const sectionHeight = pyramidHeight / 3;
            
            // Draw from bottom to top for proper layering
            for (let i = 2; i >= 0; i--) {
                const topY = pyramidTop + (i * sectionHeight);
                const bottomY = topY + sectionHeight;
                
                // Calculate widths for trapezoid
                const topWidth = maxWidth * ((3 - i - 1) / 3);
                const bottomWidth = maxWidth * ((3 - i) / 3);
                
                const topLeftX = centerX - topWidth / 2;
                const topRightX = centerX + topWidth / 2;
                const bottomLeftX = centerX - bottomWidth / 2;
                const bottomRightX = centerX + bottomWidth / 2;
                
                // Draw shape
                ctx.beginPath();
                if (i === 0) {
                    // Top triangle
                    ctx.moveTo(centerX, topY);
                    ctx.lineTo(bottomRightX, bottomY);
                    ctx.lineTo(bottomLeftX, bottomY);
                } else {
                    // Trapezoids
                    ctx.moveTo(topLeftX, topY);
                    ctx.lineTo(topRightX, topY);
                    ctx.lineTo(bottomRightX, bottomY);
                    ctx.lineTo(bottomLeftX, bottomY);
                }
                ctx.closePath();
                
                // Fill
                ctx.fillStyle = colors[i].light;
                ctx.fill();
                
                // Border
                ctx.strokeStyle = colors[i].border;
                ctx.lineWidth = 3;
                ctx.stroke();
                
                // Add shadow for depth
                ctx.shadowColor = 'rgba(0, 0, 0, 0.1)';
                ctx.shadowBlur = 10;
                ctx.shadowOffsetX = 0;
                ctx.shadowOffsetY = 4;
                
                // Text
                const textY = topY + sectionHeight / 2;
                const percentage = total > 0 ? ((statusData[i] / total) * 100).toFixed(1) : '0.0';
                
                ctx.shadowColor = 'transparent';
                ctx.fillStyle = '#ffffff';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                
                // Label
                ctx.font = 'bold 16px Inter, sans-serif';
                ctx.fillText(statusLabels[i], centerX, textY - 18);
                
                // Count
                ctx.font = '600 14px Inter, sans-serif';
                ctx.fillText(statusData[i] + ' requests', centerX, textY + 2);
                
                // Percentage
                ctx.font = '500 13px Inter, sans-serif';
                ctx.fillText('(' + percentage + '%)', centerX, textY + 20);
            }
        }
    };
    
    const statusPyramidChart = new Chart(pyramidCtx, {
        type: 'bar',
        data: {
            labels: [''],
            datasets: [{ data: [0], backgroundColor: 'transparent' }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: false }
            },
            scales: {
                x: { display: false },
                y: { display: false }
            },
            animation: {
                duration: 1800,
                easing: 'easeInOutCubic'
            }
        },
        plugins: [pyramidPlugin]
    });
    </script>

{{-- Shared Backdrop for All Modals --}}
<div id="modal-backdrop" class="hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] bg-black/50 backdrop-blur-sm z-[9998]"></div>

@endsection

@push('scripts')
<script src="{{ asset('js/admin-reports.js') }}" defer></script>
@endpush

