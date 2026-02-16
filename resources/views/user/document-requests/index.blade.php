@extends('layouts.user', ['title' => 'Request a Document'])

@section('content')
<style>
	.justify-start {
		justify-content: center;
	}
	
	/* Full-screen blurred backdrop excluding navbar */
	#modal-backdrop {
		position: fixed;
		top: 80px;
		left: 0;
		right: 0;
		bottom: 0;
		width: 100vw;
		height: calc(100vh - 80px);
		background: rgba(0, 0, 0, 0.45);
		backdrop-filter: blur(10px);
		-webkit-backdrop-filter: blur(10px);
		z-index: 50;
		pointer-events: auto;
	}
	
	/* Modal stays clear and interactive and centered */
	.modal-container {
		filter: none !important;
		pointer-events: auto;
	}

	/* Checkbox styling for all document request forms */
	input[type="checkbox"] {
		appearance: none;
		-webkit-appearance: none;
		-moz-appearance: none;
		width: 1rem;
		height: 1rem;
		border: 2px solid #d1d5db;
		border-radius: 0.25rem;
		background-color: white;
		cursor: pointer;
	}
	
	input[type="checkbox"]:checked {
		background-color: #2563eb;
		border-color: #2563eb;
		background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
		background-size: 100% 100%;
		background-position: center;
		background-repeat: no-repeat;
	}
	
	input[type="checkbox"]:focus {
		outline: 2px solid #3b82f6;
		outline-offset: 2px;
	}
</style>

<main id="page-content">

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 md:gap-6 mb-6 sm:mb-8">
	<div class="p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-lg border border-amber-100 bg-gradient-to-br from-white to-amber-50 hover:shadow-xl transition-shadow duration-300">
		<div class="flex items-center justify-between gap-3">
			<div>
				<p class="text-2xl sm:text-3xl md:text-4xl font-bold text-amber-600">{{ $stats['pending'] ?? 0 }}</p>
				<p class="text-xs sm:text-sm font-semibold text-gray-600 mt-1 sm:mt-2 uppercase tracking-wide">Pending</p>
			</div>
			<div class="w-12 h-12 sm:w-14 sm:h-14 bg-amber-100 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0">
				<svg class="w-6 h-6 sm:w-8 sm:h-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
					<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
				</svg>
			</div>
		</div>
	</div>
	<div class="p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-lg border border-blue-100 bg-gradient-to-br from-white to-blue-50 hover:shadow-xl transition-shadow duration-300">
		<div class="flex items-center justify-between gap-3">
			<div>
				<p class="text-2xl sm:text-3xl md:text-4xl font-bold text-blue-600">{{ $stats['processing'] ?? 0 }}</p>
				<p class="text-xs sm:text-sm font-semibold text-gray-600 mt-1 sm:mt-2 uppercase tracking-wide">In Progress</p>
			</div>
			<div class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-100 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0">
				<svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
					<path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
				</svg>
			</div>
		</div>
	</div>
	<div class="p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-lg border border-emerald-100 bg-gradient-to-br from-white to-emerald-50 hover:shadow-xl transition-shadow duration-300">
		<div class="flex items-center justify-between gap-3">
			<div>
				<p class="text-2xl sm:text-3xl md:text-4xl font-bold text-emerald-600">{{ $stats['completed'] ?? 0 }}</p>
				<p class="text-xs sm:text-sm font-semibold text-gray-600 mt-1 sm:mt-2 uppercase tracking-wide">Completed</p>
			</div>
			<div class="w-12 h-12 sm:w-14 sm:h-14 bg-emerald-100 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0">
				<svg class="w-6 h-6 sm:w-8 sm:h-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
					<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
				</svg>
			</div>
		</div>
	</div>
