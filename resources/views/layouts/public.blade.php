<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Daang Bakal</title>
    <link rel="icon" type="image/png" href="{{ asset('images/BARANGAY LOGO.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@400;600;700;800&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/animations.css') }}">
    <link rel="stylesheet" href="{{ asset('css/scrollbars.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modals.css') }}">


    <style>
        html {
            scroll-behavior: smooth;
        }

        .landing-page .font-barlow {
            font-family: 'Poppins', sans-serif;
        }
    </style>


    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>


    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                        barlow: ['Barlow Semi Condensed', 'sans-serif'],
                    },
                    colors: {
                        'brand-blue': '#4c6b8a', // Approximate color from your image
                        'brand-dark': '#2c4356',
                    }
                }
            }
        }
    </script>
</head>


<body class="bg-white font-sans text-gray-800 antialiased flex flex-col min-h-screen overflow-y-hidden">
    @yield('content')

    <!-- Privacy Policy Modal -->
    <div id="privacyPolicyModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm hidden">
        <div class="bg-gray-50 rounded-2xl shadow-2xl border border-blue-100 w-full max-w-2xl max-h-[90vh] overflow-y-auto p-6 relative">
            <button id="closePrivacyModal" class="absolute top-4 right-4 text-slate-500 hover:text-blue-600 text-xl font-bold">&times;</button>
            <!-- Privacy Policy Content Start -->
            @include('pages.privacy-policy-content')
            <!-- Privacy Policy Content End -->
            <div class="text-center mt-10 mb-8">
                <button id="agreeBtnModal" class="rounded-lg bg-blue-100 hover:bg-blue-200 px-8 py-3 text-base font-semibold text-gray-800 shadow-md transition-all duration-200">I Agree</button>
            </div>
        </div>
    </div>
    <script>
        // Track last step and input
        let lastStepId = null;
        let lastFocusedInputId = null;

        // Modal open/close logic
        function openPrivacyPolicyModal() {
            // Track which step is currently visible
            if (!lastStepId) {
                if (document.getElementById('step-1') && !document.getElementById('step-1').classList.contains('hidden')) {
                    lastStepId = 'step-1';
                } else if (document.getElementById('step-2') && !document.getElementById('step-2').classList.contains('hidden')) {
                    lastStepId = 'step-2';
                }
            }
            // Track last focused input
            const activeElement = document.activeElement;
            if (activeElement && activeElement.tagName === 'INPUT') {
                lastFocusedInputId = activeElement.id;
            }
            document.getElementById('privacyPolicyModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closePrivacyPolicyModal() {
            document.getElementById('privacyPolicyModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Scroll to Contact Information section
        function scrollToContactInfo() {
            // Restore last step
            var step1 = document.getElementById('step-1');
            var step2 = document.getElementById('step-2');
            if (step1 && step2 && lastStepId) {
                if (lastStepId === 'step-1') {
                    step1.classList.remove('hidden');
                    step2.classList.add('hidden');
                } else {
                    step1.classList.add('hidden');
                    step2.classList.remove('hidden');
                }
            }
            // Scroll to last focused input
            if (lastFocusedInputId) {
                var input = document.getElementById(lastFocusedInputId);
                if (input) {
                    input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    input.focus();
                }
            }
            // Reset trackers
            lastStepId = null;
            lastFocusedInputId = null;
        }

        // Modal event listeners
        document.addEventListener('DOMContentLoaded', function () {
            var closeBtn = document.getElementById('closePrivacyModal');
            var agreeBtn = document.getElementById('agreeBtnModal');
            if (closeBtn) {
                closeBtn.addEventListener('click', closePrivacyPolicyModal);
            }
            if (agreeBtn) {
                agreeBtn.addEventListener('click', function () {
                    closePrivacyPolicyModal();
                    scrollToContactInfo();
                });
            }
        });
        function closePrivacyPolicyModal() {
            document.getElementById('privacyPolicyModal').classList.add('hidden');
            document.body.style.overflow = '';
        }
        document.getElementById('closePrivacyModal').addEventListener('click', closePrivacyPolicyModal);
        document.getElementById('agreeBtnModal').addEventListener('click', function() {
            closePrivacyPolicyModal();
        });
    </script>
</body>


</html>



