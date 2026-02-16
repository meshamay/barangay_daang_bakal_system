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

<body class="bg-gray-100" style="font-family: 'Poppins', sans-serif;">

<nav id="top-navbar" class="fixed top-0 left-0 w-full h-20 font-barlow text-white shadow-lg z-30 flex items-center justify-between px-8 border-b border-white/10" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
    <div class="flex items-center gap-4">
        <div class="flex items-center space-x-4">
             <a href="{{ route('admin.users.index') }}" class="cursor-pointer md:hidden text-white hover:text-blue-200 transition-colors duration-200 p-2 hover:bg-white/10 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
             </a>
             <div class="flex items-center gap-3">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b7/Mandaluyong_seal.svg/1024px-Mandaluyong_seal.svg.png" alt="Mandaluyong Seal" class="w-14 h-14 drop-shadow-lg">
                <img src="https://tse2.mm.bing.net/th/id/OIP._bP7eQwOSrZjwv-doDDsWAHaHa?rs=1&pid=ImgDetMain&o=7&rm=3" alt="Barangay Seal" class="w-14 h-14 rounded-full object-cover drop-shadow-lg ring-2 ring-white/30">
             </div>
             <div class="border-l-2 border-white/30 pl-4 ml-2">
                <h1 class="text-lg font-bold text-white">Barangay Daang Bakal</h1>
                <p class="text-sm font-medium text-white/90">Mandaluyong City</p>
             </div>
        </div>
    </div>
</nav>

<main id="main-content" class="grow flex px-12 py-6 gap-8 text-[#1e2e3d] text-[1.05rem] pt-24 max-w-7xl mx-auto w-full overflow-y-auto bg-gradient-to-br from-gray-50 to-gray-100">

    @if(session('success'))
        <div class="fixed top-24 right-8 bg-green-500 text-white px-6 py-4 rounded-xl shadow-2xl z-50 animate-fade-in-down flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    <section class="w-1/2 flex flex-col pr-4">
      <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border border-gray-100">
      <div class="flex items-center gap-6 mb-4">
        <div class="w-40 h-36 border-4 border-blue-200 rounded-2xl flex items-center justify-center relative overflow-hidden bg-white shadow-md">

            <img
                src="{{ $user->photo_path ? asset('storage/' . $user->photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($user->first_name.'+'.$user->last_name) }}"
                alt="Profile Image"
                class="w-full h-full object-cover"
                onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=User';"
            >

        </div>
        <div class="text-left">
          <h3 class="text-3xl font-bold text-[#1e2e3d]">{{ $user->first_name }} {{ $user->last_name }}</h3>
          <p class="text-gray-600 text-base font-semibold">{{ $user->resident_id ?? 'N/A' }}</p>

          @php
            $statusColor = match(strtolower($user->status)) {
                'approved' => 'bg-green-100 text-green-800 border-green-200',
                'archived' => 'bg-gray-100 text-gray-800 border-gray-200',
                'reject'   => 'bg-red-100 text-red-800 border-red-200',
                default    => 'bg-orange-100 text-orange-800 border-orange-200', // Pending
            };
          @endphp
          <span class="inline-block mt-2 px-3 py-1 text-xs font-bold border rounded-full {{ $statusColor }}">
            {{ strtoupper($user->status) }}
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
                <label class="block text-sm font-semibold mb-2">First Name</label>
                <div class="flex items-center bg-gray-50 text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-200">{{ $user->first_name }}</div>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-2">Last Name</label>
                <div class="flex items-center bg-gray-50 text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-200">{{ $user->last_name }}</div>
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
            <label class="block text-sm font-semibold mb-2">Birthdate</label>
            <div class="flex items-center bg-gray-50 text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-200">{{ \Carbon\Carbon::parse($user->birthdate)->format('m/d/Y') }}</div>
          </div>
          <div>
            <label class="block text-sm font-semibold mb-2">Birth Place</label>
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
        <div class="w-1/2">
            <p class="text-xs font-bold mb-2">ID Front</p>
            <div class="w-full h-36 border-2 border-blue-200 rounded-xl flex items-center justify-center overflow-hidden bg-white shadow-sm">
                <img src="{{ $user->id_front_path ? asset('storage/' . $user->id_front_path) : '' }}" alt="Valid ID Front" class="w-full h-full object-cover">
            </div>
        </div>
        <div class="w-1/2">
            <p class="text-xs font-bold mb-2">ID Back</p>
            <div class="w-full h-36 border-2 border-blue-200 rounded-xl flex items-center justify-center overflow-hidden bg-white shadow-sm">
                <img src="{{ $user->id_back_path ? asset('storage/' . $user->id_back_path) : '' }}" alt="Valid ID Back" class="w-full h-full object-cover">
            </div>
        </div>
      </div>

      <div class="space-y-5">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold mb-2">Contact No.</label>
            <div class="flex items-center bg-gray-50 text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-200">{{ $user->contact_number }}</div>
          </div>
          <div>
            <label class="block text-sm font-semibold mb-2">Email Address</label>
            <div class="flex items-center bg-gray-50 text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-200">{{ $user->email }}</div>
          </div>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Address</label>
            <div class="flex items-center bg-gray-50 text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-200">{{ $user->address }}</div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-2">City/Municipality</label>
                <div class="flex items-center bg-gray-50 text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-200">Mandaluyong</div>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-2">Barangay</label>
                <div class="flex items-center bg-gray-50 text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-200">Daang Bakal</div>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Location Map:</label>
            <div id="map" class="w-full rounded-xl border-2 border-gray-200 z-0 shadow-sm" style="height: 120px;"></div>
        </div>
      </div>
      </div>

      <div class="mt-3 flex justify-end gap-3 pb-3 mr-6">

        <a href="{{ route('admin.users.index') }}"
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
                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-green-50 to-green-100 border-4 border-green-500 flex items-center justify-center shadow-lg">
                    <svg class="w-12 h-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            
            <h2 class="font-bold text-2xl mb-4 text-gray-800 tracking-tight">Confirm Approval?</h2>
            
            <div class="bg-green-50 rounded-xl p-4 mb-6 border border-green-200">
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
                        class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-3 rounded-xl font-bold text-sm transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
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

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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

    // Initialize Map
    document.addEventListener('DOMContentLoaded', () => {
        // Use user coordinates or default to Mandaluyong Hall
        const lat = {{ $user->latitude ?? 14.5794 }};
        const lng = {{ $user->longitude ?? 121.0359 }};

        const map = L.map('map').setView([lat, lng], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        L.marker([lat, lng]).addTo(map);

        // Disable zoom/scroll for display only
        map.dragging.disable();
        map.touchZoom.disable();
        map.doubleClickZoom.disable();
        map.scrollWheelZoom.disable();
    });

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
