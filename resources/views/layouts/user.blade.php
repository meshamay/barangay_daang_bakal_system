<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'User Panel' }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    <link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dropdowns.css') }}">

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

<body class="font-poppins bg-gray-100 text-slate-800 h-screen overflow-auto sm:overflow-auto md:overflow-hidden pt-16 sm:pt-20">

<nav id="top-navbar" class="fixed top-0 left-0 w-full h-16 sm:h-20 font-barlow bg-gradient-to-r from-[#134573] via-[#0f3a5f] to-[#0a2847] text-white shadow-lg z-30 flex items-center justify-between px-3 sm:px-6 border-b border-white/10">

    <div class="flex items-center gap-2 sm:gap-4">

        <div class="flex items-center space-x-2 sm:space-x-3">
            <a href="{{ route('home') }}" class="flex items-center space-x-2 sm:space-x-3">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b7/Mandaluyong_seal.svg/1024px-Mandaluyong_seal.svg.png" class="w-8 h-8 sm:w-12 sm:h-12">
                <img src="https://tse2.mm.bing.net/th/id/OIP._bP7eQwOSrZjwv-doDDsWAHaHa?rs=1&pid=ImgDetMain&o=7&rm=3" class="w-8 h-8 sm:w-12 sm:h-12 rounded-full object-cover">

                <div>
                    <h1 class="text-xs sm:text-lg md:text-xl font-semibold leading-tight">Barangay Daang Bakal</h1>
                    <p class="text-xs sm:text-base md:text-lg font-semibold leading-tight">Mandaluyong City</p>
                </div>
            </a>
        </div>

    </div>

    <div class="flex items-center gap-3 sm:gap-6">

        {{-- 🚀 START: NOTIFICATION BELL (Updated Alpine Component) --}}
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
                               class="block px-3 sm:px-5 py-3 sm:py-4 transition-all duration-200"
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
        {{-- 🛑 END: NOTIFICATION BELL --}}

        <div class="flex items-center pl-3 sm:pl-6 border-l border-blue-300/30 h-6 sm:h-8">

            <div class="text-right mr-2 sm:mr-3 hidden sm:block">
                <p class="text-sm sm:text-md font-bold text-white leading-none">
                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                </p>
                <p class="text-xs text-blue-200 font-medium mt-1">
                    {{ ucfirst(Auth::user()->role ?? 'Resident') }}
                </p>
            </div>

            <div x-data="{ profileOpen: false }" @click.away="profileOpen = false" class="relative">
                <button @click="profileOpen = !profileOpen" class="h-8 w-8 sm:h-10 sm:w-10 rounded-full overflow-hidden bg-white/10 border border-white/40 flex items-center justify-center hover:bg-white/20 focus:outline-none transition">
                    @if(Auth::user()->photo_path)
                        <img src="{{ asset('storage/'.Auth::user()->photo_path) }}" class="h-full w-full object-cover">
                    @else
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    @endif
                </button>

                <!-- Profile Dropdown Menu -->
                <div x-show="profileOpen" class="absolute right-0 mt-2 w-44 sm:w-48 bg-white rounded-lg shadow-xl overflow-hidden z-50" style="display: none;">
                    <a href="{{ route('user.profile') }}" class="block px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base text-gray-700 hover:bg-gray-100 transition flex items-center gap-2 border-b border-gray-200">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        View profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base text-red-600 hover:bg-red-50 transition flex items-center gap-2">
                            <svg class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Sign out
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</nav>

@auth
    {{-- This is for regular logged-in users (residents) --}}
    <main class="h-auto sm:h-auto md:h-[calc(100vh-5rem)] overflow-auto md:overflow-hidden max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6 sm:space-y-8">
        @yield('content')
    </main>
@endauth

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
                fetch("{{ route('api.notifications.index') }}")
                    .then(response => response.json())
                    .then(data => {
                        // Merge unread and read, prioritizing unread
                        this.notifications = data.unread.concat(data.read);
                        this.unreadCount = data.unread_count;
                    })
                    .catch(error => console.error('Error fetching notifications:', error));
            },

            markAsRead(notificationId, redirectLink) {
                fetch(`/api/notifications/${notificationId}`, {
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
                
                if (unreadNotifications.length === 0) {
                    this.open = false;
                    return;
                }

                unreadNotifications.forEach(n => {
                    fetch(`/api/notifications/${n.id}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .catch(error => console.error('Error marking as read:', error));
                });

                // Refresh notifications after a brief delay
                setTimeout(() => {
                    this.fetchNotifications();
                }, 500);
            }
        }
    }
</script>
{{-- 🛑 END: NOTIFICATION JAVASCRIPT LOGIC --}}

</body>
</html>