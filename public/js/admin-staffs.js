// Staff management page specific functions

function viewStaff(button) {
    const lastName = button?.dataset?.lastName || 'N/A';
    const firstName = button?.dataset?.firstName || 'N/A';
    const username = button?.dataset?.username || 'N/A';
    const password = button?.dataset?.password || '';
    const passwordHash = button?.dataset?.passwordHash || '';
    const dateCreated = button?.dataset?.dateCreated || 'N/A';

    // Populate the view modal with staff data
    const viewLastName = document.getElementById('viewLastName');
    const viewFirstName = document.getElementById('viewFirstName');
    const viewUsername = document.getElementById('viewUsername');
    const viewPassword = document.getElementById('viewPassword');
    const viewDateCreated = document.getElementById('viewDateCreated');
    
    if (viewLastName) viewLastName.value = lastName;
    if (viewFirstName) viewFirstName.value = firstName;
    if (viewUsername) viewUsername.value = username;
    if (viewPassword) viewPassword.value = password || passwordHash || '••••••••';
    if (viewDateCreated) viewDateCreated.value = dateCreated;
    
    // Open the modal
    openModal('viewStaffModal');
}

function confirmDelete() {
    // TODO: Hook up actual delete if needed; for now just close.
    closeModal('deleteModal');
}

function prepareDeactivate(staffId) {
    const form = document.getElementById('deactivateForm');
    if (form) {
        form.action = `/admin/staffs/${staffId}/deactivate`;
    }
    openModal('deleteModal');
}

function editStaff(id, lastName, firstName, username, dateCreated) {
    // Populate edit form fields with the selected staff's data
    const editLastName = document.getElementById('editLastName');
    const editFirstName = document.getElementById('editFirstName');
    const editUsername = document.getElementById('editUsername');
    const editPassword = document.getElementById('editPassword');
    const editPasswordConfirm = document.getElementById('editPasswordConfirm');
    const editDateCreated = document.getElementById('editDateCreated');
    
    if (editLastName) editLastName.value = lastName || '';
    if (editFirstName) editFirstName.value = firstName || '';
    if (editUsername) editUsername.value = username || '';
    if (editPassword) editPassword.value = '';
    if (editPasswordConfirm) editPasswordConfirm.value = '';
    if (editDateCreated) editDateCreated.value = dateCreated || '';

    // Point the form to the staff update route
    const form = document.getElementById('editStaffForm');
    if (form) {
        form.action = `/admin/staffs/${id}`;
    }

    openModal('editStaffModal');
}

function togglePasswordVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    const eyeOpen = document.getElementById(fieldId + '-eye-open');
    const eyeClosed = document.getElementById(fieldId + '-eye-closed');

    if (!field) return;

    if (field.type === 'password') {
        field.type = 'text';
        if (eyeOpen) eyeOpen.classList.add('hidden');
        if (eyeClosed) eyeClosed.classList.remove('hidden');
    } else {
        field.type = 'password';
        if (eyeOpen) eyeOpen.classList.remove('hidden');
        if (eyeClosed) eyeClosed.classList.add('hidden');
    }
}
