<script>
    function notificationHandler() {
        return {
            open: false,
            notifications: [],
            unreadCount: 0,
            init() {
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
                        setTimeout(() => {
                            this.fetchNotifications();
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
            }
        }
    }
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Details - {{ $user->first_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
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
    <link rel="stylesheet" href="{{ asset('css/modals.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animations.css') }}">
    <link rel="stylesheet" href="{{ asset('css/scrollbars.css') }}">
</head>
  <link href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
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
  <link rel="stylesheet" href="{{ asset('css/modals.css') }}">
  <link rel="stylesheet" href="{{ asset('css/animations.css') }}">
  <link rel="stylesheet" href="{{ asset('css/scrollbars.css') }}">
</head>

<body class="bg-gray-100" style="font-family: 'Poppins', sans-serif;">


<nav id="top-navbar" class="fixed top-0 left-0 w-full h-16 sm:h-20 font-poppins bg-gradient-to-r from-[#134573] via-[#0f3a5f] to-[#0a2847] text-white shadow-lg z-30 flex items-center justify-between px-3 sm:px-6 border-b border-white/10">
    <div class="flex items-center gap-2 sm:gap-4">
        <label for="sidebar-toggle" class="cursor-pointer md:hidden p-2 rounded-lg hover:bg-white/10 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
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
    <div class="flex items-center gap-3 sm:gap-6">
        <div x-data="notificationHandler()" x-init="init" @click.away="open = false" class="relative">
            <button @click="toggleDropdown" class="relative p-1.5 sm:p-2 rounded-lg hover:bg-white/10 transition duration-200">
                <span class="sr-only">View notifications</span>
                <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5.982-6H12a6 6 0 00-5 5.917V14.158a2.032 2.032 0 01-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute top-0 right-0 block h-4 w-4 sm:h-5 sm:w-5 text-xs text-center rounded-full bg-red-500 ring-2 ring-white font-bold flex items-center justify-center"></span>
            </button>
            <div x-show="open" class="absolute right-0 mt-2 w-[calc(100vw-1.5rem)] sm:w-96 max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden z-50 text-slate-800 border-2 border-gray-100" x-cloak style="display: none;">
                <div class="px-4 sm:px-6 py-3 sm:py-4 rounded-t-2xl" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
                    <div class="flex justify-between items-center">
                        <h3 class="text-base sm:text-lg font-bold text-white">Notifications</h3>
                        <button @click="markAllAsRead" x-show="unreadCount > 0" class="text-xs text-blue-100 hover:text-white transition-colors font-semibold">Mark all as read</button>
                    </div>
                </div>
                <div class="max-h-[60vh] sm:max-h-96 overflow-y-auto divide-y divide-gray-100">
                    <template x-if="notifications.length > 0">
                        <template x-for="notification in notifications" :key="notification.id">
                            <a :href="notification.data.link" @click.prevent="markAsRead(notification.id, notification.data.link)" class="block px-5 py-4 transition-all duration-200" :class="{'bg-blue-50/70 hover:bg-blue-50 border-l-4 border-blue-500': !notification.read_at, 'hover:bg-gray-50': notification.read_at}">
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
                                        <p class="text-sm font-semibold" :class="{'text-gray-900': !notification.read_at, 'text-gray-600': notification.read_at}" x-text="notification.data.title"></p>
                                        <p class="text-xs mt-1" :class="{'text-gray-700': !notification.read_at, 'text-gray-500': notification.read_at}" x-text="notification.data.message"></p>
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
        <div class="flex items-center pl-3 sm:pl-6 border-l border-blue-300/30 h-6 sm:h-8">
            <div class="text-right mr-2 sm:mr-3 hidden sm:block">
                <p class="text-sm sm:text-md font-bold text-white leading-none">Super Admin</p>
                <p class="text-xs text-blue-200 font-medium mt-1">Super Administrator</p>
            </div>
            <div x-data="{ open: false }" @click.away="open = false" class="relative">
                <button @click="open = !open" class="h-8 w-8 sm:h-10 sm:w-10 rounded-full overflow-hidden bg-white/10 border border-white/40 flex items-center justify-center hover:bg-white/20 focus:outline-none transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </button>
                <div x-show="open" x-cloak style="display: none;" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-2xl z-50 overflow-hidden border-2 border-gray-100">
                    <a href="{{ route('admin.profile') }}" class="block w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 transition duration-200 flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        View Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition duration-200 flex items-center gap-2 border-t border-gray-100">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<main id="main-content" class="grow flex px-12 py-6 gap-8 text-[#1e2e3d] text-[1.05rem] pt-24 max-w-7xl mx-auto w-full overflow-y-auto bg-gradient-to-br from-gray-50 to-gray-100">


    @if(session('success'))
        <!-- Modern Success Modal -->
        <div id="successModal" class="fixed inset-0 flex items-center justify-center z-[9999] bg-black/40 backdrop-blur-sm">
            <div class="relative bg-gradient-to-br from-white via-[#e0f7fa] to-[#dbeafe] w-full max-w-md rounded-3xl shadow-2xl p-10 text-center border-4 border-[#A2C4D9] animate-fade-in-up">
                <div class="flex justify-center mb-6">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-100 to-blue-300 border-4 border-blue-400 flex items-center justify-center shadow-lg animate-bounce-in">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-blue-600 animate-pulse" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                            <circle cx="12" cy="12" r="10" class="text-blue-200" fill="currentColor" opacity="0.2" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                <h2 class="font-extrabold text-2xl mb-2 text-blue-700 tracking-wide animate-fade-in">Success!</h2>
                <p class="text-base text-gray-700 mb-6 animate-fade-in-slow">{{ session('success') }}</p>
                <button onclick="window.location.href='/admin/users'" class="w-full bg-gradient-to-r from-[#A2C4D9] to-[#94B8CC] hover:from-[#94B8CC] hover:to-[#A2C4D9] text-black font-bold py-3 rounded-2xl text-base shadow-md transition-all duration-200 animate-fade-in">CLOSE</button>
                <span class="absolute top-3 right-5 text-gray-400 cursor-pointer text-2xl hover:text-gray-600 transition" onclick="document.getElementById('successModal').remove();document.body.style.overflow='auto';">&times;</span>
            </div>
            <style>
                @keyframes fade-in-up { from { opacity: 0; transform: translateY(40px);} to { opacity: 1; transform: translateY(0);} }
                .animate-fade-in-up { animation: fade-in-up 0.5s cubic-bezier(.4,0,.2,1) both; }
                @keyframes bounce-in { 0% { transform: scale(0.7);} 60% { transform: scale(1.1);} 80% { transform: scale(0.95);} 100% { transform: scale(1);} }
                .animate-bounce-in { animation: bounce-in 0.7s cubic-bezier(.4,0,.2,1) both; }
                @keyframes fade-in { from { opacity: 0;} to { opacity: 1;} }
                .animate-fade-in { animation: fade-in 0.7s 0.2s both; }
                .animate-fade-in-slow { animation: fade-in 1.2s 0.4s both; }
                .animate-pulse { animation: pulse 1.5s infinite; }
                @keyframes pulse { 0%, 100% { opacity: 1;} 50% { opacity: 0.5;} }
            </style>
        </div>
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            document.body.style.overflow = 'hidden';
          });
        </script>
    @endif

    <section class="w-1/2 flex flex-col pr-4">
      <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border border-gray-100">
      @php
        $rawPhotoPath = $user->photo_path;
        $normalizedPhotoPath = $rawPhotoPath
            ? preg_replace('#^(public/|storage/)#', '', ltrim($rawPhotoPath, '/'))
            : null;
        $photoUrl = $normalizedPhotoPath
            ? (preg_match('#^https?://#', $normalizedPhotoPath)
                ? $normalizedPhotoPath
                : asset('storage/' . $normalizedPhotoPath))
            : 'https://ui-avatars.com/api/?name=' . urlencode($user->first_name . ' ' . $user->last_name);
      @endphp
      <div class="flex items-center gap-6 mb-4">
        <div class="w-40 h-36 border-4 border-blue-200 rounded-2xl flex items-center justify-center relative overflow-hidden bg-white shadow-md">

            <img
                src="{{ $photoUrl }}"
                alt="Profile Image"
                class="w-full h-full object-cover"
                onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=User';"
            >

        </div>
        <div class="text-left">
                    <h3 class="text-3xl font-bold text-[#1e2e3d]">{{ $user->first_name }} {{ $user->last_name }}</h3>
                    <p class="text-gray-600 text-base font-semibold">{{ $user->resident_id ?? 'N/A' }}</p>

                    @php
                        $status = strtolower($user->status ?? 'pending');
                        $statusColor = match($status) {
                                'approved' => 'bg-emerald-100 text-emerald-800 border border-emerald-300',
                                'reject'   => 'bg-red-100 text-red-800 border border-red-300',
                                'archive'  => 'bg-gray-100 text-gray-800 border border-gray-300',
                                default    => 'bg-amber-100 text-amber-800 border border-amber-300', // Pending or others
                        };
                        $statusLabel = ucfirst($status);
                @endphp
                <span class="inline-block mt-2 px-3 py-1 text-xs font-bold border rounded-full {{ $statusColor }}">
                        {{ $statusLabel }}
                </span>
        </div>
      </div>

      </div>
      </div>
      
      <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
      <h2 class="text-xl font-bold mb-6 text-gray-800">Personal Information</h2>

      <div class="space-y-6">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-2">Last Name</label>
                <div class="flex items-center bg-gray-50 text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-200">{{ $user->last_name }}</div>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-2">First Name</label>
                <div class="flex items-center bg-gray-50 text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-200">{{ $user->first_name }}</div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-2">Middle Name</label>
                <div class="flex items-center bg-gray-50 text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-200">{{ $user->middle_name ?? 'N/A' }}</div>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-2">Suffix</label>
                <div class="flex items-center bg-gray-50 text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-200">{{ $user->suffix ?? 'N/A' }}</div>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-semibold mb-2">Age</label>
            <div class="flex items-center bg-gray-50 text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-200">{{ $user->age }}</div>
          </div>
          <div>
            <label class="block text-sm font-semibold mb-2">Date of Birth</label>
            <div class="flex items-center bg-gray-50 text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-200">{{ \Carbon\Carbon::parse($user->birthdate)->format('m/d/Y') }}</div>
          </div>
          <div>
            <label class="block text-sm font-semibold mb-2">Place of Birth</label>
            <div class="flex items-center bg-gray-50 text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-200">{{ $user->place_of_birth }}</div>
          </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-semibold mb-2">Gender</label>
            <div class="flex items-center bg-gray-50 text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-200">{{ $user->gender }}</div>
          </div>
          <div>
            <label class="block text-sm font-semibold mb-2">Civil Status</label>
            <div class="flex items-center bg-gray-50 text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-200">{{ $user->civil_status }}</div>
          </div>
          <div>
            <label class="block text-sm font-semibold mb-2">Citizenship</label>
            <div class="flex items-center bg-gray-50 text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-200">{{ $user->citizenship ?? 'Filipino' }}</div>
          </div>
        </div>
      </div>
      </div>
    </section>

    <div class="w-px bg-gray-300 h-full"></div>

    <section class="w-1/2 flex flex-col pl-4">
      <div class="bg-white rounded-2xl shadow-lg p-7 mb-6 border border-gray-100">
      <h2 class="text-xl font-bold mb-5 text-gray-800">Contact Information</h2>

      <div class="flex gap-5 mb-5">
        <div class="w-full">
            <p class="text-xs font-bold mb-2">ID/CERTIFICATE OF LIVE BIRTH ATTACHMENT</p>
            <div class="flex gap-5">
                <div class="w-1/2">
                    <div class="w-full h-36 border-2 border-blue-200 rounded-xl flex items-center justify-center overflow-hidden bg-white shadow-sm">
                        <img src="{{ $user->id_front_path ? asset('storage/' . $user->id_front_path) : '' }}" alt="Valid ID Front" class="w-full h-full object-cover">
                    </div>
                </div>
                <div class="w-1/2">
                    <div class="w-full h-36 border-2 border-blue-200 rounded-xl flex items-center justify-center overflow-hidden bg-white shadow-sm">
                        <img src="{{ $user->id_back_path ? asset('storage/' . $user->id_back_path) : '' }}" alt="Valid ID Back" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
      </div>

      <div class="space-y-5">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Contact Number</label>
                        <div class="flex items-center bg-gray-50 text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-200">{{ $user->contact_number }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">Email Address</label>
                        <div class="flex items-center bg-gray-50 text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-200">{{ $user->email }}</div>
                    </div>
                </div>

                <div>
                        <label class="block text-sm font-semibold mb-2">House/Unit Number, Street</label>
                        <div class="flex items-center bg-gray-50 text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-200">{{ $user->address }}</div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                        <div>
                                <label class="block text-sm font-semibold mb-2">Barangay</label>
                                <div class="flex items-center bg-gray-50 text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-200">Daang Bakal</div>
                        </div>
                        <div>
                                <label class="block text-sm font-semibold mb-2">City/Municipality</label>
                                <div class="flex items-center bg-gray-50 text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-200">Mandaluyong</div>
                        </div>
                </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Location Map:</label>
            <div class="relative">
                <iframe
                    id="locationMap"
                    data-zoom="17"
                    class="w-full rounded-xl border-2 border-gray-200 z-0 shadow-sm"
                    style="height: 120px;"
                    src="https://maps.google.com/maps?q={{ urlencode(trim(($user->address ?? '') . ', Brgy. Daang Bakal, Mandaluyong City', ', ')) }}&t=&z=17&ie=UTF8&iwloc=&output=embed"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                ></iframe>
                <div class="absolute top-2 right-2 flex flex-col gap-2">
                    <button type="button" onclick="zoomMap(1)" class="w-7 h-7 rounded-full bg-white border border-gray-300 text-gray-700 text-sm font-bold shadow hover:bg-gray-50">+</button>
                    <button type="button" onclick="zoomMap(-1)" class="w-7 h-7 rounded-full bg-white border border-gray-300 text-gray-700 text-sm font-bold shadow hover:bg-gray-50">−</button>
                </div>
            </div>
        </div>
      </div>
      </div>

            <div class="flex justify-end gap-3 pb-3 mr-6 mt-1">
                <a href="{{ url()->previous() }}"
                     class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-2.5 rounded-xl transition-all duration-200 text-sm flex items-center border border-gray-300 hover:shadow-md">
                     Close
                </a>
                <a href="{{ route('admin.users.edit', $user->id) }}"
                     class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold px-6 py-2.5 rounded-xl transition-all duration-200 text-sm flex items-center gap-2 hover:shadow-lg transform hover:scale-105">
                     <i data-lucide="edit" class="w-4 h-4"></i> Edit Information
                </a>
            </div>
    </section>
</main>

<div id="approveModal" class="modal-container hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] flex items-center justify-center z-[9999] pointer-events-none">
    <div class="bg-white w-[450px] rounded-3xl shadow-2xl overflow-hidden border-2 border-gray-100 relative animate-fade-in-up pointer-events-auto">
        {{-- Content Body --}}
        <div class="px-8 py-8 bg-white text-center">
            <!-- Icon Badge -->
            <div class="flex justify-center mb-6">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-50 to-blue-100 border-4 border-blue-500 flex items-center justify-center shadow-lg">
                    <svg class="w-12 h-12 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <h2 class="font-bold text-2xl mb-4 text-gray-800 tracking-tight">Confirm Approval?</h2>
            <div class="bg-blue-50 rounded-xl p-4 mb-6 border border-blue-200">
                <p class="text-sm text-gray-700 text-center">
                    Approve <span class="font-bold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</span> to access the system. 
                    They will be notified via email.
                </p>
            </div>
            <form action="{{ route('admin.users.accept', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="flex gap-3">
                    <button 
                        type="button" 
                        onclick="closeModal('approveModal')" 
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-xl font-bold text-sm transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                        CANCEL
                    </button>
                    <button 
                        type="submit" 
                        class="flex-1 bg-gradient-to-r from-blue-600 to-blue-400 hover:from-blue-700 hover:to-blue-500 text-white px-6 py-3 rounded-xl font-bold text-sm transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                        APPROVE
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="archiveModal" class="modal-container hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] flex items-center justify-center z-[9999] pointer-events-none">
    <div class="bg-white w-[450px] rounded-3xl shadow-2xl overflow-hidden border-2 border-gray-100 relative animate-fade-in-up pointer-events-auto">
        
        {{-- Content Body --}}
        <div class="px-8 py-8 bg-white text-center">
            <!-- Icon Badge -->
            <div class="flex justify-center mb-6">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-gray-50 to-gray-100 border-4 border-gray-400 flex items-center justify-center shadow-lg">
                    <svg class="w-12 h-12 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
            </div>
            
            <h2 class="font-bold text-2xl mb-4 text-gray-800 tracking-tight">Confirm Archive?</h2>
            
            <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-200">
                <p class="text-sm text-gray-700 text-center">
                    This will archive <span class="font-bold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</span>'s account. 
                    All data will be preserved and can be restored later.
                </p>
            </div>

            <form action="{{ route('admin.users.archive', $user->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex gap-3">
                    <button 
                        type="button" 
                        onclick="closeModal('archiveModal')" 
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-xl font-bold text-sm transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                        CANCEL
                    </button>
                    <button 
                        type="submit" 
                        class="flex-1 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-6 py-3 rounded-xl font-bold text-sm transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                        ARCHIVE
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="rejectModal" class="modal-container hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] flex items-center justify-center z-[9999] pointer-events-none">
    <div class="bg-white w-[450px] rounded-3xl shadow-2xl overflow-hidden border-2 border-gray-100 relative animate-fade-in-up pointer-events-auto">
        
        {{-- Content Body --}}
        <div class="px-8 py-8 bg-white text-center">
            <!-- Icon Badge -->
            <div class="flex justify-center mb-6">
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-red-50 to-red-100 border-4 border-red-500 flex items-center justify-center shadow-lg">
                    <svg class="w-12 h-12 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 9l6 6m0-6l-6 6" />
                    </svg>
                </div>
            </div>
            
            <h2 class="font-bold text-2xl mb-4 text-gray-800 tracking-tight">Reject Registration</h2>
            
            <div class="bg-red-50 rounded-xl p-4 mb-6 border border-red-200">
                <p class="text-sm text-gray-700 text-center">
                    You are about to reject the registration of <span class="font-bold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</span>. 
                    They will be notified via email.
                </p>
            </div>

            <form action="{{ route('admin.users.reject', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4 text-left">
                    <label for="reason" class="block text-sm font-semibold text-gray-700 mb-2">Reason (optional)</label>
                    <textarea 
                        id="reason" 
                        name="reason" 
                        rows="3"
                        placeholder="Provide a reason for rejection..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                    </textarea>
                </div>
                <div class="flex gap-3">
                    <button 
                        type="button" 
                        onclick="closeModal('rejectModal')" 
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-xl font-bold text-sm transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                        CANCEL
                    </button>
                    <button 
                        type="submit" 
                        class="flex-1 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-6 py-3 rounded-xl font-bold text-sm transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                        REJECT
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Initialize Lucide Icons
    lucide.createIcons();

    const backdrop = document.getElementById('modal-backdrop');
    if (backdrop && backdrop.parentElement !== document.body) {
        document.body.appendChild(backdrop);
    }

    function showBackdrop() {
        if (backdrop) backdrop.classList.remove('hidden');
    }

    function hideBackdrop() {
        if (backdrop) backdrop.classList.add('hidden');
    }

    // Modal Logic
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
        }
        showBackdrop();
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }
        hideBackdrop();
    }

    function zoomMap(delta) {
        const iframe = document.getElementById('locationMap');
        if (!iframe) return;
        const current = parseInt(iframe.dataset.zoom || '17', 10);
        const next = Math.min(19, Math.max(12, current + delta));
        if (next === current) return;
        iframe.dataset.zoom = String(next);
        const baseQuery = `{{ urlencode(trim(($user->address ?? '') . ', Brgy. Daang Bakal, Mandaluyong City', ', ')) }}`;
        iframe.src = `https://maps.google.com/maps?q=${baseQuery}&t=&z=${next}&ie=UTF8&iwloc=&output=embed`;
    }

    // Auto-hide success message after 5 seconds
    document.addEventListener('DOMContentLoaded', () => {
        const successAlert = document.querySelector('.animate-fade-in-down');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.opacity = '0';
                successAlert.style.transform = 'translateY(-20px)';
                successAlert.style.transition = 'all 0.3s ease-out';
                setTimeout(() => successAlert.remove(), 300);
            }, 5000);
        }
    });
</script>

{{-- Shared Backdrop for All Modals --}}
<div id="modal-backdrop" class="hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] bg-black/50 backdrop-blur-sm z-[9998]"></div>

</body>
</html>
