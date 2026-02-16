@extends('layouts.public')

@php
    $hideLayout = true;
@endphp

@section('content')

@push('styles')
	<link rel="stylesheet" href="{{ asset('css/form-inputs.css') }}">
@endpush

<div id="registration-container">
    <div class="relative min-h-screen w-full bg-cover bg-center" style="background-image: url('https://media.karousell.com/media/photos/products/2025/3/4/lot_for_sale_in_barangay_daang_1741074654_b1a72035');">
        <div class="absolute inset-0 z-10 bg-white/70"></div>
        <div class="relative z-20 flex min-h-screen w-full items-center justify-center p-4">
            <form id="registration-form" class="w-full" novalidate="" enctype="multipart/form-data">
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
                                <div>
                                    <label for="last_name" class="mb-1 block text-sm font-semibold text-gray-700">Last Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="last_name" id="last_name" autocomplete="family-name" class="h-10 w-full rounded-lg border-2 border-gray-300 px-3 py-2 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                </div>
                                <div class="row-span-3">
                                    <label for="photo-upload" id="photo-upload-label" class="relative flex h-[220px] w-full cursor-pointer flex-col items-center justify-center overflow-hidden rounded-lg border-4 border-blue-300 bg-blue-50 transition hover:bg-blue-100">
                                        <div class="upload-placeholder-content text-center">
                                            <svg class="mx-auto h-10 w-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.437 4h3.126a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            <span class="mt-2 text-sm text-gray-600">Upload 1 x 1 Photo <span class="text-red-500">*</span></span>
                                        </div>
                                    </label>
                                    <input id="photo-upload" name="photo" type="file" class="hidden" accept="image/*" required>
                                </div>
                                <div>
                                    <label for="first_name" class="mb-1 block text-sm font-semibold text-gray-700">First Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="first_name" id="first_name" autocomplete="given-name" class="h-10 w-full rounded-lg border-2 border-gray-300 px-3 py-2 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                </div>
                                <div>
                                    <label for="middle_name" class="mb-1 block text-sm font-semibold text-gray-700">Middle Name</label>
                                    <input type="text" name="middle_name" id="middle_name" autocomplete="additional-name" class="h-10 w-full rounded-lg border-2 border-gray-300 px-3 py-2 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label for="suffix" class="mb-1 block text-sm font-semibold text-gray-700">Suffix</label>
                                    <input type="text" name="suffix" id="suffix" autocomplete="honorific-suffix" class="h-10 w-full rounded-lg border-2 border-gray-300 px-3 py-2 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label for="gender" class="mb-1 block text-sm font-semibold text-gray-700">Gender <span class="text-red-500">*</span></label>
                                    <select name="gender" id="gender" class="h-10 w-full rounded-lg border-2 border-gray-300 px-3 py-2 bg-gray-50 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer appearance-none" required>
                                        <option value="" disabled selected>Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Non Binary">Non Binary</option>
                                        <option value="Prefer not to say">Prefer not to say</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="age" class="mb-1 block text-sm font-semibold text-gray-700">Age <span class="text-red-500">*</span></label>
                                    <input type="number" name="age" id="age" autocomplete="off" class="h-10 w-full rounded-lg border-2 border-gray-300 px-3 py-2 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none" required>
                                </div>
                                <div>
                                    <label for="civil_status" class="mb-1 block text-sm font-semibold text-gray-700">Civil Status <span class="text-red-500">*</span></label>
                                    <select name="civil_status" id="civil_status" class="h-10 w-full rounded-lg border-2 border-gray-300 px-3 py-2 bg-gray-50 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer appearance-none" required>
                                        <option value="" disabled selected>Select Civil Status</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Widowed">Widowed</option>
                                        <option value="Divorced">Divorced</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="dob" class="mb-1 block text-sm font-semibold text-gray-700">Date of Birth <span class="text-red-500">*</span></label>
                                    <input type="date" name="dob" id="dob" autocomplete="bday" class="h-10 w-full rounded-lg border-2 border-gray-300 px-3 py-2 bg-gray-50 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer" required>
                                </div>
                                <div>
                                    <label for="citizenship" class="mb-1 block text-sm font-semibold text-gray-700">Citizenship</label>
                                    <input type="text" name="citizenship" id="citizenship" autocomplete="off" class="h-10 w-full rounded-lg border-2 border-gray-300 px-3 py-2 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label for="place_of_birth" class="mb-1 block text-sm font-semibold text-gray-700">Place of Birth <span class="text-red-500">*</span></label>
                                    <input type="text" name="place_of_birth" id="place_of_birth" autocomplete="off" class="h-10 w-full rounded-lg border-2 border-gray-300 px-3 py-2 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                </div>
                            </div>
                        </div>
                        <div class="flex shrink-0 justify-end items-center gap-2 sm:gap-3 px-3 sm:px-4 -mt-6 mb-6">
                            <a href="/" class="rounded-lg bg-gray-200 hover:bg-red-600 px-4 sm:px-6 py-2 sm:py-2.5 text-sm font-semibold text-gray-700 hover:text-white flex items-center border border-gray-300 transition-all duration-200 shadow-md">CANCEL</a>
                            <button id="next-button" type="button" class="rounded-lg bg-gray-200 hover:bg-blue-600 px-4 sm:px-6 py-2 sm:py-2.5 text-sm font-semibold text-gray-700 hover:text-white flex items-center border border-gray-300 transition-all duration-200 shadow-md">NEXT</button>
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
                                    </div>
                                    <div>
                                        <label for="address" class="mb-1 block text-sm font-semibold text-gray-700">House/Unit Number, Street <span class="text-red-500">*</span></label>
                                        <input type="text" name="address" id="address" autocomplete="street-address" class="h-10 w-full rounded-lg border-2 border-gray-300 bg-gray-50 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                    </div>
                                </div>
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
                                    <p class="px-1 text-justify text-xs text-gray-600">All government-issued valid IDs are accepted. For students, present a School ID. For applicants below 5 years old, a Certificate of Live Birth is required. Accepted file formats are JPG or PNG, with a maximum file size of 5 MB.</p>
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
                            <div class="mt-6 flex items-center">
                                <input id="agree" name="agree" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" required>
                                <label for="agree" class="ml-2 block text-sm text-gray-800">I have read and agree to the collection and use of my personal information as described in the
                                <a href="#" onclick="openPrivacyPolicy(); return false;" class="text-blue-600 underline cursor-pointer">Privacy Policy</a>.</label>
                            </div>
                        </div>
                        <div class="flex shrink-0 justify-end items-center gap-2 sm:gap-3 px-3 sm:px-4 mt-4 mb-6">
                            <button id="back-button" type="button" class="rounded-lg bg-gray-200 hover:bg-gray-300 px-4 sm:px-6 py-2 sm:py-2.5 text-sm font-semibold text-gray-700 flex items-center border border-gray-300 transition-all duration-200 shadow-md">BACK</button>
                            <button id="submit-btn" type="submit" class="rounded-lg bg-gray-200 hover:bg-blue-600 hover:text-white px-4 sm:px-6 py-2 sm:py-2.5 text-sm font-semibold text-gray-700 flex items-center border border-gray-300 transition-all duration-200 shadow-md">SUBMIT</button>
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

                <h2 class="font-extrabold text-base sm:text-xl mb-2 sm:mb-3 text-white tracking-wide">
                    Thank you for registering! Your information has been submitted successfully!
                </h2>
            </div>

            <div class="p-6 sm:p-8 text-center">
                <p class="text-xs sm:text-sm text-gray-700 leading-relaxed mb-2 sm:mb-3">
                    Please wait for the admins approval before you can access your account.
                </p>

                <p class="text-sm text-gray-700 leading-relaxed mb-6">
                    You will receive an email or text message once your registration is approved.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
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
        document.getElementById('next-button').addEventListener('click', () => {
            const step1Fields = ['last_name', 'first_name', 'photo-upload', 'gender', 'age', 'civil_status', 'dob', 'place_of_birth'];
            if (validateFields(step1Fields)) {
                step1.classList.add('hidden');
                step2.classList.remove('hidden');
            }
        });

        // --- Back Navigation ---
        document.getElementById('back-button').addEventListener('click', () => {
            step2.classList.add('hidden');
            step1.classList.remove('hidden');
        });

        // --- FORM SUBMISSION (UPDATED FOR DATABASE SAVING) ---
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
            const originalText = submitBtn.innerText;

            // 2. Show Loading State
            submitBtn.innerText = "PROCESSING...";
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

            // 3. Send to Laravel Backend
            // We use the URL path we defined in routes/web.php
            fetch('/register/submit', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // Blade injects the token here
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // SUCCESS: Show the modal
                    registrationContainer.classList.add('hidden');
                    successPage.classList.remove('hidden');
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
                submitBtn.innerText = originalText;
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            });
        });

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
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                eyeOpen.classList.toggle('hidden');
                eyeClosed.classList.toggle('hidden');
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
                const type = passwordConfirmInput.type === 'password' ? 'text' : 'password';
                passwordConfirmInput.type = type;
                eyeOpenConfirm.classList.toggle('hidden');
                eyeClosedConfirm.classList.toggle('hidden');
            });
        }
    });
</script>
@endsection
