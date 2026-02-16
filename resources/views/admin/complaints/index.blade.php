@extends('admin.layouts.app')

@section('content')

@push('styles')
	<link rel="stylesheet" href="{{ asset('css/admin-modals.css') }}">
@endpush

<style>
    /* Table wrapper with scrollable area - this enables scrolling */
    .table-wrapper {
        max-height: 420px;
        overflow-y: auto;
        overflow-x: auto;
        position: relative;
        display: block;
        border-radius: 0.75rem;
    }

    /* FROZEN HEADER - stays at top while body scrolls */
    .table-wrapper table thead {
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table-wrapper table thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background: linear-gradient(135deg, #134573 0%, #0d2d47 100%) !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        white-space: nowrap;
    }

    /* First row of thead gets the rounded corners */
    .table-wrapper table thead tr:first-child th:first-child {
        border-top-left-radius: 0.75rem;
    }

    .table-wrapper table thead tr:first-child th:last-child {
        border-top-right-radius: 0.75rem;
    }

    .table-wrapper table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        table-layout: fixed;
    }

    /* Custom scrollbar styling */
    .table-wrapper::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .table-wrapper::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    .table-wrapper::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .table-wrapper::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Firefox */
    .table-wrapper {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #f1f5f9;
    }
</style>

<main class="flex-1 p-8 fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] overflow-y-auto bg-gradient-to-br from-gray-50 to-gray-100"> 
	<div class="flex items-center justify-between mb-8">
		<div>
			<h1 class="text-4xl font-bold bg-gradient-to-r from-[#134573] to-[#0f3a5f] bg-clip-text text-transparent">COMPLAINTS</h1>
			<p class="text-gray-500 text-sm mt-1">Manage and track all resident complaints.</p>
		</div>
	</div>
	
	{{-- Stats Cards --}}
	<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
		<div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg hover:border-blue-200 transition duration-300 ease-in-out">
			<div class="flex items-center justify-between">
				<div>
					<p class="text-gray-500 text-sm font-medium">Total Complaints</p>
					<p class="text-4xl font-bold text-gray-900 mt-2">{{ $stats['total'] ?? 0 }}</p>
				</div>
				<div class="bg-blue-100 p-4 rounded-lg">
					<svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
					</svg>
				</div>
			</div>
		</div>
		<div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg hover:border-amber-200 transition duration-300 ease-in-out">
			<div class="flex items-center justify-between">
				<div>
					<p class="text-gray-500 text-sm font-medium">Pending</p>
					<p class="text-4xl font-bold text-gray-900 mt-2">{{ $stats['pending'] ?? 0 }}</p>
				</div>
				<div class="bg-amber-100 p-4 rounded-lg">
					<svg class="h-8 w-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
					</svg>
				</div>
			</div>
		</div>
		<div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg hover:border-blue-200 transition duration-300 ease-in-out">
			<div class="flex items-center justify-between">
				<div>
					<p class="text-gray-500 text-sm font-medium">In Progress</p>
					<p class="text-4xl font-bold text-gray-900 mt-2">{{ $stats['in_progress'] ?? 0 }}</p>
				</div>
				<div class="bg-blue-100 p-4 rounded-lg">
					<svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2" />
					</svg>
				</div>
			</div>
		</div>
		<div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg hover:border-emerald-200 transition duration-300 ease-in-out">
			<div class="flex items-center justify-between">
				<div>
					<p class="text-gray-500 text-sm font-medium">Completed</p>
					<p class="text-4xl font-bold text-gray-900 mt-2">{{ $stats['completed'] ?? 0 }}</p>
				</div>
				<div class="bg-emerald-100 p-4 rounded-lg">
					<svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
					</svg>
				</div>
			</div>
		</div>
	</div>

	{{-- Search and Filter --}}
	<div class="flex justify-between items-center mb-6 gap-4">
		<form method="GET" action="{{ route('admin.complaints.index') }}" class="flex items-center gap-3 flex-1">
			<div class="relative w-full max-w-md">
				<div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
						<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
					</svg>
				</div>
				<input type="text" name="search" value="{{ request('search') }}" placeholder="Search ID or name..." class="w-full h-10 border border-gray-200 rounded-lg pl-11 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition" />
			</div>

			<div class="flex gap-2 w-fit">
				<select name="complaint_type" onchange="this.form.submit()" class="h-10 px-4 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition bg-white w-52">
					<option value="">Complaint Type</option>
					<option value="Community Issues" @selected(request('complaint_type') == 'Community Issues')>Community Issues</option>
					<option value="Physical Harrasments" @selected(request('complaint_type') == 'Physical Harrasments')>Physical Harrasments</option>
					<option value="Neighbor Dispute" @selected(request('complaint_type') == 'Neighbor Dispute')>Neighbor Dispute</option>
					<option value="Money Problems" @selected(request('complaint_type') == 'Money Problems')>Money Problems</option>
					<option value="Misbehavior" @selected(request('complaint_type') == 'Misbehavior')>Misbehavior</option>
					<option value="Others" @selected(request('complaint_type') == 'Others')>Others</option>
				</select>

				<select name="status" onchange="this.form.submit()" class="h-10 px-4 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition bg-white w-40">
					<option value="">Status</option>
					<option value="Pending" @selected(request('status') == 'Pending')>Pending</option>
					<option value="In Progress" @selected(request('status') == 'In Progress')>In Progress</option>
					<option value="Completed" @selected(request('status') == 'Completed')>Completed</option>
				</select>

				@if(request('search') || request('complaint_type') || request('status'))
					<a href="{{ route('admin.complaints.index') }}" class="h-10 px-4 flex items-center gap-2 text-sm border border-gray-300 rounded-lg bg-white hover:bg-gray-50 text-gray-700 transition">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
						</svg>
						Clear
					</a>
				@endif
			</div>
		</form>
	</div>

	{{-- Table --}}
	<div class="bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden">
		{{-- Fixed Header --}}
		<table class="w-full text-sm" style="table-layout: fixed;">
			<thead style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%); color: white;" class="shadow-sm">
				<tr class="text-xs font-semibold uppercase tracking-widest text-center">
					<th class="py-5 px-6 w-1/8">Transaction No.</th>
					<th class="py-5 px-6 w-1/8">Last Name</th>
					<th class="py-5 px-6 w-1/8">First Name</th>
					<th class="py-5 px-6 w-1/8">Complaint Type</th>
					<th class="py-5 px-6 w-1/8">Date Filed</th>
					<th class="py-5 px-6 w-1/8">Date Completed</th>
					<th class="py-5 px-6 w-1/8">Status</th>
				<th class="py-5 px-6 w-1/8">Action</th>
				</tr>
			</thead>
		</table>
		{{-- Scrollable Body --}}
		<div class="overflow-x-auto overflow-y-auto max-h-[360px]">
			<table class="w-full text-sm" style="table-layout: fixed;">
				<tbody class="divide-y divide-gray-100">
                @forelse ($complaints->take(4) as $complaint)
				@php
				    $status_color = [
				        'Pending' => 'bg-amber-100 text-amber-800 border border-amber-300',
				        'In Progress' => 'bg-blue-100 text-blue-800 border border-blue-300',
				        'Completed' => 'bg-emerald-100 text-emerald-800 border border-emerald-300',
				    ][$complaint->status] ?? 'bg-gray-100 text-gray-800 border border-gray-300';
				    $user = $complaint->user;
				@endphp
				<tr class="hover:bg-blue-50/70 transition-colors duration-150 ease-in-out text-center">
				    <td class="py-5 px-6 w-1/8 font-semibold text-gray-900">{{ $complaint->transaction_no }}</td>
				    <td class="py-5 px-6 w-1/8 text-gray-700">{{ $user->last_name ?? 'N/A' }}</td>
				    <td class="py-5 px-6 w-1/8 text-gray-700">{{ $user->first_name ?? 'N/A' }}</td>
				    <td class="py-5 px-6 w-1/8 text-gray-600">{{ $complaint->complaint_type }}</td>
				    <td class="py-5 px-6 w-1/8 text-gray-600 text-sm">{{ $complaint->created_at->format('d/m/Y') }}</td>
				    <td class="py-5 px-6 w-1/8 text-gray-600 text-sm">{{ $complaint->date_completed ? \Carbon\Carbon::parse($complaint->date_completed)->format('d/m/Y') : '—' }}</td>
				    <td class="py-5 px-6 w-1/8">
				        <span class="{{ $status_color }} text-xs font-bold px-3 py-2 rounded-full inline-block shadow-sm">
				            {{ $complaint->status }}
				        </span>
				    </td>
				    <td class="py-5 px-6 w-1/8 text-center">
				        <div class="flex items-center justify-center">
                            <div class="flex items-center gap-3 relative z-20 hover:z-[60]"> 

                                {{-- View Button - Always visible --}}
                                <div class="relative group">
                                    <button type="button" onclick="openModal('viewModal-{{ $complaint->id }}')" class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-blue-50 transition-all duration-200 hover:shadow-md">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <span class="absolute top-full mt-1 left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-30">View</span>
                                </div>

                                {{-- In Progress Button - Only for Pending status --}}
                                @if(strtolower($complaint->status) === 'pending')
                                    <div class="relative group">
                                        <button onclick="openStatusModal('inprogressModal', '{{ $complaint->id }}')" class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-amber-50 transition-all duration-200 hover:shadow-md">
                                            <svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l2.5 1.5" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3a9 9 0 110 18 9 9 0 010-18z" />
                                            </svg>
                                        </button>
                                        <span class="absolute top-full mt-1 left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-30">In progress</span>
                                    </div>
                                @endif

                                {{-- Complete Button - For Pending or In Progress status --}}
                                @if(strtolower($complaint->status) === 'pending' || strtolower($complaint->status) === 'in progress')
                                    <div class="relative group">
                                        <button onclick="openStatusModal('completedModal', '{{ $complaint->id }}')" class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-emerald-50 transition-all duration-200 hover:shadow-md">
                                            <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="9" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.5l2 2 4-4" />
                                            </svg>
                                        </button>
                                        <span class="absolute top-full mt-1 left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-30">Completed</span>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </td>
				</tr>
				@empty
				<tr>
				    <td colspan="9" class="py-6 text-center text-gray-500">No complaints found.</td>
				</tr>
				@endforelse
			</tbody>
			</table>
		</div>
	</div>
	
	<div class="mt-4">
		{{ $complaints->links() }}
	</div>

    
