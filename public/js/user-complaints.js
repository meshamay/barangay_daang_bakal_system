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
    const specify = document.getElementById('specifyField');
    if (specify && select) {
        specify.classList.toggle('hidden', select.value !== 'Others');
    }
}

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
    
    if (!form || !submitButton || !errorsDiv) return;
    
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
                    Transaction ID: <strong>${trackingId}</strong><br>
                    Your complaint has been filed and will be reviewed shortly.
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