</div>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4 sm:mb-6 relative">
	<h3 class="text-lg sm:text-xl md:text-2xl font-bold text-[#134573] flex items-center gap-2 sm:gap-3">
		<div class="w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-md flex-shrink-0">
			<svg class="w-5 h-5 sm:w-5.5 sm:h-5.5 md:w-6 md:h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
				<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
			</svg>
		</div>
		<span class="truncate">Document Requests</span>
	</h3>

	<div class="relative inline-block text-left w-auto ml-auto" x-data="{ open: false, selectDocument(type) { const modalId = { 'Barangay Certificate': 'modalCertificate', 'Barangay Clearance': 'modalClearance', 'Barangay Indigency': 'modalIndigency', 'Barangay Residency': 'modalResidency' }[type]; if (modalId) { openModal(modalId); } } }">
		<button @click="open = !open" class="w-auto bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-3 sm:px-6 py-1.5 sm:py-2.5 text-xs sm:text-sm rounded-lg font-semibold shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-1.5 sm:gap-2">
			<svg class="w-3.5 h-3.5 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
				<path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
			</svg>
			<span class="truncate text-xs sm:text-sm">New Request</span>
			<svg class="w-3 h-3 sm:w-4 sm:h-4" :class="open ? 'transform rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
				<path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
			</svg>
		</button>
		
		<div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-72 sm:w-56 bg-white border border-gray-200 rounded-xl shadow-xl z-50 overflow-hidden" style="display: none;">
			<ul class="text-gray-700 text-lg sm:text-sm divide-y divide-gray-100">
				<li @click="selectDocument('Barangay Certificate'); open = false" class="px-6 py-5 sm:px-4 sm:py-3 hover:bg-blue-50 cursor-pointer transition-colors font-medium">Barangay Certificate</li>
				<li @click="selectDocument('Barangay Clearance'); open = false" class="px-6 py-5 sm:px-4 sm:py-3 hover:bg-blue-50 cursor-pointer transition-colors font-medium">Barangay Clearance</li>
				<li @click="selectDocument('Barangay Indigency'); open = false" class="px-6 py-5 sm:px-4 sm:py-3 hover:bg-blue-50 cursor-pointer transition-colors font-medium">Barangay Indigency</li>
				<li @click="selectDocument('Barangay Residency'); open = false" class="px-6 py-5 sm:px-4 sm:py-3 hover:bg-blue-50 cursor-pointer transition-colors font-medium">Barangay Residency</li>
			</ul>
		</div>
	</div>
</div>



<div class="bg-white shadow-xl rounded-lg sm:rounded-2xl overflow-x-auto border border-gray-100">
<table class="table w-full text-xs sm:text-sm md:text-base border-collapse min-w-max">
<thead style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
	<tr class="text-xs sm:text-sm whitespace-nowrap text-white">
		<th class="px-3 sm:px-5 py-3 sm:py-4 font-bold uppercase tracking-wide text-center">Transaction ID</th>
		<th class="px-3 sm:px-5 py-3 sm:py-4 font-bold uppercase tracking-wide text-center">Last Name</th>
		<th class="px-3 sm:px-5 py-3 sm:py-4 font-bold uppercase tracking-wide text-center">First Name</th>
		<th class="px-3 sm:px-5 py-3 sm:py-4 font-bold uppercase tracking-wide text-center">Document Type</th>
		<th class="px-3 sm:px-5 py-3 sm:py-4 font-bold uppercase tracking-wide text-center">Purpose</th>
		<th class="px-3 sm:px-5 py-3 sm:py-4 font-bold uppercase tracking-wide text-center">Date Requested</th>
		<th class="px-3 sm:px-5 py-3 sm:py-4 font-bold uppercase tracking-wide text-center">Status</th>
	</tr>
