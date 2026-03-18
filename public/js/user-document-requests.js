// User document requests page functions

const backdrop = document.getElementById('modal-backdrop');
if (backdrop && backdrop.parentElement !== document.body) {
    document.body.appendChild(backdrop); // ensure fixed overlay anchors to viewport
}

function showBackdrop() {
    if (backdrop) backdrop.classList.remove('hidden');
}

function hideBackdrop() {
    if (backdrop) backdrop.classList.add('hidden');
}

function lockBackgroundScroll() {
    document.documentElement.classList.add('modal-open');
    document.body.classList.add('modal-open');
}

function unlockBackgroundScroll() {
    document.documentElement.classList.remove('modal-open');
    document.body.classList.remove('modal-open');
}

function hasVisibleModals() {
    return Array.from(document.querySelectorAll('.modal-container')).some(
        modal => !modal.classList.contains('hidden')
    );
}

function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.remove('hidden');
    showBackdrop();
    lockBackgroundScroll();
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.add('hidden');
    if (!hasVisibleModals()) {
        hideBackdrop();
        unlockBackgroundScroll();
    }
}

function closeSuccessModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.remove();
    if (!hasVisibleModals()) {
        hideBackdrop();
        unlockBackgroundScroll();
    }
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

    const mobileCards = Array.from(document.querySelectorAll('.mobile-request-item'));
    const mobileLoadMoreButton = document.getElementById('mobile-requests-load-more');
    const mobileBatchSize = 10;
    let mobileVisibleCount = mobileBatchSize;

    function renderMobileCards() {
        if (!mobileCards.length || !mobileLoadMoreButton) return;

        mobileCards.forEach((card, index) => {
            card.classList.toggle('hidden', index >= mobileVisibleCount);
        });

        if (mobileVisibleCount >= mobileCards.length) {
            mobileLoadMoreButton.classList.add('hidden');
        } else {
            mobileLoadMoreButton.classList.remove('hidden');
        }
    }

    if (mobileCards.length && mobileLoadMoreButton && window.innerWidth < 640) {
        renderMobileCards();
        mobileLoadMoreButton.addEventListener('click', function () {
            mobileVisibleCount += mobileBatchSize;
            renderMobileCards();
        });
    }

    const indigencyProofInput = document.getElementById('indigency_proof_file');
    const indigencyProofName = document.getElementById('indigency_proof_filename');
    if (indigencyProofInput && indigencyProofName) {
        indigencyProofInput.addEventListener('change', function () {
            const selectedFile = this.files && this.files[0] ? this.files[0].name : 'No image selected';
            indigencyProofName.textContent = selectedFile;
        });
    }

    // Move success modals to body to ensure they cover the header
    ['sessionSuccessModal', 'ajaxSuccessModal'].forEach(id => {
        const el = document.getElementById(id);
        if (el) document.body.appendChild(el);
    });

    const sessionSuccessModal = document.getElementById('sessionSuccessModal');
    if (sessionSuccessModal && !sessionSuccessModal.classList.contains('hidden')) {
        showBackdrop();
        lockBackgroundScroll();
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
    
    // Check if checkbox is unchecked and show tooltip
    const checkbox = form.querySelector('input[type="checkbox"][required]');
    const tooltip = form.querySelector('.checkboxTooltip');
    if (checkbox && !checkbox.checked) {
        if (tooltip) {
            tooltip.classList.remove('hidden');
            // Auto-hide when user checks the box
            checkbox.addEventListener('change', () => tooltip.classList.add('hidden'), { once: true });
        }
        return;
    }
    
    if (submitButton) {
        submitButton.disabled = true;
        submitButton.textContent = 'SUBMITTING...';
    }

    try {
        // Get store URL from form data attribute or use default
        const storeUrl = form.dataset.storeUrl || '/user/document-requests';
        
        const response = await fetch(storeUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
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
            if (successMessage && data.tracking_number) {
                successMessage.innerHTML = `
                    <span class="block font-bold">Transaction ID: ${data.tracking_number}</span>
                    <span class="block">You may claim your document at the barangay once it's ready for release.</span>
                `;
            }
            showBackdrop();
            lockBackgroundScroll();
            if (successModal) successModal.classList.remove('hidden');
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
