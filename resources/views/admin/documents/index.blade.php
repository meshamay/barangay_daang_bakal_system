@extends('admin.layouts.app')
@section('content')

@push('styles')
	<link rel="stylesheet" href="{{ asset('css/admin-modals.css') }}">
@endpush

<style>
	.justify-start {
		justify-content: center;
	}
</style>

<main class="flex-1 p-8 fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] overflow-y-auto bg-gradient-to-br from-gray-50 to-gray-100"> 
	<div class="flex items-center justify-between mb-8">
		<div>
			<h1 class="text-4xl font-bold bg-gradient-to-r from-[#134573] to-[#0f3a5f] bg-clip-text text-transparent">DOCUMENT REQUEST</h1>
			<p class="text-gray-500 text-sm mt-1">Track and manage all resident document requests.</p>
		</div>
	</div>
	
	{{-- Stats Cards --}}
	<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
		<div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg hover:border-blue-200 transition duration-300 ease-in-out">
			<div class="flex items-center justify-between">
				<div>
					<p class="text-gray-500 text-sm font-medium">Total Requests</p>
					<p class="text-4xl font-bold text-gray-900 mt-2">{{ $totalRequests ?? 0 }}</p>
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
					<p class="text-4xl font-bold text-gray-900 mt-2">{{ $pendingCount ?? 0 }}</p>
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
					<p class="text-4xl font-bold text-gray-900 mt-2">{{ $processingCount ?? 0 }}</p>
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
					<p class="text-4xl font-bold text-gray-900 mt-2">{{ $completedCount ?? 0 }}</p>
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
		<form method="GET" action="{{ route('admin.documents.index') }}" class="flex items-center gap-3 flex-1">
			<div class="relative w-full max-w-md">
				<div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
						<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
					</svg>
				</div>
				<input type="text" name="search" value="{{ request('search') }}" placeholder="Search ID or name..." class="w-full h-10 border border-gray-200 rounded-lg pl-11 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition" />
			</div>

			<div class="flex gap-2 w-fit">
				<select name="document_type" onchange="this.form.submit()" class="h-10 px-4 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition bg-white w-52">
					<option value="">Document Types</option>
					<option value="Barangay Clearance" {{ request('document_type') == 'Barangay Clearance' ? 'selected' : '' }}>Barangay Clearance</option>
					<option value="Barangay Certificate" {{ request('document_type') == 'Barangay Certificate' ? 'selected' : '' }}>Barangay Certificate</option>
					<option value="Certificate of Indigency" {{ request('document_type') == 'Certificate of Indigency' ? 'selected' : '' }}>Indigency of Certificate</option>
					<option value="Certificate of Residency" {{ request('document_type') == 'Certificate of Residency' ? 'selected' : '' }}>Resident of Certificate</option>
				</select>

				<select name="status" onchange="this.form.submit()" class="h-10 px-4 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition bg-white w-40">
					<option value="">Status</option>
					<option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
					<option value="in progress" {{ request('status') === 'in progress' ? 'selected' : '' }}>In Progress</option>
					<option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
					<option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
				</select>

				@if(request('search') || request('document_type') || request('status'))
					<a href="{{ route('admin.documents.index') }}" class="h-10 px-4 flex items-center gap-2 text-sm border border-gray-300 rounded-lg bg-white hover:bg-gray-50 text-gray-700 transition">
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
					<th class="py-5 px-6 w-1/7">Transaction No.</th>
					<th class="py-5 px-6 w-1/7">Last Name</th>
					<th class="py-5 px-6 w-1/7">First Name</th>
					<th class="py-5 px-6 w-1/7">Document Type</th>
					<th class="py-5 px-6 w-1/7">Date Filed</th>
					<th class="py-5 px-6 w-1/7">Status</th>
					<th class="py-5 px-6 w-1/7">Action</th>
				</tr>
			</thead>
		</table>
		{{-- Scrollable Body --}}
		<div class="overflow-x-auto overflow-y-auto max-h-[360px]">
			<table class="w-full text-sm" style="table-layout: fixed;">
				<tbody class="divide-y divide-gray-100">
				@forelse($documentRequests as $request)
				<tr class="hover:bg-blue-50/70 transition-colors duration-150 ease-in-out text-center">
					<td class="py-5 px-6 w-1/7 font-semibold text-gray-900">{{ $request->tracking_number ?? $request->id }}</td>
					<td class="py-5 px-6 w-1/7 text-gray-700">{{ $request->resident->last_name ?? 'N/A' }}</td>
					<td class="py-5 px-6 w-1/7 text-gray-700">{{ $request->resident->first_name ?? 'N/A' }}</td>
					@if(strpos($request->document_type, 'Indigency') !== false)
						<td class="py-5 px-6 w-1/7 text-gray-600">Indigency of Certificate</td>
					@elseif(strpos($request->document_type, 'Residency') !== false)
						<td class="py-5 px-6 w-1/7 text-gray-600">Resident of Certificate</td>
					@else
						<td class="py-5 px-6 w-1/7 text-gray-600">{{ $request->document_type }}</td>
					@endif
					<td class="py-5 px-6 w-1/7 text-gray-600 text-sm">{{ $request->created_at->format('d/m/Y') }}</td>
					
					<td class="py-5 px-6 w-1/7">
						@php
							$statusKey = strtolower($request->status);
							$colors = [
								'pending' => 'bg-amber-100 text-amber-800 border border-amber-300',
								'in progress' => 'bg-blue-100 text-blue-800 border border-blue-300',
								'completed' => 'bg-emerald-100 text-emerald-800 border border-emerald-300',
								'rejected' => 'bg-red-100 text-red-800 border border-red-300'
							];
							$color = $colors[$statusKey] ?? 'bg-gray-100 text-gray-800 border border-gray-300';
							$statusLabel = ucwords($statusKey);
						@endphp
						<span class="{{ $color }} text-xs font-bold px-3 py-2 rounded-full inline-block shadow-sm">
							{{ $statusLabel }}
						</span>
					</td>

					<td class="py-5 px-6 text-center">
						<div class="flex items-center justify-center gap-1">

							@php
								// 1. Determine Modal ID
								$modalMap = [
									'Barangay Clearance' => 'modalClearance',
									'Barangay Certificate' => 'modalCertificate',
									'Certificate of Indigency' => 'modalIndigency',
									'Barangay Indigency' => 'modalIndigency',
									'Certificate of Residency' => 'modalResidency',
									'Barangay Residency' => 'modalResidency',
								];
								$targetModal = $modalMap[$request->document_type] ?? 'modalClearance';

								// 2. Extract Specific Data from Relationships
								// Initialize variables
								// We prioritize the parent column for common fields
								$lengthOfResidency = $request->length_of_residency ?? 'N/A';
								$validIdNumber = $request->valid_id_number ?? 'N/A'; // NEW field from parent table
								$registeredVoter = $request->registered_voter ?? 'N/A'; // NEW field from parent table
								
								// Fallback logic for old child fields (if needed)
								$voter = 'N/A';
								$validIdNo = 'N/A'; 

								// LOGIC FOR CERTIFICATE / CLEARANCE
								if (in_array($request->document_type, ['Barangay Certificate', 'Barangay Clearance'])) {
									// These fields might still be needed for printing or complex logic
									if($request->certificateData) {
										$voter = $request->certificateData->is_voter;
										$validIdNo = $request->certificateData->cedula_no;
									} elseif ($request->clearanceData) {
										$voter = $request->clearanceData->is_voter;
										$validIdNo = $request->clearanceData->cedula_no;
									}
								} 
								// LOGIC FOR INDIGENCY
								elseif (in_array($request->document_type, ['Certificate of Indigency', 'Barangay Indigency']) && $request->indigencyData) {
									$lengthOfResidency = $request->length_of_residency ?? $request->indigencyData->resident_years ?? 'N/A';
								}
								// LOGIC FOR RESIDENCY
								elseif (in_array($request->document_type, ['Certificate of Residency', 'Barangay Residency']) && $request->residencyData) {
									$lengthOfResidency = $request->length_of_residency ?? $request->residencyData->resident_years ?? 'N/A';
								}
							@endphp
							
							{{-- View Button --}}
							<div class="relative group">
								<button onclick="openDocumentModal('{{ $targetModal }}', this)" 
										class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-blue-50 transition-all duration-200 hover:shadow-md"
								
										{{-- Personal Info --}}
										data-lname="{{ $request->resident->last_name ?? '' }}"
										data-fname="{{ $request->resident->first_name ?? '' }}"
										data-mname="{{ $request->resident->middle_name ?? '' }}"
										data-suffix="{{ $request->resident->suffix ?? '' }}"
										data-age="{{ $request->resident->age ?? '' }}"
										data-dob="{{ $request->resident?->birthdate?->format('F d, Y') ?? '' }}"
										data-pob="{{ $request->resident->place_of_birth ?? '' }}"
										data-gender="{{ $request->resident->gender ?? '' }}"
										data-civil="{{ $request->resident->civil_status ?? '' }}"
										data-citizenship="{{ $request->resident->citizenship ?? '' }}"
								
										{{-- Specific Request Data (Using Parent Table Columns for reliability) --}}
										data-residency="{{ $lengthOfResidency }}" 
										data-voter="{{ $registeredVoter }}" 
										data-valid-id-no="{{ $validIdNumber }}" 
										data-purpose="{{ $request->purpose ?? '' }}"
								
										{{-- Images --}}
										data-id-front="{{ ($request->resident && $request->resident->id_front_path) ? asset('storage/' . $request->resident->id_front_path) : '' }}"
										data-id-back="{{ ($request->resident && $request->resident->id_back_path) ? asset('storage/' . $request->resident->id_back_path) : '' }}"
										data-proof="{{ ($request->proof_file_path) ? asset('storage/' . $request->proof_file_path) : '' }}"
								>
									<svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
										<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
										<path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
									</svg>
								</button>
								<span class="absolute z-[100] top-full mt-1 left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">View</span>
							</div>

							@if(strtolower($request->status) === 'pending')
							{{-- Process Button --}}
							<div class="relative group">
								<button onclick="openInprogressModal('{{ $request->id }}')" class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-amber-50 transition-all duration-200 hover:shadow-md">
									<svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
										<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l2.5 1.5" />
										<path stroke-linecap="round" stroke-linejoin="round" d="M12 3a9 9 0 110 18 9 9 0 010-18z" />
									</svg>
								</button>
								<span class="absolute z-[100] top-full mt-1 left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Process</span>
							</div>
							@endif

							@if(strtolower($request->status) === 'pending')
							{{-- Complete Button --}}
							<div class="relative group">
								<button onclick="openCompletedModal('{{ $request->id }}')" class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-emerald-50 transition-all duration-200 hover:shadow-md">
									<svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
										<circle cx="12" cy="12" r="9" />
										<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.5l2 2 4-4" />
									</svg>
								</button>
								<span class="absolute z-[100] top-full mt-1 left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Complete</span>
							</div>
							@endif

							@if(strtolower($request->status) === 'in progress')
							{{-- Complete Button --}}
							<div class="relative group">
								<button onclick="openCompletedModal('{{ $request->id }}')" class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-emerald-50 transition-all duration-200 hover:shadow-md">
									<svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
										<circle cx="12" cy="12" r="9" />
										<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.5l2 2 4-4" />
									</svg>
								</button>
								<span class="absolute z-[100] top-full mt-1 left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Complete</span>
							</div>
							@endif

						</div>
					</td>
				</tr>
				@empty
				<tr>
					<td colspan="7" class="py-7 text-center text-gray-500">No document requests found.</td>
				</tr>
				@endforelse
				</tbody>
			</table>
		</div>
	</div>

