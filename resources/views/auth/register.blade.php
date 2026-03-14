@extends('layouts.public')

@php
    $hideLayout = true;
@endphp

@section('content')


@push('styles')
    <link rel="stylesheet" href="{{ asset('css/form-inputs.css') }}">
@endpush

<!-- Privacy Policy Modal -->

<div id="privacy-policy-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80 transition-opacity duration-300 hidden sm:hidden">
    <div class="relative bg-white min-h-[70vh] max-h-[90vh] w-full max-w-3xl overflow-y-auto rounded-3xl shadow-2xl border border-blue-200 animate-fadeIn">
        <!-- Close Icon -->
        <button type="button" onclick="closePrivacyPolicyModal()" class="absolute top-4 right-4 z-10 p-2 rounded-full bg-blue-100 hover:bg-blue-200 text-blue-700 shadow-md transition-all duration-200" aria-label="Close">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="container mx-auto px-6 py-8 max-w-5xl">
            <!-- Header -->
            <div class="flex items-center mb-8 gap-4">
                <img src="https://tse2.mm.bing.net/th/id/OIP._bP7eQwOSrZjwv-doDDsWAHaHa?rs=1&pid=ImgDetMain&o=7&rm=3" alt="Barangay Logo" class="h-16 w-16 rounded-full shadow-md border-2 border-blue-200">
                <div>
                    <h1 class="text-4xl font-bold text-blue-900">Privacy Policy</h1>
                    <p class="text-gray-600 text-lg">ARIS - Automated Record Information System</p>
                    <p class="text-gray-500 text-sm">Barangay Daang Bakal, Mandaluyong City</p>
                </div>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow-xl border border-blue-100 max-w-2xl mx-auto mt-6">
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
            <div class="text-center mt-8 mb-4">
                <button type="button" onclick="closePrivacyPolicyModal()" class="rounded-lg bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 text-base font-semibold shadow-lg transition-all duration-200">Back</button>
            </div>
        </div>
    </div>
</div>

