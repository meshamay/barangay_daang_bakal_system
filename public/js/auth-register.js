// Register page functions

// Function to open Privacy Policy as a popup window
function openPrivacyPolicy() {
    window.open('/privacy-policy', 'PrivacyPolicy', 'width=900,height=700,scrollbars=yes,resizable=yes');
}

document.addEventListener('DOMContentLoaded', function () {
    const registrationContainer = document.getElementById('registration-container');
    const successPage = document.getElementById('success-page');
    const registrationForm = document.getElementById('registration-form');
    const step1 = document.getElementById('step-1');
    const step2 = document.getElementById('step-2');

    // --- Image Preview Handler ---
    function createImagePreviewHandler(inputId, labelId) {
        const input = document.getElementById(inputId);
        const label = document.getElementById(labelId);
        if (!input || !label) return;

        input.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                label.innerHTML = `<img src="${e.target.result}" class="max-h-full max-w-full rounded-lg object-contain">`;
                label.className = 'relative flex h-[220px] w-full items-center justify-center overflow-hidden rounded-lg border-4 border-blue-300 transition bg-blue-50';
            };
            reader.readAsDataURL(file);
        });
    }

    // --- Field Validation ---
    function validateFields(fieldIds) {
        for (const id of fieldIds) {
            const field = document.getElementById(id);
            if (!field) continue;
            if (!field.checkValidity()) {
                field.reportValidity();
                return false;
            }
        }
        return true;
    }

    // Initialize Image Previews
    createImagePreviewHandler('photo-upload', 'photo-upload-label');
    createImagePreviewHandler('id-front-upload', 'id-front-label');
    createImagePreviewHandler('id-back-upload', 'id-back-label');

    // --- Step 1 Navigation ---
    const nextButton = document.getElementById('next-button');
    if (nextButton) {
        nextButton.addEventListener('click', () => {
            const step1Fields = ['last_name', 'first_name', 'photo-upload', 'gender', 'age', 'civil_status', 'dob', 'place_of_birth'];
            if (validateFields(step1Fields)) {
                if (step1) step1.classList.add('hidden');
                if (step2) step2.classList.remove('hidden');
            }
        });
    }

    // --- Back Navigation ---
    const backButton = document.getElementById('back-button');
    if (backButton) {
        backButton.addEventListener('click', () => {
            if (step2) step2.classList.add('hidden');
            if (step1) step1.classList.remove('hidden');
        });
    }

    // --- FORM SUBMISSION ---
    if (registrationForm) {
        registrationForm.addEventListener('submit', function (event) {
            event.preventDefault(); // Stop page reload

            // Validate Step 2 Fields
            const step2Fields = ['contact_number', 'id-front-upload', 'id-back-upload', 'address', 'username', 'password', 'password_confirmation', 'agree'];
            if (!validateFields(step2Fields)) {
                return;
            }

            // 1. Prepare Data
            const formData = new FormData(this);
            const submitBtn = document.getElementById('submit-btn');
            const originalText = submitBtn ? submitBtn.innerText : 'REGISTER';

            // 2. Show Loading State
            if (submitBtn) {
                submitBtn.innerText = "PROCESSING...";
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }

            // 3. Get CSRF token from meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // 4. Send to Laravel Backend
            fetch('/register/submit', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // SUCCESS: Show the modal
                    if (registrationContainer) registrationContainer.classList.add('hidden');
                    if (successPage) successPage.classList.remove('hidden');
                } else {
                    // ERROR: Show validation messages
                    let errorMessage = 'Registration failed:\n';
                    if (data.errors) {
                        for (const [key, messages] of Object.entries(data.errors)) {
                            errorMessage += `• ${messages[0]}\n`;
                        }
                    } else {
                        errorMessage += data.message || 'Unknown error';
                    }
                    alert(errorMessage);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('A system error occurred. Please check your internet connection or try again later.');
            })
            .finally(() => {
                // Reset Button
                if (submitBtn) {
                    submitBtn.innerText = originalText;
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            });
        });
    }

    // --- Numeric Input Restrictions ---
    ['age', 'contact_number'].forEach(id => {
        const inputElement = document.getElementById(id);
        if (inputElement) {
            inputElement.addEventListener('input', (e) => {
                e.target.value = e.target.value.replace(/\D/g, '');
            });
        }
    });

    // --- Password Toggle for Password Field ---
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');

    if (togglePassword) {
        togglePassword.addEventListener('click', (e) => {
            e.preventDefault();
            if (passwordInput) {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
            }
            if (eyeOpen) eyeOpen.classList.toggle('hidden');
            if (eyeClosed) eyeClosed.classList.toggle('hidden');
        });
    }

    // --- Password Toggle for Confirm Password Field ---
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const eyeOpenConfirm = document.getElementById('eyeOpenConfirm');
    const eyeClosedConfirm = document.getElementById('eyeClosedConfirm');

    if (togglePasswordConfirm) {
        togglePasswordConfirm.addEventListener('click', (e) => {
            e.preventDefault();
            if (passwordConfirmInput) {
                const type = passwordConfirmInput.type === 'password' ? 'text' : 'password';
                passwordConfirmInput.type = type;
            }
            if (eyeOpenConfirm) eyeOpenConfirm.classList.toggle('hidden');
            if (eyeClosedConfirm) eyeClosedConfirm.classList.toggle('hidden');
        });
    }
});
