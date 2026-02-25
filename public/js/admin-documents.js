// Document requests page specific functions

// Open View Modal and Populate Data
function openDocumentModal(modalId, button) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    // 1. Get Data from Button attributes
    const d = button.dataset;

    // 2. Helper function to find input by Label text inside this specific modal
    const setField = (labelText, value) => {
        // Find all labels in this modal
        const labels = modal.querySelectorAll('label');
        labels.forEach(label => {
            // Check if label contains the text (case insensitive, trimmed)
            if (label.textContent.trim().includes(labelText)) {
                // Find the input/textarea immediately following the label
                const input = label.nextElementSibling;
                
                // Safety check: ensure input exists and is an input/textarea field
                if (input && (input.tagName === 'INPUT' || input.tagName === 'TEXTAREA')) {
                    // Handle null/undefined values gracefully
                    input.value = (value && value !== 'undefined' && value !== 'null') ? value : '';
                }
            }
        });
    };

    // 3. Populate Fields
    setField('Last Name:', d.lname);
    setField('First Name:', d.fname);
    setField('Middle Name:', d.mname);
    setField('Suffix:', d.suffix);
    setField('Age:', d.age);
    setField('Date of Birth:', d.dob);
    setField('Place of Birth:', d.pob);
    setField('Gender:', d.gender);
    setField('Civil Status:', d.civil);
    setField('Citizenship:', d.citizenship);
    
    // --- Specific Data Fields ---
    // Length of Residency (from parent table)
    setField('Length of Residency:', d.residency);
    
    // Valid ID Number (from parent table)
    setField('Valid ID Number:', d.validIdNo);
    
    // Registered Voter (from parent table)
    setField('Registered Voter:', d.voter);
    
    // Purpose fields (Handles "Purpose of Request" and "Other Purpose" in Indigency)
    setField('Purpose of Request:', d.purpose);
    setField('Other Purpose:', d.otherPurpose);
    
    // Indigency specific fields
    setField('Certificate of being Indigent:', d.indigencyCategory);

    // 4. Update Images Logic (Switch between Icon and Photo)
    const defaultIcon = "https://cdn-icons-png.flaticon.com/512/685/685655.png";
    
    // Select the specific images using the classes we added to the modal HTML
    const frontImg = modal.querySelector('.js-id-front');
    const backImg = modal.querySelector('.js-id-back');
    const proofImg = modal.querySelector('.js-proof-img'); // For Indigency Modal

    // Helper to toggle image styles
    const updateImage = (imgElement, imageSrc) => {
        if (!imgElement) return;

        if (imageSrc && imageSrc !== '') {
            // Show real photo
            imgElement.src = imageSrc;
            imgElement.classList.remove('w-10', 'opacity-70', 'mb-2');
            imgElement.classList.add('w-full', 'h-40', 'px-2', 'object-contain');
        } else {
            // Show default icon
            imgElement.src = defaultIcon;
            imgElement.classList.add('w-10', 'opacity-70', 'mb-2');
            imgElement.classList.remove('w-full', 'h-40', 'px-2', 'object-contain');
        }
    };

    updateImage(frontImg, d.idFront);
    updateImage(backImg, d.idBack);
    updateImage(proofImg, d.proof); // Indigency proof

    // 5. Show Modal
    showBackdrop();
    modal.classList.remove('hidden');
}

// Open In Progress Modal
function openInprogressModal(requestId) {
    const modal = document.getElementById('inprogressModal');
    const form = document.getElementById('inprogressForm');
    const input = document.getElementById('inprogressRequestId');
    
    if (form) {
        form.action = "/admin/documents/" + requestId + "/status";
    }
    
    if (input) {
        input.value = requestId;
    }
    
    showBackdrop();
    if (modal) modal.classList.remove('hidden');
}

function closeInprogressModal() {
    const modal = document.getElementById('inprogressModal');
    if (modal) modal.classList.add('hidden');
    hideBackdrop();
}

// Open Completed Modal
function openCompletedModal(requestId) {
    const modal = document.getElementById('completedModal');
    const form = document.getElementById('completedForm');
    const input = document.getElementById('completedRequestId');
    
    if (form) {
        form.action = "/admin/documents/" + requestId + "/status";
    }
    
    if (input) {
        input.value = requestId;
    }
    
    showBackdrop();
    if (modal) modal.classList.remove('hidden');
}

function closeCompletedModal() {
    const modal = document.getElementById('completedModal');
    if (modal) modal.classList.add('hidden');
    hideBackdrop();
}
