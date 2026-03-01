@extends('layouts.user', ['title' => 'Complaints'])

@section('content')

@push('styles')
	<link rel="stylesheet" href="{{ asset('css/modals.css') }}">
	<link rel="stylesheet" href="{{ asset('css/form-inputs.css') }}">
@endpush

{{-- Assuming $stats is passed from the controller with keys: 'pending', 'processing', 'completed' --}}
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 md:gap-6 mb-6 sm:mb-8">
    
    {{-- Pending Card --}}
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

    {{-- In Progress Card --}}
    <div class="p-4 sm:p-6 rounded-xl sm:rounded-2xl shadow-lg border border-blue-100 bg-gradient-to-br from-white to-blue-50 hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="text-2xl sm:text-3xl md:text-4xl font-bold text-blue-600">{{ $stats['in_progress'] ?? 0 }}</p>
                <p class="text-xs sm:text-sm font-semibold text-gray-600 mt-1 sm:mt-2 uppercase tracking-wide">In Progress</p>
            </div>
            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-100 rounded-lg sm:rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Completed Card --}}
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

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0 mb-6 sm:mb-8">
    <h3 class="text-xl sm:text-2xl font-bold text-[#134573] flex items-center gap-2 sm:gap-3">
      <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-md">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        Complaints
    </h3>
    <button id="addButton" class="w-full sm:w-auto bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 sm:px-6 py-2 sm:py-2.5 text-xs sm:text-sm rounded-lg font-semibold shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center justify-center sm:justify-start gap-2" onclick="openModal('modalGeneralComplaint')">
        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        <span>File a Complaint</span>
    </button>
</div>

