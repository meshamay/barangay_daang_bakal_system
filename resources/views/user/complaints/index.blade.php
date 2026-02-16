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

<div class="bg-white rounded-2xl shadow-xl overflow-hidden border-2 border-gray-100 max-h-[calc(100vh-400px)] overflow-y-auto">
<div class="overflow-x-auto">
<table class="w-full text-xs sm:text-sm border-collapse min-w-max">
<thead style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);" class="text-white">
    <tr class="font-bold uppercase tracking-wide">
        <th class="px-3 sm:px-5 py-3 sm:py-4 text-center whitespace-nowrap">TRANSACTION ID</th>
        <th class="px-3 sm:px-5 py-3 sm:py-4 text-center whitespace-nowrap">LAST NAME</th>
        <th class="px-3 sm:px-5 py-3 sm:py-4 text-center whitespace-nowrap">FIRST NAME</th>
        <th class="px-3 sm:px-5 py-3 sm:py-4 text-center whitespace-nowrap">COMPLAINT TYPE</th>
        <th class="px-3 sm:px-5 py-3 sm:py-4 text-center whitespace-nowrap">DATE FILED</th>
        <th class="px-3 sm:px-5 py-3 sm:py-4 text-center whitespace-nowrap">DATE RESOLVED</th>
        <th class="px-3 sm:px-5 py-3 sm:py-4 text-center whitespace-nowrap">STATUS</th>
      </tr>
    </thead>
    <tbody>
      {{-- Assuming $complaints is passed from the controller --}}
      @forelse ($complaints ?? [] as $complaint)
      <tr>
        <td class="px-3 sm:px-5 py-3 sm:py-4 text-center font-mono font-semibold text-blue-600 whitespace-nowrap">{{ $complaint->transaction_no ?? 'N/A' }}</td>
        {{-- Displaying current user's name as they are the filer --}}
        <td class="px-3 sm:px-5 py-3 sm:py-4 text-center whitespace-nowrap">{{ Auth::user()->last_name ?? 'N/A' }}</td>
        <td class="px-3 sm:px-5 py-3 sm:py-4 text-center whitespace-nowrap">{{ Auth::user()->first_name ?? 'N/A' }}</td>
        <td class="px-3 sm:px-5 py-3 sm:py-4 text-center whitespace-nowrap">{{ $complaint->complaint_type ?? 'N/A' }}</td>
        <td class="px-3 sm:px-5 py-3 sm:py-4 text-center whitespace-nowrap">{{ $complaint->created_at->format('m/d/Y') }}</td>
        <td class="px-3 sm:px-5 py-3 sm:py-4 text-center whitespace-nowrap">{{ $complaint->date_completed ? \Carbon\Carbon::parse($complaint->date_completed)->format('m/d/Y') : '--/--/----' }}</td>
        <td class="px-3 sm:px-5 py-3 sm:py-4 text-center">
            @if($complaint->status == 'Pending')
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-amber-500/10 text-amber-700 border border-amber-200">Pending</span>
            @elseif($complaint->status == 'In Progress')
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-blue-500/10 text-blue-700 border border-blue-200">In Progress</span>
            @elseif($complaint->status == 'Completed')
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-700 border border-emerald-200">Completed</span>
            @else
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gray-500/10 text-gray-700 border border-gray-200">{{ $complaint->status ?? 'N/A' }}</span>
            @endif
        </td>
      </tr>
      @empty
      <tr>8 sm:py-12 text-gray-500">
              <div class="flex flex-col items-center">
                  <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-300 mb-2 sm:mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  <p class="font-semibold text-sm">No complaints filed yet</p>
                  <p class="text-xs mt-1">Click "File a Complaint" to get started.</p>
              </div>
          </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>
</div>

<div class="flex justify-end mt-4 sm:mt-6 px-0">
  <a href="{{ route('home') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold transition-all duration-200 border border-gray-300 hover:shadow-md">
    ← Back
  </a>
</div>


{{-- Shared Backdrop for All Modals --}}
<div id="modal-backdrop" class="hidden"></div>

{{-- ======================================================================== --}}
{{-- USER COMPLAINT MODAL (WITH NAME ATTRIBUTES AND FORM ID) --}}
{{-- ======================================================================== --}}
<div id="modalGeneralComplaint" class="modal-container hidden fixed inset-0 z-[70] overflow-hidden p-4 sm:p-0" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  <!-- Modal Panel -->
  <div class="flex min-h-full items-center justify-center text-center z-50 relative pointer-events-none">
    <div class="bg-white w-full sm:w-[600px] max-h-[90vh] sm:max-h-none overflow-y-auto sm:overflow-hidden rounded-2xl flex flex-col pointer-events-auto shadow-2xl border-2 border-gray-100 relative transform transition-all">
  <div class="px-4 sm:px-6 py-4 rounded-t-2xl" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
    <h1 class="text-white font-bold text-lg sm:text-xl text-center uppercase tracking-wide">General Complaint Form</h1>
  </div>
  <div class="px-4 sm:px-6 py-4 flex-1 overflow-y-auto">
    <form id="complaintForm" class="space-y-3 sm:space-y-4">
      @csrf
      <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
        <label class="w-full sm:w-40 shrink-0 text-left text-xs sm:text-sm font-semibold text-gray-700 leading-tight">Incident Date</label>
        <input type="date" name="incident_date" class="w-full sm:flex-1 bg-gray-50 border-2 border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" required>
      </div>
      <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
        <label class="w-full sm:w-40 shrink-0 text-left text-xs sm:text-sm font-semibold text-gray-700 leading-tight">Incident Time</label>
        <input type="time" name="incident_time" class="w-full sm:flex-1 bg-gray-50 border-2 border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" required>
      </div>
      <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
        <label class="w-full sm:w-40 shrink-0 text-left text-xs sm:text-sm font-semibold text-gray-700 leading-tight">Defendant's Name</label>
        <input type="text" name="defendant_name" class="w-full sm:flex-1 bg-gray-50 border-2 border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Full name" required>
      </div>
      <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
        <label class="w-full sm:w-40 shrink-0 text-left text-xs sm:text-sm font-semibold text-gray-700 leading-tight">Defendant's Address</label>
        <input type="text" name="defendant_address" class="w-full sm:flex-1 bg-gray-50 border-2 border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Complete address" required>
      </div>
      <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
        <label class="w-full sm:w-40 shrink-0 text-left text-xs sm:text-sm font-semibold text-gray-700 leading-tight">Level of Urgency</label>
        <select id="levelOfUrgency" name="level_urgency" class="w-full sm:flex-1 bg-gray-50 border-2 border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all cursor-pointer appearance-none" required>
          <option value="" disabled selected hidden>Select urgency level</option>
          <option value="Low">Low (Non-urgent)</option>
          <option value="Medium">Medium (Normal)</option>
          <option value="High">High (Urgent)</option>
        </select>
      </div>
      <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
        <label for="description" class="w-full sm:w-40 shrink-0 text-left text-xs sm:text-sm font-semibold text-gray-700 leading-tight">Type of Complaint</label>
        <select id="description" name="description" class="w-full sm:flex-1 bg-gray-50 border-2 border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all cursor-pointer appearance-none" onchange="toggleSpecifyField()" required>
          <option value="" disabled selected hidden>Select complaint type</option>
          <option value="Community Issues">Community Issues (noise, garbage, vandalism)</option>
          <option value="Physical Harrasments">Physical Harassment (unwanted touching, punching)</option>
          <option value="Neighbor Dispute">Neighbor Disputes (arguments, property damage)</option>
          <option value="Money Problems">Money Problems (unpaid debts, loan disputes)</option>
          <option value="Misbehavior">Misbehavior (insults, bullying, shouting)</option>
          <option value="Others">Others (please specify)</option>
        </select>
      </div>
      <div id="specifyField" class="hidden">
        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
          <label class="w-full sm:w-40 shrink-0 text-left text-xs sm:text-sm font-semibold text-gray-700 leading-tight">Specify Complaint</label>
          <input type="text" id="specifyInput" name="specifyInput" class="w-full sm:flex-1 bg-gray-50 border-2 border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all pointer-events-auto" placeholder="Please specify your complaint..." autocomplete="off">
        </div>
      </div>
      <div class="flex flex-col sm:flex-row sm:items-start gap-2 sm:gap-4">
        <label class="w-full sm:w-40 shrink-0 text-left text-xs sm:text-sm font-semibold text-gray-700 leading-tight pt-2">Complaint Statement</label>
        <textarea name="complaint_statement" class="w-full sm:flex-1 bg-gray-50 border-2 border-gray-300 rounded-lg px-3 sm:px-4 py-2 text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all resize-none h-24 sm:h-28" placeholder="Describe your complaint in detail..." required></textarea>
      </div>
      <div class="flex items-start pt-2">
        <input type="checkbox" required class="mt-1 mr-3 w-4 h-4 text-blue-600 border-2 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer flex-shrink-0">
        <label class="text-xs text-gray-600 leading-relaxed">I certify that the information provided above is accurate and complete to the best of my knowledge.</label>
      </div>
      
      {{-- Validation Error Area --}}
      <div id="validationErrors" class="text-red-500 text-xs hidden"></div>

    </form>
  </div>
<div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 px-4 sm:px-6 py-4 border-t border-gray-100 bg-gray-50 mt-4">
  <button type="button" onclick="closeModal('modalGeneralComplaint')" 
          class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-gradient-to-r from-gray-100 to-gray-200 hover:from-gray-200 hover:to-gray-300 text-gray-700 text-xs sm:text-sm font-semibold rounded-lg transition-all duration-200 border border-gray-300 order-2 sm:order-1">CANCEL</button>
  <button type="button" id="submitComplaintBtn" onclick="submitComplaintForm()" 
          class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white text-xs sm:text-sm font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg order-1 sm:order-2">SUBMIT</button>
  </div>
  </div>
  </div>
  </div>
</div>

<div id="successModal" class="modal-container hidden fixed inset-0 flex items-center justify-center z-[80] p-4 sm:p-0">
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
  <p id="successMessageContent" class="text-gray-600 text-xs sm:text-sm leading-relaxed mb-4 sm:mb-6">
    Your complaint has been filed and will be reviewed shortly. Barangay officials will get back to you as soon as possible. Expect an initial response within 24-48 hours.
  </p>
  <button onclick="closeSuccessModal()" 
          class="w-full sm:w-auto bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white px-6 sm:px-8 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
    CLOSE
  </button>
</div>
</div>

<script>
const backdrop = document.getElementById('modal-backdrop');
if (backdrop && backdrop.parentElement !== document.body) {
    document.body.appendChild(backdrop);
}

function showBackdrop() {
    if (backdrop) backdrop.classList.remove('hidden');
}

function hideBackdrop() {
    if (backdrop) backdrop.classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function () {
    // Move success modal to body to ensure it covers the header
    const successModal = document.getElementById('successModal');
    if (successModal) document.body.appendChild(successModal);
});

function openModal(id) {
  document.getElementById(id).classList.remove('hidden');
  showBackdrop();
  document.body.classList.add('overflow-hidden');
}
function closeModal(id) {
  document.getElementById(id).classList.add('hidden');
  hideBackdrop();
  document.body.classList.remove('overflow-hidden');
}

// Close on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === "Escape") {
        closeModal('modalGeneralComplaint');
        if(!document.getElementById('successModal').classList.contains('hidden')) closeSuccessModal();
    }
});