</thead>
	<tbody class="divide-y divide-gray-100">
		@forelse($myRequests as $request)
		<tr class="text-xs sm:text-sm hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
			<td class="px-3 sm:px-5 py-3 sm:py-4 font-mono font-semibold text-blue-600 whitespace-nowrap text-center">{{ $request->tracking_number ?? 'N/A' }}</td>
			<td class="px-3 sm:px-5 py-3 sm:py-4 font-medium text-gray-700 whitespace-nowrap text-center">{{ Auth::user()->last_name }}</td>
			<td class="px-3 sm:px-5 py-3 sm:py-4 font-medium text-gray-700 whitespace-nowrap text-center">{{ Auth::user()->first_name }}</td>
			<td class="px-3 sm:px-5 py-3 sm:py-4 font-semibold text-gray-800 text-center">{{ $request->document_type }}</td>
			
			<td class="px-3 sm:px-5 py-3 sm:py-4 text-gray-600 whitespace-nowrap text-center">
				{{ \Illuminate\Support\Str::before($request->purpose, ' |') }}
			</td>

			<td class="px-3 sm:px-5 py-3 sm:py-4 text-gray-600 whitespace-nowrap text-center">{{ $request->created_at->format('d/m/Y') }}</td>
			<td class="px-3 sm:px-5 py-3 sm:py-4 text-center">
				@php
					$statusLower = strtolower($request->status);
					$statusColor = match($statusLower) {
						'pending' => 'bg-amber-500/10 text-amber-700 border border-amber-200',
						'in progress' => 'bg-blue-500/10 text-blue-700 border border-blue-200',
						'completed' => 'bg-emerald-500/10 text-emerald-700 border border-emerald-200',
						'rejected' => 'bg-red-500/10 text-red-700 border border-red-200',
						default => 'bg-gray-500/10 text-gray-700 border border-gray-200'
					};
					$statusDisplay = ucfirst(strtolower($request->status));
				@endphp
				<span class="{{ $statusColor }} text-xs font-bold px-2 sm:px-3 py-1 sm:py-1.5 rounded-full inline-flex items-center justify-center whitespace-nowrap">
					{{ $statusDisplay }}
				</span>
			</td>
		</tr>
		@empty
		<tr>
			<td colspan="7" class="text-center py-8 sm:py-12">
				<div class="flex flex-col items-center justify-center">
					<div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-full flex items-center justify-center mb-3 sm:mb-4">
						<svg class="w-8 h-8 sm:w-10 sm:h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
						</svg>
					</div>
					<p class="text-gray-500 font-medium text-sm sm:text-base">You have no document requests yet.</p>
					<p class="text-gray-400 text-xs sm:text-sm mt-1">Click "New Request" to get started.</p>
				</div>
			</td>
		</tr>
		@endforelse
	</tbody>
	</table>
</div>

<div class="flex justify-end mt-4 sm:mt-6 px-0">
	<a href="{{ route('home') }}">
	<button class="w-full sm:w-auto bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 font-semibold px-6 sm:px-8 py-2 sm:py-2.5 rounded-lg transition-all duration-200 shadow-md border border-gray-300 flex items-center justify-center sm:justify-start gap-2 text-sm">
		<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
			<path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
		</svg>
		BACK
	</button>
	</a>
</div>
</main>

{{-- Global Backdrop --}}
<div id="modal-backdrop" class="hidden"></div>