<div class="bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden">
    
    <table class="w-full text-sm" style="table-layout: fixed;">
      <thead style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%); color: white;" class="shadow-sm">
        <tr class="text-sm font-semibold uppercase tracking-widest text-center">
          <th class="py-5 px-4 whitespace-nowrap" style="width: 12%;">Transaction ID</th>
          <th class="py-5 px-4 whitespace-nowrap" style="width: 10%;">Last Name</th>
          <th class="py-5 px-4 whitespace-nowrap" style="width: 10%;">First Name</th>
          <th class="py-5 px-4 whitespace-nowrap" style="width: 18%;">Complaint Type</th>
          <th class="py-5 px-4 whitespace-nowrap" style="width: 12%;">Date Filed</th>
          <th class="py-5 px-4 whitespace-nowrap" style="width: 12%;">Date Resolved</th>
          <th class="py-5 px-4 whitespace-nowrap" style="width: 12%;">Status</th>
        </tr>
      </thead>
    </table>
    
    <div class="overflow-x-auto overflow-y-auto" style="max-height: 391px;">
      <table class="w-full text-sm" style="table-layout: fixed;">
        <tbody class="divide-y divide-gray-100">
      {{-- Assuming $complaints is passed from the controller --}}
      @php use Illuminate\Support\Facades\Auth; @endphp
      @forelse ($complaints ?? [] as $complaint)
      <tr class="hover:bg-blue-50/70 transition-colors duration-150 ease-in-out text-center">
        <td class="py-5 px-4 font-semibold text-red-500 whitespace-nowrap" style="width: 12%;">
          {{ is_object($complaint) && isset($complaint->transaction_no) ? $complaint->transaction_no : 'N/A' }}
        </td>
        {{-- Displaying current user's name as they are the filer --}}
        <td class="py-5 px-4 text-gray-700 whitespace-nowrap" style="width: 10%;">
          {{ Auth::check() && Auth::user() && isset(Auth::user()->last_name) ? Auth::user()->last_name : 'N/A' }}
        </td>
        <td class="py-5 px-4 text-gray-700 whitespace-nowrap" style="width: 10%;">
          {{ Auth::check() && Auth::user() && isset(Auth::user()->first_name) ? Auth::user()->first_name : 'N/A' }}
        </td>
        <td class="py-5 px-4 font-semibold text-gray-800" style="width: 18%;">
            <div class="overflow-hidden text-ellipsis whitespace-nowrap">
                {{ is_object($complaint) && isset($complaint->complaint_type) ? $complaint->complaint_type : 'N/A' }}
            </div>
        </td>
        <td class="py-5 px-4 text-gray-600 text-sm whitespace-nowrap" style="width: 12%;">
          {{ is_object($complaint) && isset($complaint->created_at) && $complaint->created_at ? \Carbon\Carbon::parse($complaint->created_at)->format('d/m/Y') : 'N/A' }}
        </td>
        <td class="py-5 px-4 text-gray-600 text-sm whitespace-nowrap" style="width: 12%;">
          {{ is_object($complaint) && isset($complaint->date_completed) && $complaint->date_completed ? \Carbon\Carbon::parse($complaint->date_completed)->format('d/m/Y') : '—' }}
        </td>
        <td class="py-5 px-4 whitespace-nowrap" style="width: 12%;">
            @php
                $statusLower = is_object($complaint) && isset($complaint->status) ? strtolower($complaint->status) : '';
                $statusColor = match($statusLower) {
                    'pending' => 'bg-amber-100 text-amber-800 border border-amber-300',
                    'in progress' => 'bg-blue-100 text-blue-800 border border-blue-300',
                    'completed' => 'bg-emerald-100 text-emerald-800 border border-emerald-300',
                    default => 'bg-gray-100 text-gray-800 border border-gray-300'
                };
                $statusDisplay = $statusLower === 'in progress' ? 'In Progress' : ucfirst($statusLower);
            @endphp
            <span class="{{ $statusColor }} text-xs font-bold px-3 py-2 rounded-full inline-block whitespace-nowrap shadow-sm" style="font-size:12px;line-height:1.2;">
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                </div>
                <p class="text-gray-500 font-medium text-sm sm:text-base">You have no complaints yet.</p>
                <p class="text-gray-400 text-xs sm:text-sm mt-1">Click "File a Complaint" to get started.</p>
              </div>
            </td>
          </tr>
      @endforelse
        </tbody>
      </table>
    </div>
  </div>

<div class="flex justify-end mt-4 sm:mt-6 px-0">
  <a href="{{ route('home') }}">
    <button class="bg-gray-200 hover:bg-gray-300 text-[14px] text-gray-700 font-bold px-6 sm:px-8 py-2.5 sm:py-3 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl border border-gray-300 flex items-center gap-2">
      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
      </svg>
      BACK
    </button>
  </a>
</div>


{{-- ======================================================================== --}}
{{-- USER COMPLAINT MODAL (WITH NAME ATTRIBUTES AND FORM ID) --}}
{{-- ======================================================================== --}}
<div id="modalGeneralComplaint" class="modal-container hidden fixed inset-0 w-full h-screen flex items-center justify-center z-[9999] p-4 sm:p-0" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  <!-- Modal Panel -->
  <div class="flex min-h-full items-center justify-center text-center z-50 relative pointer-events-none">
    <div class="bg-white w-full sm:w-[600px] max-h-[80vh] sm:max-h-none overflow-y-auto sm:overflow-visible rounded-2xl flex flex-col pointer-events-auto shadow-2xl border-2 border-gray-100 relative transform transition-all">
  <div class="px-4 sm:px-6 py-4 rounded-t-2xl" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
    <h1 class="text-white font-bold text-lg sm:text-xl text-center uppercase tracking-wide">General Complaint Form</h1>
  </div>
  <div class="px-4 sm:px-6 py-4 flex-1 overflow-visible">
    <form id="complaintForm" class="space-y-3 sm:space-y-4" data-store-url="{{ route('user.complaints.store') }}">
      @csrf
      <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
        <label class="w-full sm:w-40 shrink-0 text-left text-xs sm:text-sm font-semibold text-gray-700 leading-tight">Incident Date</label>
        <input type="date" name="incident_date" class="w-full sm:flex-1 bg-gray-50 border-2 border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-xs sm:text-sm text-gray-500 valid:text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" required>
      </div>
      <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
        <label class="w-full sm:w-40 shrink-0 text-left text-xs sm:text-sm font-semibold text-gray-700 leading-tight">Incident Time</label>
        <input type="time" name="incident_time" class="w-full sm:flex-1 bg-gray-50 border-2 border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-xs sm:text-sm text-gray-500 valid:text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" required>
      </div>
      <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
        <label class="w-full sm:w-40 shrink-0 text-left text-xs sm:text-sm font-semibold text-gray-700 leading-tight">Defendant's Name</label>
        <input type="text" name="defendant_name" class="w-full sm:flex-1 bg-gray-50 border-2 border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-xs sm:text-sm placeholder:text-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Enter full name" required>
      </div>
      <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
        <label class="w-full sm:w-40 shrink-0 text-left text-xs sm:text-sm font-semibold text-gray-700 leading-tight">Defendant's Address</label>
        <input type="text" name="defendant_address" class="w-full sm:flex-1 bg-gray-50 border-2 border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-xs sm:text-sm placeholder:text-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Enter complete address" required>
      </div>
      <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
        <label class="w-full sm:w-40 shrink-0 text-left text-xs sm:text-sm font-semibold text-gray-700 leading-tight">Level of Urgency</label>
        <div class="w-full sm:flex-1 relative">
          <select id="levelOfUrgency" name="level_urgency" class="w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-xs sm:text-sm text-gray-500 valid:text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all cursor-pointer appearance-none" style="appearance: none; -webkit-appearance: none; -moz-appearance: none; background-image: none;" required>
            <option value="" disabled selected hidden>Select urgency level</option>
            <option value="Low">Low (Non-urgent)</option>
            <option value="Medium">Medium (Normal)</option>
            <option value="High">High (Urgent)</option>
          </select>
          <svg class="w-4 h-4 text-gray-400 absolute right-3 sm:right-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
          </svg>
        </div>
      </div>
      <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
        <label class="w-full sm:w-40 shrink-0 text-left text-xs sm:text-sm font-semibold text-gray-700 leading-tight">Complaint Type</label>
        <div id="complaintTypeWrapper" class="w-full sm:flex-1 relative">
          <input type="hidden" id="complaintTypeInput" name="description" required>
          <button type="button" id="complaintTypeButton" onclick="toggleComplaintTypeMenu()" class="w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all cursor-pointer flex items-center justify-between">
            <span id="complaintTypeLabel" class="text-gray-500">Select complaint type</span>
            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div id="complaintTypeMenu" class="hidden absolute z-50 mt-2 w-full bg-white border border-gray-200 rounded-lg shadow-lg max-h-56 overflow-y-auto">
            <button type="button" class="w-full text-left px-3 sm:px-4 py-2 text-xs sm:text-sm hover:bg-blue-50" onclick="selectComplaintType('Community Issues','Community Issues (loud noise, garbage dumping, etc.)')">Community Issues<br>(loud noise, garbage dumping, etc.)</button>
            <button type="button" class="w-full text-left px-3 sm:px-4 py-2 text-xs sm:text-sm hover:bg-blue-50" onclick="selectComplaintType('Physical Harassment','Physical Harassment (physical assault, threats, etc.)')">Physical Harassment<br>(physical assault, threats, etc.)</button>
            <button type="button" class="w-full text-left px-3 sm:px-4 py-2 text-xs sm:text-sm hover:bg-blue-50" onclick="selectComplaintType('Neighbor Dispute','Neighbor Dispute (boundary issue, parking conflict, etc.)')">Neighbor Dispute<br>(boundary issue, parking conflict, etc.)</button>
            <button type="button" class="w-full text-left px-3 sm:px-4 py-2 text-xs sm:text-sm hover:bg-blue-50" onclick="selectComplaintType('Money Problems','Money Problems (unpaid debt, borrowed money, etc.)')">Money Problems<br>(unpaid debt, borrowed money, etc.)</button>
            <button type="button" class="w-full text-left px-3 sm:px-4 py-2 text-xs sm:text-sm hover:bg-blue-50" onclick="selectComplaintType('Misbehavior','Misbehavior (public intoxication, trespassing, etc.)')">Misbehavior<br>(public intoxication, trespassing, etc.)</button>
            <button type="button" class="w-full text-left px-3 sm:px-4 py-2 text-xs sm:text-sm hover:bg-blue-50" onclick="selectComplaintType('Others','Others (please specify)')">Others (please specify)</button>
          </div>
        </div>
      </div>
      <div id="specifyField" class="hidden">
        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
          <label class="w-full sm:w-40 shrink-0 text-left text-xs sm:text-sm font-semibold text-gray-700 leading-tight">Complaint Details</label>
          <input type="text" id="specifyInput" name="specifyInput" class="w-full sm:flex-1 bg-gray-50 border-2 border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-xs sm:text-sm placeholder:text-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all pointer-events-auto" placeholder="Specify the nature of your complaint" autocomplete="off">
        </div>
      </div>
      <div class="flex flex-col sm:flex-row sm:items-start gap-2 sm:gap-4">
        <label class="w-full sm:w-40 shrink-0 text-left text-xs sm:text-sm font-semibold text-gray-700 leading-tight pt-2">Complaint Statement</label>
        <textarea name="complaint_statement" class="w-full sm:flex-1 bg-gray-50 border-2 border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-xs sm:text-sm placeholder:text-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all resize-none h-24 sm:h-28" placeholder="Provide a detailed description of the incident" required></textarea>
      </div>
      <div class="flex items-start pt-2 relative">
        <input id="complaintCheckbox" type="checkbox" required class="mt-1 mr-3 w-4 h-4 text-blue-600 border-2 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer flex-shrink-0">
        <label for="complaintCheckbox" class="text-[14px] text-gray-600 leading-relaxed text-left">I certify that the information provided above is accurate and complete to the best of my knowledge.</label>
        <span id="checkboxTooltip" class="absolute left-0 top-full mt-2 hidden w-full max-w-xs bg-amber-50 border-l-4 border-amber-500 px-4 py-3 rounded-lg text-sm text-amber-800 flex items-start gap-3 shadow-md" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <div>Please check this box if you want to proceed.</div>
        </span>
      </div>
      
      {{-- Validation Error Area --}}
      <div id="validationErrors" class="text-red-500 text-xs hidden"></div>



    </form>
  </div>
<div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 px-4 sm:px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
  <button type="button" onclick="closeModal('modalGeneralComplaint')" 
          class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-red-500 hover:to-red-600 text-gray-700 hover:text-white text-xs sm:text-sm font-semibold rounded-lg transition-all duration-200 border border-gray-300 order-2 sm:order-1">CANCEL</button>
  <button type="button" id="submitComplaintBtn" onclick="submitComplaintForm()" 
          class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-blue-500 hover:to-blue-600 text-gray-700 hover:text-white text-xs sm:text-sm font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg border border-gray-300 order-1 sm:order-2">SUBMIT</button>
  </div>
  </div>
  </div>
  </div>
</div>

<div id="successModal" class="modal-container hidden fixed top-[80px] left-0 w-full h-[calc(100vh-80px)] flex items-center justify-center z-[9999] p-4 sm:p-0">
<div class="bg-white w-full sm:w-[480px] rounded-2xl shadow-2xl p-6 sm:p-10 relative z-[9999] text-center border-2 border-gray-100">
  <div class="flex justify-center mb-4 sm:mb-6">
    <div class="w-16 h-16 sm:w-24 sm:h-24 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-lg">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 sm:w-12 sm:h-12 text-white" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
      </svg>
    </div>
  </div>
  <h2 class="font-extrabold text-lg sm:text-2xl mb-2 bg-gradient-to-r from-emerald-600 to-emerald-500 bg-clip-text text-transparent tracking-wide">
    COMPLAINT SUBMITTED SUCCESSFULLY!
  </h2>
  <p id="successMessageContent" class="text-gray-600 text-xs sm:text-sm leading-relaxed mb-4 sm:mb-6">
    Your complaint has been filed and will be reviewed shortly. Barangay officials will get back to you as soon as possible. Expect an initial response within 24-48 hours.
  </p>
  <button onclick="closeSuccessModal()" 
          class="w-full sm:w-auto bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white px-6 sm:px-8 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
    CLOSE
  </button>
</div>
</div>

{{-- Shared Backdrop for All Modals --}}
<div id="modal-backdrop" class="hidden fixed top-[80px] left-0 w-full h-[calc(100vh-80px)] bg-black/40 backdrop-blur-sm z-[9998]"></div>

@endsection

@push('scripts')
<script src="{{ asset('js/user-complaints.js') }}" defer></script>
@endpush