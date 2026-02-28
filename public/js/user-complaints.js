// User complaints page functions

// Move backdrop and modal to body on load
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
    const modal = document.getElementById(id);
    if (modal) modal.classList.remove('hidden');
    showBackdrop();
    document.body.classList.add('overflow-hidden');
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.add('hidden');
    hideBackdrop();
    document.body.classList.remove('overflow-hidden');
}

// Close on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === "Escape") {
        closeModal('modalGeneralComplaint');
        const successModal = document.getElementById('successModal');
        if(successModal && !successModal.classList.contains('hidden')) closeSuccessModal();
    }
});

function closeSuccessModal() {
    const successModal = document.getElementById("successModal");
    if (successModal) successModal.classList.add("hidden");
    hideBackdrop();
    document.body.classList.remove('overflow-hidden');
    // Reload page to show the new complaint in the table
    window.location.reload();
}

function toggleSpecifyField() {
    const select = document.getElementById('description');
    const complaintTypeInput = document.getElementById('complaintTypeInput');
    const specify = document.getElementById('specifyField');
    const selectedValue = complaintTypeInput?.value || select?.value || '';
    if (specify) {
        specify.classList.toggle('hidden', selectedValue !== 'Other Concerns');
    }
}

function toggleComplaintTypeMenu() {
    const menu = document.getElementById('complaintTypeMenu');
    if (menu) menu.classList.toggle('hidden');
}

function selectComplaintType(value, label) {
    const input = document.getElementById('complaintTypeInput');
    const labelEl = document.getElementById('complaintTypeLabel');
    const menu = document.getElementById('complaintTypeMenu');
    if (input) input.value = value;
    if (labelEl) {
        labelEl.textContent = label;
        labelEl.classList.remove('text-gray-500');
        labelEl.classList.add('text-gray-700');
    }
    if (menu) menu.classList.add('hidden');
    toggleSpecifyField();
}

document.addEventListener('click', function(event) {
    const wrapper = document.getElementById('complaintTypeWrapper');
    const menu = document.getElementById('complaintTypeMenu');
    if (!wrapper || !menu) return;
    if (!wrapper.contains(event.target)) {
        menu.classList.add('hidden');
    }
});

function removePlaceholder() {
    const specifyInput = document.getElementById('specifyInput');
    if (specifyInput) specifyInput.placeholder = '';
}

function restorePlaceholder() {
    const specifyInput = document.getElementById('specifyInput');
    if (specifyInput) specifyInput.placeholder = 'Please specify...';
}

// AJAX SUBMISSION FUNCTION FOR COMPLAINTS
async function submitComplaintForm() {
    const form = document.getElementById('complaintForm');
    const submitButton = document.getElementById('submitComplaintBtn');
    const errorsDiv = document.getElementById('validationErrors');
    const complaintTypeInput = document.getElementById('complaintTypeInput');
    
    if (!form || !submitButton || !errorsDiv) return;
    
    const checkbox = form.querySelector('input[type="checkbox"][required]');
    const tooltip = form.querySelector('.checkboxTooltip');
    if (checkbox && !checkbox.checked) {
        // show tooltip next to unchecked checkbox
        if (tooltip) {
            tooltip.classList.remove('hidden');
            // Hide after 3 seconds if not checked
            setTimeout(() => {
                if (!checkbox.checked) tooltip.classList.add('hidden');
            }, 3000);
            // automatically hide when they click checkbox
            checkbox.addEventListener('change', () => tooltip.classList.add('hidden'), { once: true });
        }
        return;
    }

    if (complaintTypeInput && !complaintTypeInput.value) {
        alert("Please select a complaint type.");
        return;
    }

    const formData = new FormData(form);
    errorsDiv.classList.add('hidden');
    errorsDiv.innerHTML = '';
    
    submitButton.disabled = true;
    submitButton.textContent = 'SUBMITTING...';

    try {
        // Get the complaints store route URL from the page
        const storeUrl = form.dataset.storeUrl || '/user/complaints';
        
        const response = await fetch(storeUrl, {
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
            
            // Capture Transaction ID from server response
            const trackingId = data.tracking_number;
            
            // Update the success message content with the ID
            const successMessage = document.getElementById('successMessageContent');
            if (successMessage) {
                successMessage.innerHTML = `
                    <span class="block font-bold">Transaction ID: ${trackingId}</span>
                    <span class="block mt-5">Your complaint has been filed and will be reviewed shortly.</span>
                `;
            }
            
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