{{-- ================================================================================= --}}
{{-- MODAL 1: Barangay Certificate --}}
<div id="modalCertificate" class="modal-container hidden fixed inset-0 flex items-center justify-center z-[70] p-4 sm:p-0" onclick="if(event.target === this) closeModal('modalCertificate')">
	<div class="modal-content bg-white w-full sm:w-[560px] max-h-[90vh] sm:max-h-none rounded-2xl shadow-2xl overflow-y-auto sm:overflow-hidden flex flex-col border-2 border-gray-100">
	<div class="px-4 sm:px-6 py-4 rounded-t-2xl" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
		<h1 class="text-white font-bold text-lg sm:text-xl text-center uppercase tracking-wide">APPLICATION FOR BARANGAY CERTIFICATE</h1>
	</div>
	<div class="px-4 sm:px-8 py-6 text-left flex-1 flex flex-col justify-between overflow-y-auto">
		<div>
			<form id="formCertificate">
				@csrf
				<input type="hidden" name="document_type" value="Barangay Certificate">

				<div class="space-y-5">
					<div>
						<label class="block text-sm font-semibold text-gray-700 mb-2">Length of Residency</label>
						<input type="text" name="length_of_residency" class="w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="e.g., 5 years" required>
					</div>
					<div>
					<label class="block text-sm font-semibold text-gray-700 mb-2">Valid ID Number</label>
					<input type="text" name="valid_id_number" class="w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Enter ID number" required>
					</div>
					<div>
					<label class="block text-sm font-semibold text-gray-700 mb-2">Registered Voter</label>
					<select name="registered_voter" class="w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-4 sm:px-4 py-3 sm:py-2.5 text-base sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all cursor-pointer appearance-none" required>
							<option value="" disabled selected hidden>Select option</option>
							<option value="Yes">Yes</option>
							<option value="No">No</option>
						</select>
					</div>
					<div>
						<label class="block text-sm font-semibold text-gray-700 mb-2">Purpose of Request</label>
						<input type="text" name="purpose" required class="w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="State your purpose">
					</div>
					<div class="flex items-start pt-2">
						<input type="checkbox" required class="mt-1 mr-3 w-4 h-4 text-blue-600 border-2 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer">
						<label class="text-xs text-gray-600 leading-relaxed">I certify that the information provided above is accurate and complete to the best of my knowledge.</label>
					</div>
				</div>

				<div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
					<button type="button" onclick="closeModal('modalCertificate')" class="px-6 py-2.5 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 font-semibold rounded-lg transition-all duration-200 border border-gray-300">CANCEL</button>
					<button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">SUBMIT</button>
				</div>
			</form>
			</div>
		</div>
</div>
</div>

{{-- MODAL 2: Barangay Clearance --}}
<div id="modalClearance" class="modal-container hidden fixed inset-0 flex items-center justify-center z-[70] p-4 sm:p-0" onclick="if(event.target === this) closeModal('modalClearance')">
    <div class="modal-content bg-white w-full sm:w-[560px] max-h-[90vh] sm:max-h-none rounded-2xl shadow-2xl overflow-y-auto sm:overflow-hidden flex flex-col border-2 border-gray-100">
    <div class="px-4 sm:px-6 py-4 rounded-t-2xl" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
        <h1 class="text-white font-bold text-lg sm:text-xl text-center uppercase tracking-wide">APPLICATION FOR BARANGAY CLEARANCE</h1>
    </div>
    <div class="px-4 sm:px-8 py-6 text-left flex-1 flex flex-col justify-between overflow-y-auto">
        <div>
            <form id="formClearance">
                @csrf
                <input type="hidden" name="document_type" value="Barangay Clearance">

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Length of Residency</label>
                        <input type="text" name="length_of_residency" class="w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="e.g., 5 years" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Valid ID Number</label>
                        <input type="text" name="valid_id_number" class="w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Enter ID number" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Registered Voter</label>
                        <select name="registered_voter" class="w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-4 py-3 text-base sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all cursor-pointer appearance-none" required>
                            <option value="" disabled selected hidden>Select option</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Purpose of Request</label>
                        <input type="text" name="purpose" required class="w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="State your purpose">
                    </div>
                    <div class="flex items-start pt-2">
                        <input type="checkbox" required class="mt-1 mr-3 w-4 h-4 text-blue-600 border-2 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        <label class="text-xs text-gray-600 leading-relaxed">I certify that the information provided above is accurate and complete to the best of my knowledge.</label>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                    <button type="button" onclick="closeModal('modalClearance')" class="px-6 py-2.5 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 font-semibold rounded-lg transition-all duration-200 border border-gray-300">CANCEL</button>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">SUBMIT</button>
                </div>
            </form>
            </div>
        </div>
</div>
</div>

