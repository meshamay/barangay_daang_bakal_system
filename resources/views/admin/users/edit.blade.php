<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Resident - {{ $user->first_name }}</title>
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
</head>

<body class="bg-gray-100" style="font-family: 'Poppins', sans-serif;">

<nav id="top-navbar" class="fixed top-0 left-0 w-full h-20 font-barlow text-white shadow-lg z-30 flex items-center justify-between px-8 border-b border-white/10" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
    <div class="flex items-center gap-4">
        <div class="flex items-center space-x-4">
             <a href="{{ route('admin.users.show', $user->id) }}" class="cursor-pointer md:hidden text-white hover:text-blue-200 transition-colors duration-200 p-2 hover:bg-white/10 rounded-lg">
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

<form action="{{ route('admin.users.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')

<main id="main-content" class="grow flex px-12 py-6 gap-8 text-[#1e2e3d] text-[1.05rem] pt-24 max-w-7xl mx-auto h-screen overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">

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

      {{-- Display validation errors --}}
      @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h3 class="text-sm font-semibold text-red-800 mb-1">There were errors with your submission</h3>
                    <ul class="list-disc list-inside text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
      @endif

      <div class="space-y-6">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-2">First Name <span class="text-red-500">*</span></label>
                <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" 
                    class="w-full bg-white text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all @error('first_name') border-red-500 @enderror" 
                    required>
                @error('first_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold mb-2">Last Name <span class="text-red-500">*</span></label>
                <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" 
                    class="w-full bg-white text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all @error('last_name') border-red-500 @enderror" 
                    required>
                @error('last_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-2">Middle Name</label>
                <input type="text" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}" 
                    class="w-full bg-white text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all @error('middle_name') border-red-500 @enderror">
                @error('middle_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold mb-2">Suffix</label>
                <input type="text" name="suffix" value="{{ old('suffix', $user->suffix) }}" 
                    class="w-full bg-white text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all @error('suffix') border-red-500 @enderror">
                @error('suffix')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-semibold mb-2">Age <span class="text-red-500">*</span></label>
            <input type="number" name="age" value="{{ old('age', $user->age) }}" min="1" max="120"
                class="w-full bg-white text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all @error('age') border-red-500 @enderror" 
                required>
            @error('age')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label class="block text-sm font-semibold mb-2">Birthdate <span class="text-red-500">*</span></label>
            <input type="date" name="birthdate" value="{{ old('birthdate', $user->birthdate) }}" 
                class="w-full bg-white text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all @error('birthdate') border-red-500 @enderror" 
                required>
            @error('birthdate')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label class="block text-sm font-semibold mb-2">Birth Place <span class="text-red-500">*</span></label>
            <input type="text" name="place_of_birth" value="{{ old('place_of_birth', $user->place_of_birth) }}" 
                class="w-full bg-white text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all @error('place_of_birth') border-red-500 @enderror" 
                required>
            @error('place_of_birth')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-semibold mb-2">Gender <span class="text-red-500">*</span></label>
            <select name="gender" 
                class="w-full bg-white text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all @error('gender') border-red-500 @enderror" 
                required>
                <option value="">Select Gender</option>
                <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female</option>
            </select>
            @error('gender')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label class="block text-sm font-semibold mb-2">Civil Status <span class="text-red-500">*</span></label>
            <select name="civil_status" 
                class="w-full bg-white text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all @error('civil_status') border-red-500 @enderror" 
                required>
                <option value="">Select Status</option>
                <option value="Single" {{ old('civil_status', $user->civil_status) == 'Single' ? 'selected' : '' }}>Single</option>
                <option value="Married" {{ old('civil_status', $user->civil_status) == 'Married' ? 'selected' : '' }}>Married</option>
                <option value="Widowed" {{ old('civil_status', $user->civil_status) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                <option value="Separated" {{ old('civil_status', $user->civil_status) == 'Separated' ? 'selected' : '' }}>Separated</option>
                <option value="Divorced" {{ old('civil_status', $user->civil_status) == 'Divorced' ? 'selected' : '' }}>Divorced</option>
            </select>
            @error('civil_status')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label class="block text-sm font-semibold mb-2">Citizenship <span class="text-red-500">*</span></label>
            <input type="text" name="citizenship" value="{{ old('citizenship', $user->citizenship ?? 'Filipino') }}" 
                class="w-full bg-white text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all @error('citizenship') border-red-500 @enderror" 
                required>
            @error('citizenship')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
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
            <label class="block text-sm font-semibold mb-2">Contact No. <span class="text-red-500">*</span></label>
            <input type="text" name="contact_number" value="{{ old('contact_number', $user->contact_number) }}" 
                class="w-full bg-white text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all @error('contact_number') border-red-500 @enderror" 
                required>
            @error('contact_number')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label class="block text-sm font-semibold mb-2">Email Address <span class="text-red-500">*</span></label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                class="w-full bg-white text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all @error('email') border-red-500 @enderror" 
                required>
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Address <span class="text-red-500">*</span></label>
            <input type="text" name="address" value="{{ old('address', $user->address) }}" 
                class="w-full bg-white text-gray-700 rounded-lg h-11 text-sm font-medium px-3 border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all @error('address') border-red-500 @enderror" 
                required>
            @error('address')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-2">City/Municipality</label>
                <div class="flex items-center bg-gray-100 text-gray-500 rounded-lg h-11 text-sm font-medium px-3 border border-gray-200">Mandaluyong</div>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-2">Barangay</label>
                <div class="flex items-center bg-gray-100 text-gray-500 rounded-lg h-11 text-sm font-medium px-3 border border-gray-200">Daang Bakal</div>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-2">Location Map:</label>
            <div id="map" class="w-full rounded-xl border-2 border-gray-200 z-0 shadow-sm" style="height: 120px;"></div>
        </div>
      </div>
      </div>

      <div class="mt-3 flex justify-end gap-3 pb-3">

        <a href="{{ route('admin.users.show', $user->id) }}"
             class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-6 py-2.5 rounded-xl transition-all duration-200 text-sm flex items-center border border-gray-300 hover:shadow-md">
             Cancel
        </a>

        <button type="submit"
             class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold px-6 py-2.5 rounded-xl transition-all duration-200 text-sm flex items-center gap-2 hover:shadow-lg transform hover:scale-105">
            <i data-lucide="save" class="w-4 h-4"></i> Save Changes
        </button>

      </div>
    </section>
</main>

</form>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Initialize Lucide Icons
    lucide.createIcons();

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
</script>

<link rel="stylesheet" href="{{ asset('css/scrollbars.css') }}">

</body>
</html>
