<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Layout</title>
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


<!-- TOP NAVBAR -->
<nav id="top-navbar" class="fixed top-0 left-0 w-full h-20 font-barlow text-white shadow-lg z-30 flex items-center justify-between px-6 border-b border-white/10" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">



    <!-- LEFT SIDE: LOGOS + TEXT -->
    <div class="flex items-center gap-4">

        <!-- LOGOS SIDE BY SIDE (NO OVERLAP) -->
        <div class="flex items-center space-x-3">
        <label for="sidebar-toggle" class="cursor-pointer md:hidden p-2 rounded-lg hover:bg-white/10 transition duration-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </label>

        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b7/Mandaluyong_seal.svg/1024px-Mandaluyong_seal.svg.png" alt="Mandaluyong Seal" class="w-12 h-12">
        <img src="https://tse2.mm.bing.net/th/id/OIP._bP7eQwOSrZjwv-doDDsWAHaHa?rs=1&pid=ImgDetMain&o=7&rm=3" alt="Barangay Seal" class="w-12 h-12 rounded-full object-cover">

        <div>
          <h1 class="text-xl font-semibold">Barangay Daang Bakal</h1>
          <p class="text-lg font-semibold">Mandaluyong City</p>
        </div>
      </div>

    </div>

    <!-- RIGHT SIDE: NOTIFICATION + PROFILE -->
    <div class="flex items-center gap-6">

        <!-- NOTIFICATION BELL -->

 <div class="flex items-center space-x-5">
<div x-data="{ open: false }" class="relative">
  <!-- Bell Icon Button -->
  <button @click="open = !open" class="relative p-2 rounded-lg hover:bg-white/10 transition duration-200">
    <!-- Bell Icon -->
    <svg xmlns="http://www.w3.org/2000/svg"
         class="h-6 w-6 text-white"
         fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002
           6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67
           6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595
           1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
    </svg>

  </button>

  <!-- Dropdown Panel -->
  <div
    x-show="open"
    @click.outside="open = false"
    class="absolute right-0 mt-3 w-80 bg-white shadow-lg rounded-xl border border-gray-100 overflow-hidden z-50">

    <div class="p-4 border-b">
      <h3 class="text-lg font-semibold text-gray-700">Notifications</h3>
    </div>

    <div class="max-h-96 overflow-y-auto">

      <!-- Notification Item -->
      <div class="p-4 border-b hover:bg-gray-50">
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 flex items-center justify-center bg-yellow-100 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600"
              fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4l3 3" />
            </svg>
          </div>
          <div>
            <p class="text-sm text-gray-800">
              Your complaint (CMP-T8634) has been received and is currently being processed.
            </p>
            <p class="text-xs text-gray-500 mt-1">Date Filed: 10/22/2025</p>
          </div>
        </div>
      </div>

      <!-- Another Item -->
      <div class="p-4 border-b hover:bg-gray-50">
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 flex items-center justify-center bg-gray-200 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700"
              fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 13l4 4L19 7" />
            </svg>
          </div>
          <div>
            <p class="text-sm text-gray-800">
              Your document request is pending. Please wait for admin approval.
            </p>
            <p class="text-xs text-gray-500 mt-1">Date Requested: 10/20/2025</p>
          </div>
        </div>
      </div>

      <!-- Completed Item -->
      <div class="p-4 hover:bg-gray-50">
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 flex items-center justify-center bg-green-100 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600"
              fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 13l4 4L19 7" />
            </svg>
          </div>
          <div>
            <p class="text-sm text-gray-800">
              Your document request has been completed. You may now claim it.
            </p>
            <p class="text-xs text-gray-500 mt-1">Date Completed: 09/15/2025</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

        <!-- PROFILE SECTION -->


                    <div class="flex items-center pl-6 border-l border-blue-300/30 h-8">

                        <div class="text-right mr-3 hidden sm:block">
                            <p class="text-md font-bold text-white leading-none">Juan Dela Cruz</p>
                            <p class="text-xs text-blue-200 font-medium mt-1">Resident</p>
                        </div>


                        <div class="h-10 w-10 rounded-full bg-white/10 border border-white/40 flex items-center justify-center text-white backdrop-blur-sm hover:bg-white/20 transition cursor-pointer">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>


                    </div>
                </div>


            </div>
        </div>
    </nav>

