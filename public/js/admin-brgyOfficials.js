// Barangay Officials page specific functions

function previewImage(event, previewImageId, iconPlaceholderId, thumbId = null) {
    const input = event.target;
    const reader = new FileReader();

    reader.onload = function() {
        const dataURL = reader.result;
        const preview = document.getElementById(previewImageId);
        const icon = document.getElementById(iconPlaceholderId);
        const thumb = thumbId ? document.getElementById(thumbId) : null;

        if (preview) {
            preview.src = dataURL;
            preview.classList.remove('hidden');
        }
        if (icon) {
            icon.classList.add('hidden');
        }
        if (thumb) {
            thumb.src = dataURL;
        }
    };

    if (input.files && input.files[0]) {
        reader.readAsDataURL(input.files[0]);
    }
}

function openViewOfficial(data) {
    const viewLastName = document.getElementById('viewLastName');
    const viewFirstName = document.getElementById('viewFirstName');
    const viewMiddleInitial = document.getElementById('viewMiddleInitial');
    const viewPosition = document.getElementById('viewPosition');
    
    if (viewLastName) viewLastName.value = data.last_name || '';
    if (viewFirstName) viewFirstName.value = data.first_name || '';
    if (viewMiddleInitial) viewMiddleInitial.value = data.middle_initial || '';
    if (viewPosition) viewPosition.value = data.position || '';

    const photo = document.getElementById('viewPhotoPreview');
    const thumb = document.getElementById('viewPhotoThumb');
    const fallback = 'https://via.placeholder.com/400x400.png?text=Add+Photo';
    const hasPhoto = data.photo && typeof data.photo === 'string' && data.photo.trim() !== '';
    
    if (photo) {
        photo.onerror = () => { photo.onerror = null; photo.src = fallback; };
        photo.src = hasPhoto ? data.photo : fallback;
    }
    
    if (thumb) {
        thumb.src = hasPhoto ? data.photo : 'https://via.placeholder.com/200x200.png?text=Preview';
    }

    openModal('viewOfficialModal');
}

function openEditOfficial(data) {
    const form = document.getElementById('editOfficialForm');
    if (form) {
        form.action = `/admin/brgy-officials/${data.id}`;
    }
    
    const editLastName = document.getElementById('editLastName');
    const editFirstName = document.getElementById('editFirstName');
    const editMiddleInitial = document.getElementById('editMiddleInitial');
    const editPosition = document.getElementById('editPosition');
    
    if (editLastName) editLastName.value = data.last_name || '';
    if (editFirstName) editFirstName.value = data.first_name || '';
    if (editMiddleInitial) editMiddleInitial.value = data.middle_initial || '';
    if (editPosition) editPosition.value = data.position || '';

    const photo = document.getElementById('editPhotoPreview');
    const icon = document.getElementById('editCameraIconPlaceholder');
    const thumb = document.getElementById('editPhotoThumb');
    const fallback = 'https://via.placeholder.com/400x400.png?text=Add+Photo';
    
    if (data.photo) {
        if (photo) {
            photo.src = data.photo;
            photo.classList.remove('hidden');
        }
        if (icon) icon.classList.add('hidden');
        if (thumb) thumb.src = data.photo;
    } else {
        if (photo) {
            photo.classList.add('hidden');
            photo.src = fallback;
        }
        if (icon) icon.classList.remove('hidden');
        if (thumb) thumb.src = 'https://via.placeholder.com/200x200.png?text=Preview';
    }

    openModal('editOfficialModal');
}

function openDeleteOfficial(id, name) {
    const form = document.getElementById('deleteOfficialForm');
    if (form) {
        form.action = `/admin/brgy-officials/${id}`;
    }
    openModal('deleteModal');
}
