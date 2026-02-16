// Reports page specific functions

// Logic to switch modals
function submitExport() {
    const form = document.getElementById('exportForm');
    if (!form) return;

    const checkedSections = form.querySelectorAll('input[name="sections[]"]:checked');
    if (checkedSections.length === 0) {
        alert('Please select at least one section to export.');
        return;
    }

    form.submit();
}

// Close modal if user clicks outside
window.onclick = function(event) {
    const exportModal = document.getElementById('exportModal');
    const successModal = document.getElementById('exportSuccessModal');
   
    if (event.target == exportModal) {
        closeModal('exportModal');
    }
    if (event.target == successModal) {
        closeModal('exportSuccessModal');
    }
}