</main>

{{-- ================= MODALS START HERE ================= --}}

{{-- Modal 1: Clearance --}}
<div id="modalClearance" class="modal-container hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] flex items-center justify-center z-[9999]">
	<div class="bg-white w-[800px] max-h-[90vh] rounded-3xl overflow-hidden flex flex-col shadow-2xl border-2 border-gray-100">
		<div class="flex items-center justify-center px-6 py-4" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
			<h1 class="text-white text-xl font-bold font-['Barlow_Semi_Condensed'] tracking-wide">Request for Barangay Clearance</h1>
		</div>
		<div class="px-8 py-6 flex-1 overflow-y-auto">
			<form class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
				<div><label class="font-semibold text-gray-700 block mb-1">Last Name:</label><input class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" disabled></div>
				<div><label class="font-semibold text-gray-700 block mb-1">First Name:</label><input class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" disabled></div>
				<div><label class="font-semibold text-gray-700 block mb-1">Middle Name:</label><input class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" disabled></div>
				<div><label class="font-semibold text-gray-700 block mb-1">Suffix:</label><input class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" disabled></div>
				<div><label class="font-semibold text-gray-700 block mb-1">Age:</label><input class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" disabled></div>
				<div><label class="font-semibold text-gray-700 block mb-1">Date of Birth:</label><input class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" disabled></div>
				<div><label class="font-semibold text-gray-700 block mb-1">Place of Birth:</label><input class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" disabled></div>
				<div><label class="font-semibold text-gray-700 block mb-1">Gender:</label><input class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" disabled></div>
				<div><label class="font-semibold text-gray-700 block mb-1">Civil Status:</label><input class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" disabled></div>
				<div><label class="font-semibold text-gray-700 block mb-1">Citizenship:</label><input class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" disabled></div>
				<div class="col-span-2 mt-4"><label class="font-semibold text-gray-700 block mb-2">Valid ID Attachment</label>
					<div class="mt-2 grid grid-cols-2 gap-4">
						<button type="button" class="border-2 border-dashed border-blue-300 rounded-xl py-8 flex flex-col items-center text-sm w-full bg-blue-50/50 overflow-hidden hover:bg-blue-50 transition-colors">
							<img src="https://cdn-icons-png.flaticon.com/512/685/685655.png" class="js-id-front w-12 opacity-70 mb-2 object-contain transition-all duration-300">
							<span class="mt-2 text-gray-700 font-medium">Front of Valid ID</span>
						</button>
						<button type="button" class="border-2 border-dashed border-blue-300 rounded-xl py-8 flex flex-col items-center text-sm w-full bg-blue-50/50 overflow-hidden hover:bg-blue-50 transition-colors">
							<img src="https://cdn-icons-png.flaticon.com/512/685/685655.png" class="js-id-back w-12 opacity-70 mb-2 object-contain transition-all duration-300">
							<span class="mt-2 text-gray-700 font-medium">Back of Valid ID</span>
						</button>
					</div>
				</div>
				<div class="col-span-2 my-4"><div class="border-t border-gray-200"></div></div>
				<div><label class="font-semibold text-gray-700 block mb-1">Length of Residency:</label><input class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" disabled></div>
				<div><label class="font-semibold text-gray-700 block mb-1">Valid ID Number:</label><input class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" disabled></div>
				<div><label class="font-semibold text-gray-700 block mb-1">Registered Voter:</label><input class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" disabled></div>
				<div><label class="font-semibold text-gray-700 block mb-1">Purpose of Request:</label><input class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50" disabled></div>
			</form>
			<div class="flex justify-end mt-6"><button onclick="closeModal('modalClearance')" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-all duration-200 border border-gray-300 hover:shadow-md">Close</button></div>
		</div>
	</div>