<br>
  <!-- Main Content -->
  <main class="grow flex px-12 py-6 gap-8 text-[#1e2e3d] text-[1.05rem] pt-20 max-w-7xl mx-auto">

    <!-- Left Panel -->
    <section class="w-1/2 flex flex-col">
      <!-- Profile Area -->
      <div class="flex items-center gap-6 mb-4">
        <div class="w-40 h-36 border-4 border-[#a2c4d9] rounded-xl flex items-center justify-center relative overflow-hidden">
          <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile Image" class="w-full h-full object-cover">
        </div>
        <div class="text-left">
          <h3 class="text-3xl font-bold text-[#1e2e3d]">{{ $user->first_name }} {{ $user->last_name }}</h3>
          <p class="text-gray-600 text-base font-semibold">{{ $user->resident_id_no }}</p>
        </div>
      </div>

      <!-- Personal Info Label -->
      <h2 class="text-lg font-bold mb-4 ml-1">Personal Information</h2>

      <!-- Text Holders -->
      <div class="space-y-5">
        <!-- Row 1 (4 fields) -->
        <div class="grid grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-semibold mb-1">Last Name</label>
            <div class="flex items-center bg-gray-200 text-gray-500 rounded-md h-10 text-sm font-medium px-3">{{ $user->last_name }}</div>
          </div>
          <div>

            <label class="block text-sm font-semibold mb-1">First Name</label>
            <div class="flex items-center bg-gray-200 text-gray-500 rounded-md h-10 text-sm font-medium px-3">{{ $user->first_name }}</div>
          </div>
          <div>
            <label class="block text-sm font-semibold mb-1">Middle Name</label>
            <div class="flex items-center bg-gray-200 text-gray-500 rounded-md h-10 text-sm font-medium px-3">{{ $user->middle_name }}</div>
          </div>
          <div>
            <label class="block text-sm font-semibold mb-1">Suffix</label>
            <div class="flex items-center bg-gray-200 text-gray-500 rounded-md h-10 text-sm font-medium px-3">{{ $user->suffix }}</div>
          </div>
        </div>

        <!-- Row 2 (3 fields) -->
        <div class="grid grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-semibold mb-1">Age</label>
            <div class="flex items-center bg-gray-200 text-gray-500 rounded-md h-10 text-sm font-medium px-3">{{ \Carbon\Carbon::parse($user->birth_date)->age }}</div>
          </div>
          <div>
            <label class="block text-sm font-semibold mb-1">Date of Birth</label>
            <div class="flex items-center bg-gray-200 text-gray-500 rounded-md h-10 text-sm font-medium px-3">{{ \Carbon\Carbon::parse($user->birth_date)->format('m/d/Y') }}</div>
          </div>
          <div>
            <label class="block text-sm font-semibold mb-1">Place of Birth</label>
            <div class="flex items-center bg-gray-200 text-gray-500 rounded-md h-10 text-sm font-medium px-3">{{ $user->birth_place }}</div>
          </div>
        </div>

        <!-- Row 3 (3 fields) -->
        <div class="grid grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-semibold mb-1">Gender</label>
            <div class="flex items-center bg-gray-200 text-gray-500 rounded-md h-10 text-sm font-medium px-3">{{ $user->gender }}</div>
          </div>
          <div>
            <label class="block text-sm font-semibold mb-1">Civil Status</label>
            <div class="flex items-center bg-gray-200 text-gray-500 rounded-md h-10 text-sm font-medium px-3">{{ $user->civil_status }}</div>
          </div>
          <div>
            <label class="block text-sm font-semibold mb-1">Citizenship</label>
            <div class="flex items-center bg-gray-200 text-gray-500 rounded-md h-10 text-sm font-medium px-3">{{ $user->citizenship }}</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Divider -->
    <div class="w-px bg-[#a2c4d9]"></div>

    <!-- Right Panel -->
    <section class="w-1/2 flex flex-col">
      <h2 class="text-xl font-bold mb-4">Contact Information</h2>

      <!-- Camera Boxes -->
      <div class="flex gap-6 mb-6">
        <div class="w-60 h-36 border-4 border-[#a2c4d9] rounded-xl flex items-center justify-center overflow-hidden">
          <img src="{{ asset('storage/' . $user->valid_id_front) }}" alt="Valid ID Front" class="w-full h-full object-cover">
        </div>
        <div class="w-60 h-36 border-4 border-[#a2c4d9] rounded-xl flex items-center justify-center overflow-hidden">
          <img src="{{ asset('storage/' . $user->valid_id_back) }}" alt="Valid ID Back" class="w-full h-full object-cover">
        </div>
      </div>

      <!-- Fields -->
      <div class="space-y-5">
        <!-- Row 1 (2 fields) -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold mb-0">Contact No.</label>
            <div class="flex items-center bg-gray-200 text-gray-500 rounded-md h-10 text-sm font-medium px-3">{{ $user->contact_number }}</div>
          </div>
          <div>
            <label class="block text-sm font-semibold mb-0">Email Address</label>
            <div class="flex items-center bg-gray-200 text-gray-500 rounded-md h-10 text-sm font-medium px-3">{{ $user->email }}</div>
          </div>
        </div>

        <!-- Row 2 (2 fields) -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold mb-0">House/Unit No.,Street</label>
           <div class="flex items-center bg-gray-200 text-gray-500 rounded-md h-10 text-sm font-medium px-3">{{ $user->address }}</div>
          </div>
          <div>
            <label class="block text-sm font-semibold mb-0">Barangay</label>
            <div class="flex items-center bg-gray-200 text-gray-500 rounded-md h-10 text-sm font-medium px-3">Daang Bakal</div>
          </div>
        </div>

        <!-- Row 3  -->
 <div class="grid grid-cols-2 gap-4">

    <!-- Left container -->
    <div>
        <label class="block text-sm font-semibold mb-0">City/Municipality</label>
        <div class="flex items-center bg-gray-200 text-gray-500 rounded-md h-10 text-sm font-medium px-3">Mandaluyong</div>
    </div>

    <!-- Right container -->
    <div>
        <label class="block text-sm font-semibold mb-1">Location Map:</label>
        <div id="map" class="w-full rounded-lg" style="height: 80px;"></div>
    </div>