{{-- MODAL 3: Barangay Indigency --}}
<div id="modalIndigency" class="modal-container hidden fixed inset-0 flex items-center justify-center z-[70] p-4 sm:p-0">
	<div class="modal-content bg-white w-full sm:w-[560px] max-h-[90vh] sm:max-h-none overflow-y-auto sm:overflow-hidden rounded-2xl shadow-2xl overflow-hidden flex flex-col border-2 border-gray-100">

		<!-- Header -->
		<div class="px-4 sm:px-6 py-4 rounded-t-2xl" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
			<h1 class="text-white font-bold text-lg sm:text-xl text-center uppercase tracking-wide">APPLICATION FOR INDIGENCY CLEARANCE</h1>
		</div>

		<!-- Body -->
		<div class="px-4 sm:px-8 py-6 text-left flex-1 flex flex-col justify-between">
			<div>
				<form id="formIndigency" class="space-y-5" enctype="multipart/form-data">
					@csrf
					<input type="hidden" name="document_type" value="Certificate of Indigency">
					<input type="hidden" name="resident_years" value="N/A">

					<div>
					<label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Indigency Category</label>
					<select name="indigency_category" class="w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-4 sm:px-4 py-3 sm:py-2.5 text-base sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all cursor-pointer appearance-none">
							<option value="" disabled selected hidden>Select category</option>
							<option value="Medical">Medical</option>
							<option value="Educational">Educational</option>
							<option value="Burial">Burial</option>
							<option value="Legal">Legal</option>
							<option value="Financial">Financial</option>
						</select>
					</div>
			
					<div>
					<label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Other Purpose (Optional)</label>
					<input type="text" name="other_purpose" class="w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Specify if not in category">
					</div>

					<div>
					<label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Proof of Request (Optional)</label>
					<input type="file" name="proof_file" class="w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all file:mr-2 sm:file:mr-4 file:py-1 file:px-2 sm:file:px-3 file:rounded-md file:border-0 file:text-xs sm:file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
					</div>

					<div>
					<label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Purpose of Request</label>
					<input type="text" name="purpose" class="w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="State your purpose" required>
					</div>
					<div class="flex items-start pt-2">
						<input type="checkbox" class="mt-1 mr-3 w-4 h-4 text-blue-600 border-2 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer">
						<label class="text-xs text-gray-600 leading-relaxed">I certify that the information provided above is accurate and complete to the best of my knowledge.</label>
					</div>
				</form>
			</div>

			<!-- Footer buttons -->
			<div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
				<button type="button" onclick="closeModal('modalIndigency')" class="px-6 py-2.5 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 font-semibold rounded-lg transition-all duration-200 border border-gray-300">CANCEL</button>
				<button type="submit" form="formIndigency" class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">SUBMIT</button>
			</div>
		</div>
	</div>
</div>