</div>

{{-- Modal 2: Certificate --}}
<div id="modalCertificate" class="modal-container hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] flex items-center justify-center z-[9999]">
	<div class="bg-white w-[800px] max-h-[90vh] rounded-3xl overflow-hidden flex flex-col shadow-2xl border-2 border-gray-100">
		<div class="flex items-center justify-center px-6 py-4" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
			<h1 class="text-white text-xl font-bold font-['Barlow_Semi_Condensed'] tracking-wide">Request for Barangay Certificate</h1>
		</div>
		<div class="px-8 py-6 flex-1 overflow-y-auto">
			<form class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
				<div><label class="font-semibold text-gray-700">Last Name:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">First Name:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Middle Name:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Suffix:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Age:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Date of Birth:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Place of Birth:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Gender:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Civil Status:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Citizenship:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div class="col-span-2 mt-4"><label class="font-semibold text-gray-700">Valid ID Attachment</label>
					<div class="mt-2 grid grid-cols-2 gap-4">
						<button type="button" class="border-2 border-dashed border-gray-500 rounded-xl py-6 flex flex-col items-center text-sm w-full bg-white overflow-hidden">
							<img src="https://cdn-icons-png.flaticon.com/512/685/685655.png" class="js-id-front w-10 opacity-70 mb-2 object-contain transition-all duration-300">
							<span class="mt-2">Front of Valid ID</span>
						</button>
						<button type="button" class="border-2 border-dashed border-gray-500 rounded-xl py-6 flex flex-col items-center text-sm w-full bg-white overflow-hidden">
							<img src="https://cdn-icons-png.flaticon.com/512/685/685655.png" class="js-id-back w-10 opacity-70 mb-2 object-contain transition-all duration-300">
							<span class="mt-2">Back of Valid ID</span>
						</button>
					</div>
				</div>
				<div class="col-span-2 my-4"><div class="border-t border-gray-200"></div></div>
				<div><label class="font-semibold text-gray-700">Length of Residency:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Valid ID Number:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Registered Voter:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Purpose of Request:</label><input class="w-full border border-gray-700 rounded-md px-3 py-6 mt-1" disabled></div>
			</form>
			<div class="flex justify-end mt-6"><button onclick="closeModal('modalCertificate')" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-all duration-200 border border-gray-300 hover:shadow-md">Close</button></div>
		</div>
	</div>