function closeSuccessModal() {
  document.getElementById("successModal").classList.add("hidden");
  hideBackdrop();
  document.body.classList.remove('overflow-hidden');
  // Reload page to show the new complaint in the table
  window.location.reload(); 
}

function toggleSpecifyField() {
  const select = document.getElementById('description');
  const specify = document.getElementById('specifyField');
  specify.classList.toggle('hidden', select.value !== 'Others');
}
function removePlaceholder() {
  document.getElementById('specifyInput').placeholder = '';
}
function restorePlaceholder() {
  document.getElementById('specifyInput').placeholder = 'Please specify...';
}

// 🚀 NEW AJAX SUBMISSION FUNCTION FOR COMPLAINTS
async function submitComplaintForm() {
    const form = document.getElementById('complaintForm');
    const submitButton = document.getElementById('submitComplaintBtn');
    const errorsDiv = document.getElementById('validationErrors');
    
    const checkbox = form.querySelector('input[type="checkbox"][required]');
    if (checkbox && !checkbox.checked) {
        alert("Please confirm the accuracy of the information by checking the box.");
        return;
    }

    const formData = new FormData(form);
    errorsDiv.classList.add('hidden');
    errorsDiv.innerHTML = '';
    
    submitButton.disabled = true;
    submitButton.textContent = 'SUBMITTING...';

    try {
        // Use the complaints store route
        const response = await fetch("{{ route('user.complaints.store') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
            },
        });

        let data;
        try {
            data = await response.json();
        } catch (e) {
            console.error('Failed to parse JSON response:', e);
            alert('Server error: Invalid response format. Check console for details.');
            return;
        }

        if (response.status === 422) { // Validation Error
            let messages = '';
            for (const key in data.errors) {
                messages += `• ${data.errors[key][0]}<br>`;
            }
            errorsDiv.innerHTML = messages;
            errorsDiv.classList.remove('hidden');

        } else if (response.ok) { // Success (200, 201)
            
            // 🚀 FIX: Capture Transaction ID from server response
            const trackingId = data.tracking_number;
            
            // Update the success message content with the ID
            const successMessage = document.getElementById('successMessageContent');
            successMessage.innerHTML = `
                Transaction ID: <strong>${trackingId}</strong><br>
                Your complaint has been filed and will be reviewed shortly.
            `;
            
            closeModal('modalGeneralComplaint');
            showBackdrop();
            openModal('successModal');
            form.reset(); 

        } else {
            alert('An unexpected server error occurred: ' + (data.details || data.message || 'Check console.'));
        }
    } catch (error) {
        console.error('Complaint submission error:', error);
        alert('A network or critical server error occurred: ' + error.message);
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = 'SUBMIT';
    }
}
</script>

@endsection