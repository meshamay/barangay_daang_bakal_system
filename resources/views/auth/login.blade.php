<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign In</title>
  <script>
    if (window.location.protocol !== 'https:' && window.location.hostname !== 'localhost') {
      window.location.replace('https://' + window.location.host + window.location.pathname + window.location.search);
    }
  </script>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');
    body { 
      font-family: 'Poppins', sans-serif;
    }
  </style>
  <link rel="stylesheet" href="{{ asset('css/animations.css') }}">
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-6 bg-gradient-to-br from-blue-50 via-white to-blue-100">

  <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-20"
       style="background-image: url('https://media.karousell.com/media/photos/products/2025/3/4/lot_for_sale_in_barangay_daang_1741074654_b1a72035');"></div>

  <div class="relative z-20 flex flex-col md:flex-row w-full max-w-5xl mx-auto rounded-3xl overflow-hidden shadow-2xl border-2 border-gray-100 bg-white fade-in my-auto">

    <div class="hidden md:flex md:w-1/2 relative" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
      <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/signIn.png') }}');"></div>
      <div class="relative z-10 p-12 text-white flex flex-col justify-center items-center h-full translate-x-3 translate-y-36">
        <div class="border-2 border-[#134573] rounded-2xl p-6 bg-white/5 backdrop-blur-sm max-w-sm">
          <h1 class="text-3xl font-bold mb-3 tracking-tight text-white drop-shadow-lg text-center">Welcome Back!</h1>
          <p class="text-base leading-relaxed text-center text-white drop-shadow-lg">
            Sign in to continue accessing seamless document requests and efficient service management.
          </p>
        </div>
      </div>
    </div>

    <div class="w-full md:w-1/2 p-6 sm:p-8 md:p-12 bg-white">
      <h2 class="text-2xl sm:text-3xl font-bold text-center mb-2 text-blue-600">Sign In</h2>
      <p class="text-center text-gray-500 text-xs sm:text-sm mb-6 sm:mb-8">Access the Barangay Management System</p>

      @if ($errors->any())
          <div class="mb-6 rounded-xl bg-red-50 border-l-4 border-red-500 p-4 text-sm text-red-700" role="alert">
              <div class="font-semibold mb-1">Please correct the following errors:</div>
              <ul class="list-disc list-inside space-y-1">
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
          </div>
      @endif
      @if (session('success'))
          <div class="mb-6 rounded-xl bg-green-50 border-l-4 border-green-500 p-4 text-sm text-green-700" role="alert">
              <strong class="font-semibold">Success!</strong> {{ session('success') }}
          </div>
      @endif
<form id="loginForm" action="{{ url()->secure(route('login.post', [], false)) }}" method="POST" class="space-y-5">
        @csrf
        <div>
          <label for="username" class="block text-gray-700 mb-2 font-semibold text-sm">Username or Email Address</label>
          <input type="text" id="username" name="username" placeholder="Enter your username or email" required
            class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 text-gray-700 bg-gray-50
                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" />
        </div>

            <div>
          <label for="password" class="block text-gray-700 mb-2 font-semibold text-sm">Password</label>
          <div class="relative">
            <input type="password" id="password" name="password" placeholder="Enter your password" required
                   class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 pr-12 text-gray-700 bg-gray-50
                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" />
            <button type="button" id="togglePassword"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-600 transition duration-200 p-1">
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

        <button type="submit"
          class="w-full py-3 rounded-xl font-semibold shadow-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800
                 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98]">
          Sign In
        </button>

        <p class="text-center text-gray-600 text-sm mt-6 pt-5 border-t-2 border-gray-100">
          Don't have an account?
          <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-semibold hover:underline transition duration-150">
            Register here
          </a>
        </p>

        <p class="text-center mt-3 text-sm">
          <a href="{{ url('/') }}" class="text-gray-500 hover:text-gray-700 hover:underline transition duration-150 flex items-center justify-center gap-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Return to Homepage
          </a>
        </p>
      </form>
    </div>
  </div>

</body>
</html>

<script src="{{ asset('js/auth-login.js') }}" defer></script>