</div>

{{-- Modal 3: Indigency --}}
<div id="modalIndigency" class="modal-container hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] flex items-center justify-center z-[9999]">
	<div class="bg-white w-[800px] max-h-[90vh] rounded-3xl overflow-hidden flex flex-col shadow-2xl border-2 border-gray-100">
		<div class="flex items-center justify-center px-6 py-4" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
			<h1 class="text-white text-xl font-bold font-['Barlow_Semi_Condensed'] tracking-wide">Request for Indigency of Certificate</h1>
		</div>
		<div class="px-8 py-6 flex-1 overflow-y-auto">
			<form class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
				<div><label class="font-semibold text-gray-700">Last Name:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">First Name:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Middle Name:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Suffix:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Age:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Date of Birth:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Place of Birth:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Gender:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Civil Status:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Citizenship:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div class="col-span-2 mt-4"><label class="font-semibold text-gray-700">Valid ID Attachment</label>
					<div class="mt-2 grid grid-cols-2 gap-4">
						<button type="button" class="border-2 border-dashed border-gray-500 rounded-xl py-6 flex flex-col items-center text-sm w-full bg-white overflow-hidden">
							<img src="https://cdn-icons-png.flaticon.com/512/685/685655.png" class="js-id-front w-10 opacity-70 mb-2 object-contain transition-all duration-300">
							<span class="mt-2">Front of Valid ID</span>
						</button>
						<button type="button" class="border-2 border-dashed border-gray-500 rounded-xl py-6 flex flex-col items-center text-sm w-full bg-white overflow-hidden">
							<img src="https://cdn-icons-png.flaticon.com/512/685/685655.png" class="js-id-back w-10 opacity-70 mb-2 object-contain transition-all duration-300">
							<span class="mt-2">Back of Valid ID</span>
						</button>
					</div>
				</div>
				<div class="col-span-2 my-4"><div class="border-t border-gray-200"></div></div>
				<div><label class="font-semibold text-gray-700">Certificate of Being Indigent:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Other Purpose:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div class="col-span-2 mt-4 grid grid-cols-2 gap-4 items-start">
					
					{{-- INDIGENCY PROOF SECTION --}}
					<div>
						<label class="font-semibold text-gray-700 mb-2 block">Proof of Request</label>
						<button type="button" class="border-2 border-dashed border-gray-500 rounded-xl py-6 flex flex-col items-center text-sm w-full bg-white overflow-hidden">
							<img src="https://cdn-icons-png.flaticon.com/512/685/685655.png" class="js-proof-img w-10 opacity-70 mb-2 object-contain transition-all duration-300">
							<span class="mt-2">Uploaded Photo/File</span>
						</button>
					</div>
					
					<div><label class="font-semibold text-gray-700 mb-2 block">Purpose of Request:</label><input class="w-full border border-gray-700 rounded-md px-3 py-12" disabled></div>
				</div>
			</form>
			<div class="flex justify-end"><button onclick="closeModal('modalIndigency')" class="bg-[#A2C4D9] mt-4 hover:bg-gray-400 text-gray-900 px-5 py-1.5 rounded-xl text-[12px] font-bold">CLOSE</button></div>
		</div>
	</div>
