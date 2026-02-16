// Complaints page specific functions

// STATUS MODAL FUNCTIONS
function openStatusModal(modalId, complaintId) {
    const modal = document.getElementById(modalId);
    const form = document.getElementById(modalId === 'inprogressModal' ? 'inprogressForm' : 'completedForm');
    
    if (form) {
        // Construct the URL - get base URL from current page
        const baseUrl = window.location.origin + '/admin/complaints';
        form.action = baseUrl + "/" + complaintId;
    }

    if (!modal) {
        console.warn('Status modal not found:', modalId);
        return;
    }

    modal.classList.remove('hidden');
    showBackdrop();
}

function closeStatusModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.add('hidden');
    hideBackdrop();
}