</main>

{{-- ======================================================= --}}
{{--  MODALS FOR STATUS UPDATES (Generic, ID set via JS)     --}}
{{-- ======================================================= --}}

    <div id="inprogressModal" class="modal-container hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] flex items-center justify-center z-[9999]">
        <div class="bg-white w-[520px] rounded-3xl shadow-2xl p-10 relative text-center border-2 border-gray-100 transform transition-all">
            <!-- Icon Badge -->
            <div class="flex justify-center mb-6">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-amber-50 to-amber-100 border-4 border-amber-500 flex items-center justify-center shadow-lg">
                    <svg class="w-12 h-12 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <!-- Title -->
            <h2 class="font-bold text-2xl mb-4 text-gray-800 tracking-tight">Process Complaint</h2>

            <!-- Description -->
            <div class="bg-amber-50 rounded-xl p-4 mb-6 border border-amber-200">
                <p class="text-sm text-gray-700 leading-relaxed">
                    You are accepting this complaint. Once confirmed, the status will be changed to "In Progress" and the resident will be notified.
                </p>
            </div>

            <!-- Action Buttons -->
            <form id="inprogressForm" method="POST" action="">
                @csrf @method('PUT')
                <input type="hidden" name="status" value="In Progress">
                <div class="flex justify-center gap-4 mt-6">
                    <button type="button" onclick="closeStatusModal('inprogressModal')" 
                            class="px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-md border border-gray-300">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-8 py-3 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-lg transform hover:scale-105">
                        Process Complaint
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="completedModal" class="modal-container hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] flex items-center justify-center z-[9999]">
        <div class="bg-white w-[520px] rounded-3xl shadow-2xl p-10 relative text-center border-2 border-gray-100 transform transition-all">
            <!-- Icon Badge -->
            <div class="flex justify-center mb-6">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-green-50 to-green-100 border-4 border-green-500 flex items-center justify-center shadow-lg">
                    <svg class="w-12 h-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <!-- Title -->
            <h2 class="font-bold text-2xl mb-4 text-gray-800 tracking-tight">Complete Complaint</h2>

            <!-- Description -->
            <div class="bg-green-50 rounded-xl p-4 mb-6 border border-green-200">
                <p class="text-sm text-gray-700 leading-relaxed">
                    The complaint case is resolved. Once confirmed, the status will be changed to "Completed" and the resident will be notified.
                </p>
            </div>

            <!-- Action Buttons -->
            <form id="completedForm" method="POST" action="">
                @csrf @method('PUT')
                <input type="hidden" name="status" value="Completed">
                <div class="flex justify-center gap-4 mt-6">
                    <button type="button" onclick="closeStatusModal('completedModal')" 
                            class="px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-md border border-gray-300">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-8 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-lg transform hover:scale-105">
                        Mark as Complete
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    {{-- ========================================================================= --}}
    {{--  🚀 DYNAMIC VIEW MODALS LOOP (This generates a unique modal for EACH row) --}}
    {{-- ========================================================================= --}}
    
    @foreach ($complaints as $c)
        @php $u = $c->user; @endphp
        
        {{-- UNIQUE ID FOR EVERY MODAL --}}
        <div id="viewModal-{{ $c->id }}" class="modal-container hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] flex items-center justify-center z-[9999]">
            <div class="bg-white w-[850px] max-h-[90vh] rounded-3xl overflow-hidden flex flex-col shadow-2xl border-2 border-gray-100">
                
                {{-- Modal Header --}}
                <div class="flex items-center px-6 py-4 gap-3" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
                    <h1 class="text-white text-xl font-bold font-['Barlow_Semi_Condensed'] tracking-wide">Complaint File - Transaction {{ $c->transaction_no }}</h1>
                </div>

                {{-- Modal Body --}}
                <div class="px-8 py-6 flex-1 overflow-y-auto text-left">

                    <div class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
                        {{-- USER INFO --}}
                        <div class="col-span-2"><h3 class="font-bold text-blue-600 border-b-2 border-blue-200 pb-2 mb-3">Complainant Information</h3></div>

                        <div><label class="font-semibold text-gray-700 block mb-1">Last Name:</label>
                             <input value="{{ $u->last_name ?? 'N/A' }}" readonly class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50"></div>
                        <div><label class="font-semibold text-gray-700 block mb-1">First Name:</label>
                             <input value="{{ $u->first_name ?? 'N/A' }}" readonly class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50"></div>
                        <div><label class="font-semibold text-gray-700 block mb-1">Resident ID:</label>
                             <input value="{{ $u->resident_id ?? 'N/A' }}" readonly class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50"></div>
                        <div><label class="font-semibold text-gray-700 block mb-1">Contact No:</label>
                             <input value="{{ $u->contact_number ?? 'N/A' }}" readonly class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50"></div>
                        <div><label class="font-semibold text-gray-700 block mb-1">Address:</label>
                             <input value="{{ $u->address ?? 'N/A' }}" readonly class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50"></div>
                        <div><label class="font-semibold text-gray-700 block mb-1">Gender/Age:</label>
                             <input value="{{ $u->gender ?? '' }} / {{ $u->age ?? '' }}" readonly class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50"></div>

                        {{-- ATTACHMENTS --}}
                        <div class="col-span-2 mt-3">
                            <label class="font-semibold text-gray-700 block mb-2">Valid ID Attachments</label>
                            <div class="mt-2 grid grid-cols-2 gap-4">
                                <div class="border-2 border-dashed border-blue-300 rounded-xl py-3 flex flex-col items-center text-sm w-full h-52 justify-center bg-blue-50/50 hover:bg-blue-50 transition-colors">
                                    @if($u && $u->id_front_path)
                                        <img src="{{ asset('storage/' . $u->id_front_path) }}" class="h-full w-auto object-contain cursor-pointer" onclick="window.open(this.src)">
                                    @else <span class="text-red-500 font-medium">No Front ID</span> @endif
                                </div>
                                <div class="border-2 border-dashed border-blue-300 rounded-xl py-3 flex flex-col items-center text-sm w-full h-52 justify-center bg-blue-50/50 hover:bg-blue-50 transition-colors">
                                    @if($u && $u->id_back_path)
                                        <img src="{{ asset('storage/' . $u->id_back_path) }}" class="h-full w-auto object-contain cursor-pointer" onclick="window.open(this.src)">
                                    @else <span class="text-red-500 font-medium">No Back ID</span> @endif
                                </div>
                            </div>
                        </div>

                        {{-- DIVIDER --}}
                        <div class="col-span-2 my-3"><div class="border-t border-gray-200"></div></div>

                        {{-- COMPLAINT INFO --}}
                        <div class="col-span-2"><h3 class="font-bold text-blue-600 border-b-2 border-blue-200 pb-2 mb-3">Incident Details</h3></div>

                        <div><label class="font-semibold text-gray-700 block mb-1">Incident Date:</label>
                             <input value="{{ $c->incident_date }}" readonly class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50"></div>
                        <div><label class="font-semibold text-gray-700 block mb-1">Incident Time:</label>
                             <input value="{{ $c->incident_time }}" readonly class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50"></div>
                        <div><label class="font-semibold text-gray-700 block mb-1">Defendant Name:</label>
                             <input value="{{ $c->defendant_name }}" readonly class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50"></div>
                        <div><label class="font-semibold text-gray-700 block mb-1">Defendant Address:</label>
                             <input value="{{ $c->defendant_address }}" readonly class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50"></div>
                        <div><label class="font-semibold text-gray-700 block mb-1">Urgency:</label>
                             <input value="{{ $c->level_urgency }}" readonly class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50"></div>
                        <div><label class="font-semibold text-gray-700 block mb-1">Complaint Type:</label>
                             <input value="{{ $c->complaint_type }}" readonly class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50"></div>
                        
                        <div class="col-span-2">
                            <label class="font-semibold text-gray-700 block mb-1">Statement:</label>
                            <textarea readonly class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50 h-28">{{ $c->complaint_statement }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button onclick="closeModal('viewModal-{{ $c->id }}')" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-all duration-200 border border-gray-300 hover:shadow-md">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

{{-- Shared Backdrop for All Modals --}}
<div id="modal-backdrop" class="hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] bg-black/50 backdrop-blur-sm z-[9998]"></div>

@endsection

@push('scripts')
<script src="{{ asset('js/admin-complaints.js') }}" defer></script>
@endpush