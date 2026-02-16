        </div>

@extends('layouts.public')

@php
    $hideLayout = true;
@endphp

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4 max-w-5xl">
        
        {{-- Header --}}
        <div class="flex items-center mb-10">
            <img src="https://tse2.mm.bing.net/th/id/OIP._bP7eQwOSrZjwv-doDDsWAHaHa?rs=1&pid=ImgDetMain&o=7&rm=3" alt="Barangay Logo" class="h-16 w-16 rounded-full mr-4 shadow-md">
            <div>
                <h1 class="text-4xl font-bold text-gray-800">Privacy Policy</h1>
                <p class="text-gray-600 text-lg">ARIS - Automated Record Information System</p>
                <p class="text-gray-500 text-sm">Barangay Daang Bakal, Mandaluyong City</p>
            </div>
        </div>

        <div class="bg-white p-10 rounded-3xl shadow-2xl border border-blue-100 max-w-2xl mx-auto mt-8">
            <div class="mb-6">
                <h2 class="text-3xl font-extrabold text-blue-900 mb-1">Data Privacy Notice</h2>
                <h3 class="text-lg font-semibold text-blue-700">Automated Record and Information System (ARIS)</h3>
            </div>
            <p class="mb-6 text-gray-700 text-base leading-relaxed">The Barangay Daang Bakal Management System is committed to safeguarding and respecting your personal information in accordance with the Data Privacy Act of 2012 (Republic Act No. 10173) and its Implementing Rules and Regulations.</p>
            <div class="mb-8">
                <h4 class="text-xl font-bold text-blue-800 mb-2">Purpose of Data Collection</h4>
                <ul class="list-disc ml-6 text-gray-700 space-y-1">
                    <li>Verify identity and residency</li>
                    <li>Create and manage ARIS user accounts</li>
                    <li>Process document requests and complaints</li>
                    <li>Maintain accurate barangay records</li>
                    <li>Communicate official updates and notices</li>
                </ul>
            </div>
            <div class="mb-8">
                <h4 class="text-xl font-bold text-blue-800 mb-2">Data Sharing</h4>
                <p class="mb-2 text-gray-700">Your personal information is not sold or used for any commercial purpose. Data may only be shared with:</p>
                <ul class="list-disc ml-6 text-gray-700 space-y-1">
                    <li>Authorized barangay officials performing official functions</li>
                    <li>Government authorities, when required by law</li>
                    <li>Trusted service providers bound by confidentiality agreements</li>
                </ul>
            </div>
            <div class="mb-8">
                <h4 class="text-xl font-bold text-blue-800 mb-2">Data Retention</h4>
                <p class="text-gray-700">Personal data is retained only for as long as necessary to fulfill its intended purpose and to comply with barangay and legal record-keeping requirements, in accordance with RA 10173.</p>
            </div>
            <div class="mb-8">
                <h4 class="text-xl font-bold text-blue-800 mb-2">Your Rights as a Data Subject</h4>
                <ul class="list-disc ml-6 text-gray-700 space-y-1">
                    <li>Access your personal information</li>
                    <li>Request correction of inaccurate or incomplete data</li>
                    <li>Be informed about how your data is being processed</li>
                    <li>Lodge a complaint for any data privacy concerns</li>
                </ul>
            </div>
            <div class="mb-8">
                <h4 class="text-xl font-bold text-blue-800 mb-2">Consent</h4>
                <p class="text-gray-700">By registering and using ARIS, you give your consent to the collection, processing, and storage of your personal information as described in this Privacy Notice and in accordance with Republic Act No. 10173.</p>
            </div>
            <div class="mb-8">
                <h4 class="text-xl font-bold text-blue-800 mb-2">Contact Information</h4>
                <div class="bg-blue-50 p-4 rounded-xl border-l-4 border-blue-400">
                    <p class="font-semibold text-blue-900 mb-1">Barangay Daang Bakal ARIS Administration Office</p>
                    <p class="mb-1">📧 <span class="text-blue-700">Email:</span> <a href="mailto:barangaydaangbakal@gmail.com" class="underline text-blue-700">barangaydaangbakal@gmail.com</a></p>
                    <p>📞 <span class="text-blue-700">Office Hours:</span> 7:00 AM – 5:00 PM (Working Days Only)</p>
                </div>
            </div>
        </div>
            <div class="text-center mt-10 mb-8">
                <button id="agreeBtn" class="rounded-lg bg-blue-100 hover:bg-blue-200 px-8 py-3 text-base font-semibold text-gray-800 shadow-md transition-all duration-200">I Agree</button>
            </div>

    </div>
</div>

<script>
    // Enable/disable button based on checkbox
    const checkbox = document.getElementById('privacyAgreeCheckbox');
    const button = document.getElementById('agreeButton');

    checkbox.addEventListener('change', function() {
        if (this.checked) {
            button.disabled = false;
            button.classList.remove('bg-gray-400', 'cursor-not-allowed');
            button.classList.add('bg-blue-600', 'hover:bg-blue-700', 'cursor-pointer');
            button.style.opacity = '1';
        } else {
            button.disabled = true;
            button.classList.remove('bg-blue-600', 'hover:bg-blue-700', 'cursor-pointer');
            button.classList.add('bg-gray-400', 'cursor-not-allowed');
            button.style.opacity = '0.6';
        }
    });

    function handleAgree() {
        // Save agreement to localStorage
        localStorage.setItem('privacyPolicyAgreed', 'true');
        
        // Try to check parent window checkbox if popup opener exists
        if (window.opener && !window.opener.closed) {
            try {
                const parentCheckbox = window.opener.document.getElementById('agree');
                if (parentCheckbox) {
                    parentCheckbox.checked = true;
                    // Trigger change event so any listeners are notified
                    parentCheckbox.dispatchEvent(new Event('change', { bubbles: true }));
                    parentCheckbox.dispatchEvent(new Event('input', { bubbles: true }));
                }
            } catch(e) {
                console.log('Could not access parent window, using localStorage instead');
            }
        }
        
        // Close the window after a short delay to allow changes to register
        setTimeout(() => {
            window.close();
        }, 100);
    }
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('agreeBtn');
    btn && btn.addEventListener('click', function() {
        document.body.innerHTML = '';
    });
});
</script>
@endsection