{{-- MODAL 4: Barangay Residency --}}
<div id="modalResidency" class="modal-container hidden fixed inset-0 flex items-center justify-center z-[70] p-4 sm:p-0">
	<div class="modal-content bg-white w-full sm:w-[560px] max-h-[90vh] sm:max-h-none overflow-y-auto sm:overflow-hidden rounded-2xl shadow-2xl overflow-hidden flex flex-col border-2 border-gray-100">

		<!-- Header -->
		<div class="px-4 sm:px-6 py-4 rounded-t-2xl" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
			<h1 class="text-white font-bold text-lg sm:text-xl text-center uppercase tracking-wide">APPLICATION FOR RESIDENT CERTIFICATE</h1>
		</div>

		<!-- Body -->
		<div class="px-4 sm:px-8 py-6 text-left flex-1 flex flex-col justify-between">
			
			<div>
				<form id="formResidency" class="space-y-5">
					@csrf
					<input type="hidden" name="document_type" value="Certificate of Residency">
					<input type="hidden" name="civil_status" value="N/A">
					<input type="hidden" name="citizenship" value="N/A">

					<div>
					<label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Length of Residency</label>
					<input type="text" name="resident_years" class="w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="e.g., 5 years" required>
					</div>

					<div>
						<label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Valid ID Number</label>
						<input type="text" name="valid_id_number" class="w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Enter ID number" required>
					</div>

					<div>
						<label class="block text-sm font-semibold text-gray-700 mb-2">Registered Voter</label>
						<select name="registered_voter" class="w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-4 py-3 text-base sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all cursor-pointer appearance-none" required>
							<option value="" disabled selected hidden>Select option</option>
							<option value="Yes">Yes</option>
							<option value="No">No</option>
						</select>
					</div>

					<div>
					<label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">Purpose of Request</label>
					<input type="text" name="purpose" class="w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-3 sm:px-4 py-2 sm:py-2.5 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="State your purpose" required>
					</div>
					<div class="flex items-start pt-2">
						<input type="checkbox" class="mt-1 mr-3 w-4 h-4 text-blue-600 border-2 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer">
						<label class="text-xs text-gray-600 leading-relaxed">I certify that the information provided above is accurate and complete to the best of my knowledge.</label>
					</div>
				</form>
			</div>

			<!-- Footer buttons -->
		<div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
			<button type="button" onclick="closeModal('modalResidency')" class="px-6 py-2.5 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 font-semibold rounded-lg transition-all duration-200 border border-gray-300">CANCEL</button>
			<button type="submit" form="formResidency" class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">SUBMIT</button>
			</div>

		</div>
	</div>
</div>

{{-- ... (Success Modals and Scripts remain unchanged) ... --}}

@if(session('success'))
<div id="sessionSuccessModal" class="modal-container fixed inset-0 flex items-center justify-center z-[80] p-4 sm:p-0">
	<div class="bg-[#DDE1E5] w-full sm:w-[480px] rounded-2xl shadow-xl p-6 sm:p-10 relative text-center">
	<div class="flex justify-center mb-3 sm:mb-4">
		<div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-4 border-green-700 flex items-center justify-center">
			<svg xmlns="http://www.w3.org/2000/svg" class="w-8 sm:w-10 h-8 sm:h-10 text-green-700" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
				</svg>
			</div>
		</div>
		<h2 class="font-extrabold text-xl mb-3 text-black tracking-wide">
			REQUEST SUBMITTED SUCCESSFULLY!
		</h2>
		<br>
		<p class="text-xs sm:text-sm text-black leading-relaxed">
			Transaction ID: {{ session('success_id') }} <br>
			Your request will be processed within 1 day. You may claim your
			document at the barangay once it’s ready for release.
		</p>
		<button onclick="closeSuccessModal('sessionSuccessModal')"
			class="mt-6 sm:mt-7 bg-[#A2C4D9] hover:bg-[#94B8CC] px-5 sm:px-7 py-1 rounded-2xl text-xs sm:text-sm font-semibold text-black transition">
			CLOSE
		</button>
	</div>
</div>
@endif

<div id="ajaxSuccessModal" class="modal-container hidden fixed inset-0 flex items-center justify-center z-[80] p-4 sm:p-0">
	<div class="bg-white w-full sm:w-[480px] rounded-2xl shadow-2xl p-6 sm:p-10 relative z-[9999] text-center border-2 border-gray-100">
	<div class="flex justify-center mb-4 sm:mb-6">
		<div class="w-16 h-16 sm:w-24 sm:h-24 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-lg">
			<svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 sm:w-12 sm:h-12 text-white" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
			</svg>
		</div>
	</div>
	<h2 class="font-extrabold text-lg sm:text-2xl mb-2 bg-gradient-to-r from-emerald-600 to-emerald-500 bg-clip-text text-transparent tracking-wide">
		REQUEST SUBMITTED SUCCESSFULLY!
	</h2>
	<p id="ajaxSuccessMessage" class="text-gray-600 text-xs sm:text-sm leading-relaxed mb-4 sm:mb-6"></p>
	<button onclick="window.location.reload()" class="w-full sm:w-auto bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white px-6 sm:px-8 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
		CLOSE
	</button>
	</div>
