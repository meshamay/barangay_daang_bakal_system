// Announcements page specific functions

function openEditModal(button) {
    const id = button.dataset.id;
    const title = button.dataset.title;
    const content = button.dataset.content;
    const start = button.dataset.start;
    const end = button.dataset.end;

    // Update Form Action URL
    const form = document.getElementById('editAnnouncementForm');
    if (form) {
        // Get base URL from current page
        const baseUrl = window.location.origin + '/admin/announcements';
        form.action = baseUrl + '/' + id;
    }

    // Populate Fields
    const editTitle = document.getElementById('editTitle');
    const editContent = document.getElementById('editContent');
    const editStartDate = document.getElementById('editStartDate');
    const editEndDate = document.getElementById('editEndDate');
    
    if (editTitle) editTitle.value = title;
    if (editContent) editContent.value = content;
    if (editStartDate) editStartDate.value = start;
    if (editEndDate) editEndDate.value = end;

    openModal('editAnnouncementModal');
}

function openViewModal(button) {
    const viewTitle = document.getElementById('viewTitle');
    const viewContent = document.getElementById('viewContent');
    const viewStartDate = document.getElementById('viewStartDate');
    const viewEndDate = document.getElementById('viewEndDate');
    
    if (viewTitle) viewTitle.value = button.dataset.title;
    if (viewContent) viewContent.value = button.dataset.content;
    if (viewStartDate) viewStartDate.value = button.dataset.start;
    if (viewEndDate) viewEndDate.value = button.dataset.end;

    openModal('viewAnnouncementModal');
}
