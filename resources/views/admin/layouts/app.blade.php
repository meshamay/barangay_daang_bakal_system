<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $roleRaw = strtolower(Auth::user()->role ?? '');
        $userTypeRaw = strtolower(Auth::user()->user_type ?? '');
        $isSuperAdmin = Auth::check() && (in_array($roleRaw, ['super admin', 'super_admin', 'superadmin']) || in_array($userTypeRaw, ['super admin', 'super_admin', 'superadmin']));
        $isAdminUser = Auth::check() && (in_array($roleRaw, ['admin']) || in_array($userTypeRaw, ['admin']));
        $topRoleLabel = $isSuperAdmin ? 'Super Administrator' : ($isAdminUser ? 'Administrator' : ucfirst(Auth::user()->role ?? Auth::user()->user_type ?? ''));
    @endphp
    <title>{{ $isSuperAdmin ? 'Super Admin Panel' : 'Admin Panel' }}</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dropdowns.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-modals.css') }}">
    <script src="{{ asset('js/admin-modals.js') }}" defer></script>

    @stack('styles')
    @stack('scripts')

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif'],
                        barlow: ['Barlow Semi Condensed', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>

<body class="font-poppins bg-gray-100 h-screen overflow-hidden">

@if(!($hideLayout ?? false))

<!-- SIDEBAR TOGGLE -->
<input type="checkbox" id="sidebar-toggle" class="peer hidden">

<!-- MAIN WRAPPER -->
<div class="flex flex-col h-screen ">

    <!-- TOP NAVBAR -->
    <nav id="top-navbar"
         class="fixed top-0 left-0 w-full h-16 sm:h-20 font-poppins bg-gradient-to-r from-[#134573] via-[#0f3a5f] to-[#0a2847]
         text-white shadow-lg z-30 flex items-center justify-between px-3 sm:px-6 border-b border-white/10">

        <div class="flex items-center gap-2 sm:gap-4">
            <label for="sidebar-toggle" class="cursor-pointer md:hidden p-2 rounded-lg hover:bg-white/10 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </label>

            <div class="flex items-center space-x-2 sm:space-x-3">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 sm:space-x-3">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b7/Mandaluyong_seal.svg/1024px-Mandaluyong_seal.svg.png" class="w-8 h-8 sm:w-12 sm:h-12">
                    <img src="https://tse2.mm.bing.net/th/id/OIP._bP7eQwOSrZjwv-doDDsWAHaHa" class="w-8 h-8 sm:w-12 sm:h-12 rounded-full object-cover">

                    <div>
                        <h1 class="text-xs sm:text-sm md:text-base font-semibold leading-tight text-white">Barangay Daang Bakal</h1>
                        <span class="font-poppins text-[10px] sm:text-xs md:text-sm lg:text-base font-semibold text-white">Mandaluyong City</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- RIGHT SIDE: NOTIFICATION + PROFILE -->
        <div class="flex items-center gap-3 sm:gap-6">

        <!-- NOTIFICATION BELL -->

<div x-data="notificationHandler()" 
     x-init="init"
     @click.away="open = false"
     class="relative">
    
    <button @click="toggleDropdown" class="relative p-1.5 sm:p-2 rounded-lg hover:bg-white/10 transition duration-200">
        <span class="sr-only">View notifications</span>
        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5.982-6H12a6 6 0 00-5 5.917V14.158a2.032 2.032 0 01-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute top-0 right-0 block h-4 w-4 sm:h-5 sm:w-5 text-xs text-center rounded-full bg-red-500 ring-2 ring-white font-bold flex items-center justify-center"></span>
    </button>

    <div x-show="open" class="absolute right-0 mt-2 w-[calc(100vw-1.5rem)] sm:w-96 max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden z-50 text-slate-800 border-2 border-gray-100" 
         x-cloak 
         style="display: none;">
        <div class="px-4 sm:px-6 py-3 sm:py-4 rounded-t-2xl" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
            <div class="flex justify-between items-center">
                <h3 class="text-base sm:text-lg font-bold text-white">Notifications</h3>
                <button @click="markAllAsRead" x-show="unreadCount > 0" class="text-xs text-blue-100 hover:text-white transition-colors font-semibold">Mark all as read</button>
            </div>
        </div>
        <div class="max-h-[60vh] sm:max-h-96 overflow-y-auto divide-y divide-gray-100">
            <template x-if="notifications.length > 0">
                <template x-for="notification in notifications" :key="notification.id">
                    <a :href="notification.data.link" 
                       @click.prevent="markAsRead(notification.id, notification.data.link)"
                       class="block px-5 py-4 transition-all duration-200"
                       :class="{'bg-blue-50/70 hover:bg-blue-50 border-l-4 border-blue-500': !notification.read_at, 'hover:bg-gray-50': notification.read_at}">
                        <div class="flex items-start gap-2 sm:gap-3">
                            <div class="flex-shrink-0">
                                <template x-if="notification.data.type === 'complaint'">
                                    <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-xl bg-red-100 flex items-center justify-center">
                                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                        </svg>
                                    </div>
                                </template>
                                <template x-if="notification.data.type === 'document'">
                                    <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-xl bg-blue-100 flex items-center justify-center">
                                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                            <polyline points="13 2 13 9 20 9"></polyline>
                                        </svg>
                                    </div>
                                </template>
                            </div>
                            <div class="flex-grow">
                                <p class="text-sm font-semibold" 
                                   :class="{'text-gray-900': !notification.read_at, 'text-gray-600': notification.read_at}" 
                                   x-text="notification.data.title"></p>
                                <p class="text-xs mt-1" 
                                   :class="{'text-gray-700': !notification.read_at, 'text-gray-500': notification.read_at}" 
                                   x-text="notification.data.message"></p>
                                <p class="text-xs text-gray-400 mt-2" x-text="formatDate(notification.created_at)"></p>
                            </div>
                        </div>
                    </a>
                </template>
            </template>
            <template x-if="notifications.length === 0">
                <div class="p-8 text-center">
                    <svg class="h-12 w-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5.982-6H12a6 6 0 00-5 5.917V14.158a2.032 2.032 0 01-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <p class="text-sm text-gray-500 font-medium">No new notifications</p>
                    <p class="text-xs text-gray-400 mt-1">You're all caught up!</p>
                </div>
            </template>
        </div>
    </div>
</div>

<!-- PROFILE SECTION -->
        <div class="flex items-center pl-3 sm:pl-6 border-l border-blue-300/30 h-6 sm:h-8">

            <div class="text-right mr-2 sm:mr-3 hidden sm:block">
                <p class="text-sm sm:text-md font-bold text-white leading-none">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
                <p class="text-xs text-blue-200 font-medium mt-1">{{ $topRoleLabel }}</p>
            </div>

            <div x-data="{ open: false }" @click.away="open = false" class="relative">
                <button @click="open = !open" class="h-8 w-8 sm:h-10 sm:w-10 rounded-full overflow-hidden bg-white/10 border border-white/40 flex items-center justify-center hover:bg-white/20 focus:outline-none transition">
                    @php
                        $adminPhoto = Auth::user()->photo_path ? asset('storage/' . Auth::user()->photo_path) : null;
                    @endphp
                    @if($adminPhoto)
                        <img src="{{ $adminPhoto }}" class="h-full w-full object-cover">
                    @else
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    @endif
                </button>
        
        <!-- Dropdown Menu -->
        <div x-show="open" x-cloak style="display: none;"
             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl z-50 overflow-hidden border-2 border-gray-100">
            <a href="{{ route('admin.profile') }}" class="block w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition duration-200 flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                View Profile
            </a>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="block w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition duration-200 flex items-center gap-2 border-t border-gray-100">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Sign Out
                </button>
            </form>
        </div>
                    </div>
              </div>
            </div>
        </div>
      </div>
    </nav>

    <!-- PAGE CONTENT WITH SIDEBAR -->
    <div class="flex h-screen pt-20">

        <aside class="font-poppins w-60 bg-gradient-to-b from-[#134573] via-[#0f3a5f] to-[#0a2847] p-6 overflow-y-auto transition-transform duration-300 ease-in-out shadow-2xl
                       absolute inset-y-0 left-0 transform -translate-x-full z-20
                       md:fixed md:top-20 md:left-0 md:translate-x-0
                       peer-checked:translate-x-0 h-[calc(100vh-80px)]">

            <ul class="space-y-1">

                <!-- Dashboard -->
                <li>
                     <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center py-3 px-4 rounded-lg font-semibold text-sm transition duration-200 group
                       {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 text-white border-l-4 border-white' : 'text-gray-200 hover:bg-white/10' }}">
                        <!-- ICON -->
        <svg class="w-12 h-12 mr-3 shrink-0 {{ request()->routeIs('admin.dashboard') ? 'stroke-white' : 'stroke-gray-300 group-hover:stroke-white' }}" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
            <path fill="none" fill-rule="evenodd" clip-rule="evenodd"
                d="M9.918 10.0005H7.082C6.66587 9.99708 6.26541 10.1591 5.96873 10.4509C5.67204 10.7427 5.50343 11.1404 5.5 11.5565V17.4455C5.5077 18.3117 6.21584 19.0078 7.082 19.0005H9.918C10.3341 19.004 10.7346 18.842 11.0313 18.5502C11.328 18.2584 11.4966 17.8607 11.5 17.4445V11.5565C11.4966 11.1404 11.328 10.7427 11.0313 10.4509C10.7346 10.1591 10.3341 9.99708 9.918 10.0005Z"
                stroke="currentColor" stroke-width="1.5" />
            <path fill="none" fill-rule="evenodd" clip-rule="evenodd"
                d="M9.918 4.0006H7.082C6.23326 3.97706 5.52559 4.64492 5.5 5.4936V6.5076C5.52559 7.35629 6.23326 8.02415 7.082 8.0006H9.918C10.7667 8.02415 11.4744 7.35629 11.5 6.5076V5.4936C11.4744 4.64492 10.7667 3.97706 9.918 4.0006Z"
                stroke="currentColor" stroke-width="1.5" />
            <path fill="none" fill-rule="evenodd" clip-rule="evenodd"
                d="M15.082 13.0007H17.917C18.3333 13.0044 18.734 12.8425 19.0309 12.5507C19.3278 12.2588 19.4966 11.861 19.5 11.4447V5.55666C19.4966 5.14054 19.328 4.74282 19.0313 4.45101C18.7346 4.1592 18.3341 3.9972 17.918 4.00066H15.082C14.6659 3.9972 14.2654 4.1592 13.9687 4.45101C13.672 4.74282 13.5034 5.14054 13.5 5.55666V11.4447C13.5034 11.8608 13.672 12.2585 13.9687 12.5503C14.2654 12.8421 14.6659 13.0041 15.082 13.0007Z"
                stroke="currentColor" stroke-width="1.5" />
            <path fill="none" fill-rule="evenodd" clip-rule="evenodd"
                d="M15.082 19.0006H17.917C18.7661 19.0247 19.4744 18.3567 19.5 17.5076V16.4936C19.4744 15.6449 18.7667 14.9771 17.918 15.0006H15.082C14.2333 14.9771 13.5256 15.6449 13.5 16.4936V17.5066C13.525 18.3557 14.2329 19.0241 15.082 19.0006Z"
                stroke="currentColor" stroke-width="1.5" />
        </svg>
        <span>Dashboard</span>
    </a>
</li>


<!-- Users -->
<li>
    {{-- REPLACED hardcoded URL with {{ route('admin.users.index') }} --}}
    <a href="{{ route('admin.users.index') }}"
       class="flex items-center py-3 px-4 rounded-lg font-semibold text-sm transition duration-200 group
       {{ request()->routeIs('admin.users.*') ? 'bg-white/20 text-white border-l-4 border-white' : 'text-gray-200 hover:bg-white/10' }}">

        <svg class="w-9 h-9 mr-3 shrink-0 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-gray-300 group-hover:text-white' }}" viewBox="0 0 16 16">
            <path fill="currentColor" d="M8 7C9.65685 7 11 5.65685 11 4C11 2.34315 9.65685 1 8 1C6.34315 1 5 2.34315 5 4C5 5.65685 6.34315 7 8 7Z"></path>
            <path fill="currentColor" d="M14 12C14 10.3431 12.6569 9 11 9H5C3.34315 9 2 10.3431 2 12V15H14V12Z"></path>
        </svg>
        <span>Users</span>
    </a>
</li>

<!-- Document Request -->
<li>
                <a href="{{ route('admin.documents.index') }}" class="flex items-center py-3 px-4 rounded-lg font-semibold text-sm transition duration-200 group
                {{ request()->routeIs('admin.documents.*') ? 'bg-white/20 text-white border-l-4 border-white' : 'text-gray-200 hover:bg-white/10' }}">
                    <svg class="w-9 h-9 mr-3 shrink-0 {{ request()->routeIs('admin.documents.*') ? 'text-white' : 'text-gray-300 group-hover:text-white' }}" viewBox="0 0 24 24">
                        <path fill="currentColor" fill-rule="evenodd" d="M9.29289 1.29289C9.48043 1.10536 9.73478 1 10 1H18C19.6569 1 21 2.34315 21 4V20C21 21.6569 19.6569 23 18 23H6C4.34315 23 3 21.6569 3 20V8C3 7.73478 3.10536 7.48043 3.29289 7.29289L9.29289 1.29289ZM18 3H11V8C11 8.55228 10.5523 9 10 9H5V20C5 20.5523 5.44772 21 6 21H18C18.5523 21 19 20.5523 19 20V4C19 3.44772 18.5523 3 18 3ZM6.41421 7H9V4.41421L6.41421 7ZM7 13C7 12.4477 7.44772 12 8 12H16C16.5523 12 17 12.4477 17 13C17 13.5523 16.5523 14 16 14H8C7.44772 14 7 13.5523 7 13ZM7 17C7 16.4477 7.44772 16 8 16H16C16.5523 16 17 16.4477 17 17C17 17.5523 16.5523 18 16 18H8C7.44772 18 7 17.5523 7 17Z"></path>
                    </svg>
                    <span>Document Requests</span>
                </a>
            </li>


<!-- Complaints -->
<li>
                <a href="{{ route('admin.complaints.index') }}" class="flex items-center py-3 px-4 rounded-lg font-semibold text-sm transition duration-200 group
                {{ request()->routeIs('admin.complaints.*') ? 'bg-white/20 text-white border-l-4 border-white' : 'text-gray-200 hover:bg-white/10' }}">
                    <svg class="w-9 h-9 mr-3 shrink-0 {{ request()->routeIs('admin.complaints.*') ? 'text-white' : 'text-gray-300 group-hover:text-white' }}" viewBox="0 0 24 24">
                        <path fill="currentColor" fill-rule="evenodd" d="M4 2H20C21.1 2 22 2.9 22 4V16C22 17.1 21.1 18 20 18H6L2 22V4C2 2.9 2.9 2 4 2ZM5.17 16H20V4H4V17.17L5.17 16ZM11 5H13V11H11V5ZM13 13H11V15H13V13Z"></path>
                    </svg>
                    <span>Complaints</span>
                </a>
            </li>


<!-- Staffs (superadmin only) -->
@if(Auth::user()->user_type === 'super admin')
<li>
    <a href="{{ route('admin.staffs.index') }}" class="flex items-center py-3 px-4 rounded-lg font-semibold text-sm transition duration-200 group
    {{ request()->routeIs('admin.staffs.*') ? 'bg-white/20 text-white border-l-4 border-white' : 'text-gray-200 hover:bg-white/10' }}">
        <svg class="w-9 h-9 mr-3 shrink-0 {{ request()->routeIs('admin.staffs.*') ? 'text-white' : 'text-gray-300 group-hover:text-white' }}" viewBox="0 0 474.565 474.565">
            <path fill="currentColor" d="M255.204,102.3c-0.606-11.321-12.176-9.395-23.465-9.395C240.078,95.126,247.967,98.216,255.204,102.3z"></path>
            <path fill="currentColor" d="M134.524,73.928c-43.825,0-63.997,55.471-28.963,83.37c11.943-31.89,35.718-54.788,66.886-63.826 C163.921,81.685,150.146,73.928,134.524,73.928z"></path>
            <path fill="currentColor" d="M43.987,148.617c1.786,5.731,4.1,11.229,6.849,16.438L36.44,179.459c-3.866,3.866-3.866,10.141,0,14.015l25.375,25.383 c1.848,1.848,4.38,2.888,7.019,2.888c2.61,0,5.125-1.04,7.005-2.888l14.38-14.404c2.158,1.142,4.55,1.842,6.785,2.827 c0-0.164-0.016-0.334-0.016-0.498c0-11.771,1.352-22.875,3.759-33.302c-17.362-11.174-28.947-30.57-28.947-52.715 c0-34.592,28.139-62.739,62.723-62.739c23.418,0,43.637,13.037,54.43,32.084c11.523-1.429,22.347-1.429,35.376,1.033 c-1.676-5.07-3.648-10.032-6.118-14.683l14.396-14.411c1.878-1.856,2.918-4.38,2.918-7.004c0-2.625-1.04-5.148-2.918-7.004 l-25.361-25.367c-1.94-1.941-4.472-2.904-7.003-2.904c-2.532,0-5.063,0.963-6.989,2.904l-14.442,14.411 c-5.217-2.764-10.699-5.078-16.444-6.825V9.9c0-5.466-4.411-9.9-9.893-9.9h-35.888c-5.451,0-9.909,4.434-9.909,9.9v20.359 c-5.73,1.747-11.213,4.061-16.446,6.825L75.839,22.689c-1.942-1.941-4.473-2.904-7.005-2.904c-2.531,0-5.077,0.963-7.003,2.896 L36.44,48.048c-1.848,1.864-2.888,4.379-2.888,7.012c0,2.632,1.04,5.148,2.888,7.004l14.396,14.403 c-2.75,5.218-5.063,10.708-6.817,16.438H23.675c-5.482,0-9.909,4.441-9.909,9.915v35.889c0,5.458,4.427,9.908,9.909,9.908H43.987z"></path>
            <path fill="currentColor" d="M354.871,340.654c15.872-8.705,26.773-25.367,26.773-44.703c0-28.217-22.967-51.168-51.184-51.168 c-9.923,0-19.118,2.966-26.975,7.873c-4.705,18.728-12.113,36.642-21.803,52.202C309.152,310.022,334.357,322.531,354.871,340.654z "></path>
            <path fill="currentColor" d="M460.782,276.588c0-5.909-4.799-10.693-10.685-10.693H428.14c-1.896-6.189-4.411-12.121-7.393-17.75l15.544-15.544 c2.02-2.004,3.137-4.721,3.137-7.555c0-2.835-1.118-5.553-3.137-7.563l-27.363-27.371c-2.08-2.09-4.829-3.138-7.561-3.138 c-2.734,0-5.467,1.048-7.547,3.138l-15.576,15.552c-5.623-2.982-11.539-5.481-17.751-7.369v-21.958 c0-5.901-4.768-10.685-10.669-10.685H311.11c-2.594,0-4.877,1.04-6.739,2.578c3.26,11.895,5.046,24.793,5.046,38.552 c0,8.735-0.682,17.604-1.956,26.423c7.205-2.656,14.876-4.324,22.999-4.324c36.99,0,67.086,30.089,67.086,67.07 c0,23.637-12.345,44.353-30.872,56.303c13.48,14.784,24.195,32.324,31.168,51.976c1.148,0.396,2.344,0.684,3.54,0.684 c2.733,0,5.467-1.04,7.563-3.13l27.379-27.371c2.004-2.004,3.106-4.721,3.106-7.555s-1.102-5.551-3.106-7.563l-15.576-15.552 c2.982-5.621,5.497-11.555,7.393-17.75h21.957c2.826,0,5.575-1.118,7.563-3.138c2.004-1.996,3.138-4.72,3.138-7.555 L460.782,276.588z"></path>
            <path fill="currentColor" d="M376.038,413.906c-16.602-48.848-60.471-82.445-111.113-87.018c-16.958,17.958-37.954,29.351-61.731,29.351 c-23.759,0-44.771-11.392-61.713-29.351c-50.672,4.573-94.543,38.17-111.145,87.026l-9.177,27.013 c-2.625,7.773-1.368,16.338,3.416,23.007c4.783,6.671,12.486,10.631,20.685,10.631h315.853c8.215,0,15.918-3.96,20.702-10.631 c4.767-6.669,6.041-15.234,3.4-23.007L376.038,413.906z"></path>
            <path fill="currentColor" d="M120.842,206.782c0,60.589,36.883,125.603,82.352,125.603c45.487,0,82.368-65.014,82.368-125.603 C285.563,81.188,120.842,80.939,120.842,206.782z"></path>
        </svg>
        <span>Staffs</span>
    </a>
</li>
@endif


<!-- Barangay Officials -->
<li>
                <a href="{{ route('admin.brgyOfficials.index') }}" class="flex items-center py-3 px-4 rounded-lg font-semibold text-sm transition duration-200 group
                {{ request()->routeIs('admin.brgyOfficials.*') ? 'bg-white/20 text-white border-l-4 border-white' : 'text-gray-200 hover:bg-white/10' }}">
                    <svg class="w-9 h-9 mr-3 shrink-0 {{ request()->routeIs('admin.brgyOfficials.*') ? 'text-white' : 'text-gray-300 group-hover:text-white' }}" viewBox="240 275 550 550">
                        <circle fill="currentColor" cx="435.2" cy="409.5" r="102.4"></circle>
                        <path fill="currentColor" d="M588.8 409.5c0 17.6-3.1 34.5-8.6 50.3 2.9.2 5.7.9 8.6.9 56.6 0 102.4-45.8 102.4-102.4 0-56.6-45.8-102.4-102.4-102.4-26.1 0-49.7 10.1-67.8 26.2 40.9 27.7 67.8 74.4 67.8 127.4zM435.2 563.1c-128 0-179.2 25.6-179.2 102.4v102.6h358.4V665.5c0-77.3-51.2-102.4-179.2-102.4z"></path>
                        <path fill="currentColor" d="M588.8 511.9c-14.5 0-27.9.4-40.5 1.1-2.3 2.5-4.6 4.9-7 7.2 63.7 13.5 124.2 49.5 124.2 145.3v51.4H768V614.3c0-77.3-51.2-102.4-179.2-102.4z"></path>
                    </svg>
                    <span>Barangay Officials</span>
                </a>
            </li>


<!-- Announcements -->
<li>
                <a href="{{ route('admin.announcements.index') }}" class="flex items-center py-3 px-4 rounded-lg font-semibold text-sm transition duration-200 group
                {{ request()->routeIs('admin.announcements.*') ? 'bg-white/20 text-white border-l-4 border-white' : 'text-gray-200 hover:bg-white/10' }}">
                    <svg class="w-9 h-9 mr-3 shrink-0 {{ request()->routeIs('admin.announcements.*') ? 'text-white' : 'text-gray-300 group-hover:text-white' }}" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M12,24H6v-7c-3.3,0-6-2.7-6-6s2.7-6,6-6h5c3.6,0,7.3-3.4,7.3-3.4L20,0v7c2.2,0,4,1.8,4,4s-1.8,4-4,4v6.8l-1.7-1.5 c0,0-3.1-2.8-6.3-3.3V24z M8,22h2v-5H8V22z M12,15.1c2.4,0.3,4.6,1.5,6,2.5V4.4c-1.4,1-3.6,2.3-6,2.6V15.1z M6,15h4V7H6 c-2.2,0-4,1.8-4,4S3.8,15,6,15z M20,11v2c1.1,0,2-0.9,2-2s-0.9-2-2-2V11z"></path>
                    </svg>
                    <span>Announcements</span>
                </a>
            </li>


<!-- Reports & Analytics -->
<li>
    <a href="{{ route('admin.reports.index') }}" class="flex items-center py-3 px-4 rounded-lg font-semibold text-sm transition duration-200 group
    {{ request()->routeIs('admin.reports.*') ? 'bg-white/20 text-white border-l-4 border-white' : 'text-gray-200 hover:bg-white/10' }}">
        <svg class="w-9 h-9 mr-3 shrink-0 {{ request()->routeIs('admin.reports.*') ? 'text-white' : 'text-gray-300 group-hover:text-white' }}" viewBox="0 0 24 24" fill="none">
            <path stroke="currentColor" stroke-width="1.5"
                d="M9 21H15M9 21V16M9 21H3.6C3.26863 21 3 20.7314 3 20.4V16.6C3 16.2686 3.26863 16 3.6 16H9M15 21V9M15 21H20.4C20.7314 21 21 20.7314 21 20.4V3.6C21 3.26863 20.7314 3 20.4 3H15.6C15.2686 3 15 3.26863 15 3.6V9M15 9H9.6C9.26863 9 9 9.26863 9 9.6V16">
            </path>
        </svg>
        <span>Reports & Analytics</span>
    </a>
</li>



<!-- Audit Logs -->
@if(Auth::user()->user_type === 'super admin')
<li>
                <a href="{{ route('admin.auditlogs.index') }}" class="flex items-center py-3 px-4 rounded-lg font-semibold text-sm transition duration-200 group
                {{ request()->routeIs('admin.auditlogs.*') ? 'bg-white/20 text-white border-l-4 border-white' : 'text-gray-200 hover:bg-white/10' }}">
                    <svg class="w-9 h-9 mr-3 shrink-0 {{ request()->routeIs('admin.auditlogs.*') ? 'text-white' : 'text-gray-300 group-hover:text-white' }}" viewBox="0 0 32 32">
                        <path fill="currentColor" d="M28,16v6H4V6H16V4H4A2,2,0,0,0,2,6V22a2,2,0,0,0,2,2h8v4H8v2H24V28H20V24h8a2,2,0,0,0,2-2V16ZM18,28H14V24h4Z"></path>
                        <polygon fill="currentColor" points="21 15 16 10.04 17.59 8.47 21 11.85 28.41 4.5 30 6.08 21 15"></polygon>
                    </svg>
                    <span>Audit Logs</span>
                </a>
            </li>
@endif


        </ul>

</aside>




<!-- MOBILE BACKDROP -->
        <label for="sidebar-toggle"
               class="fixed inset-0 bg-black/50 z-10 hidden peer-checked:block md:hidden"></label>

        <!-- MAIN CONTENT -->
        <main class="flex-1 overflow-y-auto md:ml-60 bg-[#FBF6F1] p-6">
            @yield('content')
        </main>

    </div>
</div>

@else
    @yield('content')
@endif

{{-- 🚀 START: NOTIFICATION JAVASCRIPT LOGIC --}}
<script>
    function notificationHandler() {
        return {
            open: false,
            notifications: [],
            unreadCount: 0,
            
            init() {
                // Initial fetch and start polling every 5 seconds
                this.fetchNotifications();
                setInterval(() => this.fetchNotifications(), 5000); 
            },

            toggleDropdown() {
                this.open = !this.open;
            },

            formatDate(dateString) {
                const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
                return new Date(dateString).toLocaleDateString(undefined, options);
            },

            fetchNotifications() {
                fetch("{{ route('admin.api.notifications.index') }}")
                    .then(response => response.json())
                    .then(data => {
                        // Merge unread and read, prioritizing unread
                        this.notifications = data.unread.concat(data.read);
                        this.unreadCount = data.unread_count;
                    })
                    .catch(error => console.error('Error fetching notifications:', error));
            },

            markAsRead(notificationId, redirectLink) {
                fetch(`/admin/api/notifications/${notificationId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Small delay to ensure server update is processed
                        setTimeout(() => {
                            // Refresh the notification list
                            this.fetchNotifications();
                            
                            // Redirect to the notification link after a brief pause
                            if (redirectLink) {
                                setTimeout(() => {
                                    window.location.href = redirectLink;
                                }, 500);
                            }
                        }, 100);
                    }
                })
                .catch(error => console.error('Error marking as read:', error));
            },
            
            markAllAsRead() {
                    // Mark all unread notifications as read
                    const unreadNotifications = this.notifications.filter(n => !n.read_at);
                
                    unreadNotifications.forEach(n => {
                        fetch(`/admin/api/notifications/${n.id}`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const index = this.notifications.findIndex(notif => notif.id === n.id);
                                if (index !== -1) {
                                    this.notifications[index].read_at = new Date().toISOString();
                                }
                            }
                        })
                        .catch(error => console.error('Error marking as read:', error));
                    });
                
                    // Set the badge to zero immediately for user experience
                    this.unreadCount = 0;
                    this.open = false;
            }
        }
    }
</script>
{{-- 🛑 END: NOTIFICATION JAVASCRIPT LOGIC --}}

</body>
</html>