</div>

<script>
	const backdrop = document.getElementById('modal-backdrop');
	if (backdrop && backdrop.parentElement !== document.body) {
		document.body.appendChild(backdrop); // ensure fixed overlay anchors to viewport, not a transformed parent
	}

	function showBackdrop() {
		if (backdrop) backdrop.classList.remove('hidden');
	}

	function hideBackdrop() {
		if (backdrop) backdrop.classList.add('hidden');
	}

	function openModal(id) {
		document.getElementById(id).classList.remove('hidden');
		showBackdrop();
	}
	function closeModal(id) {
		document.getElementById(id).classList.add('hidden');
		hideBackdrop();
	}

	function closeSuccessModal(id) {
		const modal = document.getElementById(id);
		if (modal) modal.remove();
		hideBackdrop();
	}

	// Close on ESC
	document.addEventListener('keydown', function(event) {
		if (event.key === "Escape") {
			['modalCertificate', 'modalClearance', 'modalIndigency', 'modalResidency'].forEach(id => {
				const modal = document.getElementById(id);
				if (modal && !modal.classList.contains('hidden')) {
					closeModal(id);
				}
			});
		}
	});

	document.addEventListener('DOMContentLoaded', function () {
		backdrop.classList.add('hidden'); // Ensure backdrop is hidden on load

		// Move success modals to body to ensure they cover the header
		['sessionSuccessModal', 'ajaxSuccessModal'].forEach(id => {
			const el = document.getElementById(id);
			if (el) document.body.appendChild(el);
		});

		const sessionSuccessModal = document.getElementById('sessionSuccessModal');
		if (sessionSuccessModal && !sessionSuccessModal.classList.contains('hidden')) {
			showBackdrop();
		}

		const forms = {
			'formCertificate': 'modalCertificate',
			'formClearance': 'modalClearance',
			'formIndigency': 'modalIndigency',
			'formResidency': 'modalResidency'
		};

		for (const formId in forms) {
			const form = document.getElementById(formId);
			if (form) {
				form.addEventListener('submit', function (e) {
					e.preventDefault();
					submitForm(form, forms[formId]);
				});
			}
		}
	});

	async function submitForm(form, modalId) {
		const formData = new FormData(form);
		let submitButton = form.querySelector('button[type="submit"]');
		if (!submitButton) {
			submitButton = document.querySelector(`button[form="${form.id}"][type="submit"]`);
		}
		if (submitButton) {
			submitButton.disabled = true;
			submitButton.textContent = 'SUBMITTING...';
		}

		// You can add logic here to clear old validation messages

		try {
			const response = await fetch("{{ route('user.document.store') }}", {
				method: 'POST',
				body: formData,
				headers: {
					'Accept': 'application/json',
				},
			});

			if (response.status === 422) { // Validation error
				const data = await response.json();
				let errorMessages = 'Please fix the following errors:\n';
				for (const key in data.errors) {
					errorMessages += `- ${data.errors[key][0]}\n`;
				}
				alert(errorMessages);
			} else if (response.ok) {
				const data = await response.json();
				closeModal(modalId);
				
				const successModal = document.getElementById('ajaxSuccessModal');
				const successMessage = document.getElementById('ajaxSuccessMessage');
				successMessage.innerHTML = `Transaction ID: ${data.tracking_number} <br> Your request will be processed within 1 day. You may claim your document at the barangay once it’s ready for release.`;
				showBackdrop();
				successModal.classList.remove('hidden');
			} else {
				const errorText = await response.text();
				throw new Error('An unexpected error occurred. Please try again. Status: ' + response.status + '. Response: ' + errorText);
			}
		} catch (error) {
			alert(error.message);
		} finally {
			if (submitButton) {
				submitButton.disabled = false;
				submitButton.textContent = 'SUBMIT';
			}
		}
	}
</script>

@endsection