</div>

{{-- Modal 4: Residency --}}
<div id="modalResidency" class="modal-container hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] flex items-center justify-center z-[9999]">
	<div class="bg-white w-[800px] max-h-[90vh] rounded-3xl overflow-hidden flex flex-col shadow-2xl border-2 border-gray-100">
		<div class="flex items-center justify-center px-6 py-4" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
			<h1 class="text-white text-xl font-bold font-['Barlow_Semi_Condensed'] tracking-wide">Request for Resident of Certificate</h1>
		</div>
		<div class="px-8 py-6 flex-1 overflow-y-auto">
			<form class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
				<div><label class="font-semibold text-gray-700">Last Name:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">First Name:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Middle Name:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Suffix:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Age:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Date of Birth:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Place of Birth:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Gender:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Civil Status:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Citizenship:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div class="col-span-2 mt-4"><label class="font-semibold text-gray-700">Valid ID Attachment</label>
					<div class="mt-2 grid grid-cols-2 gap-4">
						<button type="button" class="border-2 border-dashed border-gray-500 rounded-xl py-6 flex flex-col items-center text-sm w-full bg-white overflow-hidden">
							<img src="https://cdn-icons-png.flaticon.com/512/685/685655.png" class="js-id-front w-10 opacity-70 mb-2 object-contain transition-all duration-300">
							<span class="mt-2">Front of Valid ID</span>
						</button>
						<button type="button" class="border-2 border-dashed border-gray-500 rounded-xl py-6 flex flex-col items-center text-sm w-full bg-white overflow-hidden">
							<img src="https://cdn-icons-png.flaticon.com/512/685/685655.png" class="js-id-back w-10 opacity-70 mb-2 object-contain transition-all duration-300">
							<span class="mt-2">Back of Valid ID</span>
						</button>
					</div>
				</div>
			<div class="col-span-2 my-4"><div class="border-t border-gray-200"></div></div>
				<div><label class="font-semibold text-gray-700">Length of Residency:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Valid ID Number:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Registered Voter:</label><input class="w-full border border-gray-700 rounded-md px-3 py-1.5 mt-1" disabled></div>
				<div><label class="font-semibold text-gray-700">Purpose of Request:</label><input class="w-full border border-gray-700 rounded-md px-3 py-6 mt-1" disabled></div>
			</form>
			<div class="flex justify-end mt-6"><button onclick="closeModal('modalResidency')" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-all duration-200 border border-gray-300 hover:shadow-md">Close</button></div>
		</div>
	</div>
