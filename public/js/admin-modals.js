// Admin modal backdrop and modal management
const backdrop = document.getElementById('modal-backdrop');

function showBackdrop() {
    if (backdrop) backdrop.classList.remove('hidden');
}

function hideBackdrop() {
    if (backdrop) backdrop.classList.add('hidden');
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        showBackdrop();
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        hideBackdrop();
    }
}

// Sets the action URL for the modal form dynamically based on the user ID
function setModalAction(actionUrl, modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        const form = modal.querySelector('form');
        if (form) {
            form.action = actionUrl;
        }
        modal.classList.remove('hidden');
        showBackdrop();
    }
}