<div id="registration-container">
    <div class="relative min-h-screen w-full bg-cover bg-center" style="background-image: url('https://media.karousell.com/media/photos/products/2025/3/4/lot_for_sale_in_barangay_daang_1741074654_b1a72035');">
        <div class="absolute inset-0 z-10 bg-white/70"></div>
        <div class="relative z-20 flex min-h-screen w-full items-center justify-center p-4">
            <form id="registration-form" class="w-full" novalidate enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="barangay" value="Barangay Daang Bakal">
                <div id="step-1" class="mx-auto max-w-4xl">
                    <div class="flex max-h-[90vh] sm:max-h-[90vh] h-auto sm:flex-col overflow-y-auto sm:overflow-hidden flex-col rounded-2xl bg-white border-2 border-gray-100 shadow-xl">
                        <div class="flex shrink-0 items-center p-3 sm:p-4" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
                            <img src="https://tse2.mm.bing.net/th/id/OIP._bP7eQwOSrZjwv-doDDsWAHaHa?rs=1&pid=ImgDetMain&o=7&rm=3" alt="Barangay Logo" class="mr-3 sm:mr-4 h-10 w-10 sm:h-12 sm:w-12 rounded-full">
                            <h1 class="text-base sm:text-xl font-semibold text-white">Barangay Daang Bakal</h1>
                        </div>
                        <div class="overflow-y-auto md:overflow-y-visible p-3 sm:p-4">
                            <h3 class="mb-2 sm:mb-3 text-xl sm:text-2xl font-bold text-gray-800">PERSONAL INFORMATION</h3>
                            <p class="mb-2 sm:mb-3 text-xs sm:text-sm text-gray-700">All fields marked with <span class="text-red-500 font-semibold">*</span> are required.</p>
                            <div class="grid grid-cols-1 gap-x-8 gap-y-4 md:grid-cols-2">
                                <!-- Photo Upload for Phone View -->
                                <div class="block md:hidden mb-4">
                                    <label for="photo-upload-phone" id="photo-upload-label-phone" class="relative flex h-[220px] w-full cursor-pointer flex-col items-center justify-center overflow-hidden rounded-lg border-4 border-blue-300 bg-blue-50 transition hover:bg-blue-100">
                                        <div class="upload-placeholder-content text-center">
                                            <svg class="mx-auto h-10 w-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.437 4h3.126a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            <span class="mt-2 text-sm text-gray-600">Upload 1 x 1 Photo <span class="text-red-500">*</span></span>
                                        </div>
                                        <img id="photo-preview-phone" src="#" alt="Photo Preview" class="hidden absolute top-0 left-0 w-full h-full object-cover rounded-lg border-4 border-blue-300 bg-blue-50" />
                                    </label>
                                    <input id="photo-upload-phone" name="photo_phone" type="file" class="hidden" accept="image/*" onchange="previewPhoto(event, 'photo-preview-phone')">
                                </div>
                                <div>
                                    <label for="last_name" class="mb-1 block text-sm font-semibold text-gray-700">Last Name <span class="text-red-500">*</span></label>
                                        <input type="text" name="last_name" id="last_name" autocomplete="family-name" class="h-9 w-full rounded-lg border-2 border-gray-300 px-3 py-1 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                </div>
                                <div class="row-span-3 hidden md:block">
                                    <label for="photo-upload-desktop" id="photo-upload-label-desktop" class="relative flex h-[220px] w-full cursor-pointer flex-col items-center justify-center overflow-hidden rounded-lg border-4 border-blue-300 bg-blue-50 transition hover:bg-blue-100">
                                        <div class="upload-placeholder-content text-center">
                                            <svg class="mx-auto h-10 w-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.437 4h3.126a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            <span class="mt-2 text-sm text-gray-600">Upload 1 x 1 Photo <span class="text-red-500">*</span></span>
                                        </div>
                                        <img id="photo-preview-desktop" src="#" alt="Photo Preview" class="hidden absolute top-0 left-0 w-full h-full object-cover rounded-lg border-4 border-blue-300 bg-blue-50" />
                                    </label>
                                    <input id="photo-upload-desktop" name="photo_desktop" type="file" class="hidden" accept="image/*" onchange="previewPhoto(event, 'photo-preview-desktop')">
                                </div>
                                <div>
                                    <label for="first_name" class="mb-1 block text-sm font-semibold text-gray-700">First Name <span class="text-red-500">*</span></label>
                                        <input type="text" name="first_name" id="first_name" autocomplete="given-name" class="h-9 w-full rounded-lg border-2 border-gray-300 px-3 py-1 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label for="middle_name" class="mb-1 block text-sm font-semibold text-gray-700">Middle Name</label>
                                        <input type="text" name="middle_name" id="middle_name" autocomplete="additional-name" class="h-9 w-full rounded-lg border-2 border-gray-300 px-3 py-1 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label for="suffix" class="mb-1 block text-sm font-semibold text-gray-700">Suffix</label>
                                        <input type="text" name="suffix" id="suffix" autocomplete="honorific-suffix" class="h-9 w-full rounded-lg border-2 border-gray-300 px-3 py-1 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div class="order-4 sm:order-none">
                                    <label for="gender" class="mb-1 block text-sm font-semibold text-gray-700">Gender <span class="text-red-500">*</span></label>
                                        <select name="gender" id="gender" class="h-9 w-full rounded-lg border-2 border-gray-300 px-3 py-1 bg-gray-50 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer appearance-none" required>
                                        <option value="" disabled selected>Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Non Binary">Non Binary</option>
                                        <option value="Prefer not to say">Prefer not to say</option>
                                    </select>
                                </div>
                                <div class="order-1 sm:order-none">
                                    <label for="age" class="mb-1 block text-sm font-semibold text-gray-700">Age <span class="text-red-500">*</span></label>
                                        <input type="number" name="age" id="age" autocomplete="off" class="h-9 w-full rounded-lg border-2 border-gray-300 px-3 py-1 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none" required>
                                </div>
                                <div class="order-5 sm:order-none">
                                    <label for="civil_status" class="mb-1 block text-sm font-semibold text-gray-700">Civil Status <span class="text-red-500">*</span></label>
                                        <select name="civil_status" id="civil_status" class="h-9 w-full rounded-lg border-2 border-gray-300 px-3 py-1 bg-gray-50 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer appearance-none" required>
                                        <option value="" disabled selected>Select Civil Status</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Widowed">Widowed</option>
                                        <option value="Divorced">Divorced</option>
                                    </select>
                                </div>
                                <div class="order-2 sm:order-none">
                                    <label for="dob" class="mb-1 block text-sm font-semibold text-gray-700">Date of Birth <span class="text-red-500">*</span></label>
                                        <input type="date" name="dob" id="dob" autocomplete="bday" class="h-9 w-full rounded-lg border-2 border-gray-300 px-3 py-1 bg-gray-50 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer" required>
                                </div>
                                <div class="order-6 sm:order-none">
                                    <label for="citizenship" class="mb-1 block text-sm font-semibold text-gray-700">Citizenship</label>
                                        <input type="text" name="citizenship" id="citizenship" autocomplete="off" class="h-9 w-full rounded-lg border-2 border-gray-300 px-3 py-1 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div class="order-3 sm:order-none">
                                    <label for="place_of_birth" class="mb-1 block text-sm font-semibold text-gray-700">Place of Birth <span class="text-red-500">*</span></label>
                                        <input type="text" name="place_of_birth" id="place_of_birth" autocomplete="off" class="h-9 w-full rounded-lg border-2 border-gray-300 px-3 py-1 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                </div>
                            </div>
                            <div class="flex shrink-0 justify-end items-center gap-2 sm:gap-3 px-3 sm:px-2 mb-4 ml-2 mt-4 sm:mt-4">
                                <button id="cancel-button" type="button" onclick="window.location.href='/'" class="rounded-lg bg-gray-200 hover:bg-red-600 hover:text-white px-4 sm:px-6 py-2 sm:py-2.5 text-sm font-semibold text-gray-700 flex items-center border border-gray-300 transition-all duration-200 shadow-md">CANCEL</button>
                                <button id="next-button" type="button" class="rounded-lg bg-gray-200 hover:bg-blue-600 hover:text-white px-4 sm:px-6 py-2 sm:py-2.5 text-sm font-semibold text-gray-700 flex items-center border border-gray-300 transition-all duration-200 shadow-md">NEXT</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="step-2" class="mx-auto max-w-4xl hidden">
                    <div class="flex max-h-[90vh] sm:max-h-[90vh] h-auto sm:flex-col overflow-y-auto sm:overflow-hidden flex-col rounded-2xl bg-white border-2 border-gray-100 shadow-xl">
                        <div class="flex shrink-0 items-center p-3 sm:p-4 text-white" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
                            <img src="https://tse2.mm.bing.net/th/id/OIP._bP7eQwOSrZjwv-doDDsWAHaHa?rs=1&pid=ImgDetMain&o=7&rm=3" alt="Barangay Logo" class="mr-3 sm:mr-4 h-10 w-10 sm:h-12 sm:w-12 rounded-full border-2 border-white shadow-md">
                            <h1 class="text-base sm:text-xl font-semibold text-white">Barangay Daang Bakal</h1>
                        </div>
                        <div class="overflow-y-auto p-3 sm:p-4">
                            <h3 class="mb-2 sm:mb-3 text-xl sm:text-2xl font-bold text-gray-800">CONTACT INFORMATION</h3>
                             <p class="mb-2 sm:mb-3 text-xs sm:text-sm text-gray-700">All fields marked with <span class="text-red-500 font-semibold">*</span> are required.</p>
                            <div class="grid grid-cols-1 gap-x-8 gap-y-4 md:grid-cols-2">
                                <!-- Left: Contact fields -->
                                <div class="flex flex-col space-y-4">
                                    <div>
                                        <label for="contact_number" class="mb-1 block text-sm font-semibold text-gray-700">Contact Number <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"><span>+63</span></div>
                                            <input type="tel" name="contact_number" id="contact_number" autocomplete="tel-national" class="h-10 w-full rounded-lg border-2 border-gray-300 bg-gray-50 py-2 pl-12 pr-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" maxlength="10" inputmode="numeric" required>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="email" class="mb-1 block text-sm font-semibold text-gray-700">Email Address</label>
                                        <input type="email" name="email" id="email" autocomplete="email" class="h-10 w-full rounded-lg border-2 border-gray-300 bg-gray-50 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <p class="mt-1 text-xs text-gray-500">Provide an email to enable self-service password reset. If you don't have one, you can still register, but password resets must be done at the Barangay Hall.</p>
                                    </div>
                                    <div>
                                        <label for="address" class="mb-1 block text-sm font-semibold text-gray-700">House/Unit Number, Street <span class="text-red-500">*</span></label>
                                        <input type="text" name="address" id="address" autocomplete="street-address" class="h-10 w-full rounded-lg border-2 border-gray-300 bg-gray-50 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    </div>
                                </div>
                                <!-- Right: ID upload fields and instructions -->
                                <div class="flex flex-col space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <label for="id-front-upload" id="id-front-label" class="relative flex h-36 cursor-pointer flex-col items-center justify-center overflow-hidden rounded-lg border-4 border-blue-300 bg-blue-50 text-center transition hover:bg-blue-100">
                                            <div class="upload-placeholder-content">
                                                <svg class="mx-auto h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" /><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" /></svg>
                                                <span class="mt-2 text-sm text-gray-600">Upload Front Photo <span class="text-red-500">*</span></span>
                                            </div>
                                        </label>
                                        <input type="file" name="id_front" id="id-front-upload" class="hidden" accept="image/*" required>

                                        <label for="id-back-upload" id="id-back-label" class="relative flex h-36 cursor-pointer flex-col items-center justify-center overflow-hidden rounded-lg border-4 border-blue-300 bg-blue-50 text-center transition hover:bg-blue-100">
                                            <div class="upload-placeholder-content">
                                                <svg class="mx-auto h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" /><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" /></svg>
                                                <span class="mt-2 text-sm text-gray-600">Upload Back Photo <span class="text-red-500">*</span></span>
                                            </div>
                                        </label>
                                        <input type="file" name="id_back" id="id-back-upload" class="hidden" accept="image/*" required>
                                    </div>
                                    <p class="px-1 text-justify text-xs text-gray-600">All government-issued valid IDs are accepted. For students, present a School ID. For applicants below 5 years old, a Certificate of Live Birth is required. Accepted file formats are JPG or PNG, with a maximum file size of 10 MB.</p>
                                </div>
                            </div>
                            <div class="pt-20">
                                <h3 class="mb-4 text-2xl font-semibold text-gray-800">ACCOUNT CONFIRMATION</h3>
                                <div class="grid grid-cols-1 gap-x-6 gap-y-4 md:grid-cols-3">
                                    <div>
                                        <label for="username" class="mb-1 block text-sm font-semibold text-gray-700">User Name <span class="text-red-500">*</span></label>
                                        <input type="text" name="username" id="username" autocomplete="username" class="h-10 w-full rounded-lg border-2 border-gray-300 bg-gray-50 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    </div>
                                    <div>
                                        <label for="password" class="mb-1 block text-sm font-semibold text-gray-700">Password <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input type="password" name="password" id="password" autocomplete="new-password" class="h-10 w-full rounded-lg border-2 border-gray-300 bg-gray-50 px-3 pr-12 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                            <button type="button" id="togglePassword" class="absolute right-4 top-2 text-gray-400 hover:text-blue-600 transition duration-200 p-1">
                                                <svg id="eyeOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <svg id="eyeClosed" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="password_confirmation" class="mb-1 block text-sm font-semibold text-gray-700">Confirm Password <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password" class="h-10 w-full rounded-lg border-2 border-gray-300 bg-gray-50 px-3 pr-12 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                            <button type="button" id="togglePasswordConfirm" class="absolute right-4 top-2 text-gray-400 hover:text-blue-600 transition duration-200 p-1">
                                                <svg id="eyeOpenConfirm" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <svg id="eyeClosedConfirm" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-gray-600">Password must be at least 8 characters long and include an uppercase letter, lowercase letter, number, and special character.</p>
                            </div>

                            {{-- Validation Error Area --}}
                            <div id="registrationErrors" class="text-red-500 text-xs mt-4 hidden text-center"></div>

                            <div class="mt-6 flex items-center">
                                <input id="agree" name="agree" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" required>
                                <label for="agree" class="ml-2 block text-sm text-gray-800">I have read and agree to the collection and use of my personal information as described in the
                                    <span class="sm:hidden"><a href="#" onclick="openPrivacyPolicy(); return false;" class="text-blue-600 underline cursor-pointer">Privacy Policy</a></span>
                                    <span class="hidden sm:inline"><a href="/privacy-policy" target="_blank" class="text-blue-600 underline cursor-pointer">Privacy Policy</a></span>
                                </label>
                            </div>
                        
                        <div class="flex shrink-0 justify-end items-center gap-2 sm:gap-3 px-3 sm:px-2 mb-4 ml-2 mt-4 sm:mt-4">
                            <button id="back-button" type="button" class="rounded-lg bg-gray-200 hover:bg-red-600 hover:text-white px-4 sm:px-6 py-2 sm:py-2.5 text-sm font-semibold text-gray-700 flex items-center border border-gray-300 transition-all duration-200 shadow-md">BACK</button>
                            <button id="submit-btn" type="submit" class="rounded-lg bg-gray-200 hover:bg-blue-600 hover:text-white px-4 sm:px-6 py-2 sm:py-2.5 text-sm font-semibold text-gray-700 flex items-center border border-gray-300 transition-all duration-200 shadow-md">SUBMIT</button>
                        </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="success-page" class="hidden fixed inset-0 z-50">
    <div class="absolute inset-0 w-full h-full bg-cover bg-center" style="background-image: url('https://media.karousell.com/media/photos/products/2025/3/4/lot_for_sale_in_barangay_daang_1741074654_b1a72035');"></div>
    <div class="absolute inset-0 z-10 bg-white/70"></div>
    <div class="relative z-20 flex h-full w-full items-center justify-center p-4 sm:p-6">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl border-2 border-gray-100 overflow-hidden">
            <div class="p-6 sm:p-10 text-center" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
                <div class="flex justify-center mb-3 sm:mb-4">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-4 border-emerald-500 flex items-center justify-center bg-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>

                <h2 class="font-extrabold text-base sm:text-xl mb-2 sm:mb-2 text-white tracking-wide">
                    Thank you for registering!
                </h2>
                <p class="text-sm text-white mb-0">Your information has been submitted successfully!</p>
            </div>

            <div class="p-6 sm:p-8 text-center">
                <p class="text-[14px] text-gray-700 leading-relaxed mb-2 sm:mb-3">
                    Please wait for the admins approval before you can access your account.
                </p>

                <p class="text-sm text-gray-700 leading-relaxed mb-6">
                    You will receive an email or text message once your registration is approved.
                </p>
                <div class="flex justify-center">
                    <a href="/" class="bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-2 rounded-lg font-semibold transition">Close</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/auth-register.js') }}" defer></script>
<script>
function previewPhoto(event, previewId) {
    const input = event.target;
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '#';
        preview.classList.add('hidden');
    }
}
</script>
@endsection