</div>

{{-- Modal 5: Process Confirmation --}}
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
		<h2 class="font-bold text-2xl mb-4 text-gray-800 tracking-tight">Process Document Request</h2>
		
		<!-- Description -->
		<div class="bg-amber-50 rounded-xl p-4 mb-6 border border-amber-200">
			<p class="text-sm text-gray-700 leading-relaxed">
				You are accepting this document request. Once confirmed, the status will be changed to "In Progress" and the resident will be notified.
			</p>
		</div>
		
		<!-- Action Buttons -->
		<form id="inprogressForm" method="POST" action="">
			@csrf
			@method('PUT')
			<input type="hidden" name="status" value="in progress">
			<div class="flex justify-center gap-4 mt-6">
				<button type="button" onclick="closeInprogressModal()" 
						class="px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-md border border-gray-300">
					Cancel
				</button>
				<button type="submit" 
						class="px-8 py-3 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-lg transform hover:scale-105">
					Process Request
				</button>
			</div>
		</form>
	</div>
</div>

{{-- Modal 6: Complete Confirmation --}}
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
		<h2 class="font-bold text-2xl mb-4 text-gray-800 tracking-tight">Complete Document Request</h2>
		
		<!-- Description -->
		<div class="bg-green-50 rounded-xl p-4 mb-6 border border-green-200">
			<p class="text-sm text-gray-700 leading-relaxed">
				The document is ready for pick up. Once confirmed, the status will be changed to "Completed" and the resident will be notified.
			</p>
		</div>
		
		<!-- Action Buttons -->
		<form id="completedForm" method="POST" action="">
			@csrf
			@method('PUT')
			<input type="hidden" name="status" value="completed">
			<div class="flex justify-center gap-4 mt-6">
				<button type="button" onclick="closeCompletedModal()" 
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
		</form>
	</div>
</div>

{{-- Shared Backdrop for All Modals --}}
<div id="modal-backdrop" class="hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] bg-black/50 backdrop-blur-sm z-[9998]"></div>

@endsection

@push('scripts')
<script src="{{ asset('js/admin-documents.js') }}" defer></script>
@endpush