</div>
      </div>
  <!--Buttons -->

    <div class="flex justify-end mt-5">
        <button onclick="window.location.href='/superadmin/users'"
              class="bg-[#A2C4D9] hover:bg-[#94B8CC] text-xs text-black font-extrabold px-6 py-1 rounded-md transition-all duration-200 shadow-sm gap-4 mr-4">
       CLOSE
      </button>
      <button onclick="window.location.href='/superadmin/users/profile/edit'"
              class="bg-[#A2C4D9] hover:bg-[#94B8CC] text-xs text-black font-extrabold px-6 py-1 rounded-md transition-all duration-200 shadow-sm">
       EDIT
      </button>
    </div>


    </section>
  </main>
















  <!-- =============================== -->
  <!-- JavaScript -->
  <!-- =============================== -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script>

    lucide.createIcons();


    document.addEventListener('DOMContentLoaded', () => {
  const backButton = document.getElementById('backButton');
  if (backButton) {
    backButton.addEventListener('click', () => {
      window.location.href = 'user-homepage';
    });
  }
});


    // Default location (Mandaluyong City Hall)
    const defaultLat = {{ $user->latitude ?? 14.5794 }};
    const defaultLng = {{ $user->longitude ?? 121.0359 }};

    // Initialize map
    const map = L.map('map').setView([defaultLat, defaultLng], 16);

    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    // Create draggable marker
    let marker = L.marker([defaultLat, defaultLng], {
        draggable: false // Set to false for view-only
    }).addTo(map);

    // Let staff/Admin drag or click to move marker
    map.on('click', function (e) {
        marker.setLatLng([e.latlng.lat, e.latlng.lng]);
    });


  </script>

</body>

</html>
