<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reset Password - Barangay Daang Bakal</title>
  <link rel="icon" type="image/png" href="{{ asset('images/BARANGAY LOGO.png') }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');
    body { font-family: 'Poppins', sans-serif; }
  </style>
</head>
<body>
  <div class="relative min-h-screen w-full bg-cover bg-center" style="background-image: url('https://media.karousell.com/media/photos/products/2025/3/4/lot_for_sale_in_barangay_daang_1741074654_b1a72035');">
    <div class="absolute inset-0 z-10 bg-white/70"></div>
    <div class="relative z-20 min-h-screen flex items-center justify-center p-4">
      <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-100 p-6 sm:p-8">
    <h1 class="text-2xl font-bold text-blue-600 text-center mb-2">Reset Password</h1>
    <p class="text-sm text-gray-500 text-center mb-6">Set your new password below.</p>

    @if ($errors->any())
      <div class="mb-4 rounded-xl bg-red-50 border-l-4 border-red-500 p-4 text-sm text-red-700">
        <ul class="list-disc list-inside space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">

      <input type="hidden" name="email" value="{{ request('email', $email ?? '') }}">


      <div>
        <label for="password" class="block text-gray-700 mb-2 font-semibold text-sm">New Password</label>
        <div class="relative">
          <input type="password" id="password" name="password" required
            class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 pr-12 text-gray-700 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" />
          <button type="button" id="toggleNewPassword" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-600 transition duration-200 p-1" aria-label="Toggle new password visibility">
            <svg id="eyeOpenNew" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            <svg id="eyeClosedNew" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
            </svg>
          </button>
        </div>
      </div>

      <div>
        <label for="password_confirmation" class="block text-gray-700 mb-2 font-semibold text-sm">Confirm New Password</label>
        <div class="relative">
          <input type="password" id="password_confirmation" name="password_confirmation" required
            class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 pr-12 text-gray-700 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" />
          <button type="button" id="toggleConfirmPassword" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-600 transition duration-200 p-1" aria-label="Toggle confirm password visibility">
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

      <button type="submit"
        class="w-full py-3 rounded-xl font-semibold shadow-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all duration-200">
        Reset Password
      </button>
    </form>

        <p class="text-center mt-5 text-sm">
          <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-semibold hover:underline">Back to Sign In</a>
        </p>
      </div>
    </div>
  </div>
</body>
</html>

<script>
  const toggleNewPassword = document.getElementById('toggleNewPassword');
  const newPasswordInput = document.getElementById('password');
  const eyeOpenNew = document.getElementById('eyeOpenNew');
  const eyeClosedNew = document.getElementById('eyeClosedNew');

  if (toggleNewPassword && newPasswordInput) {
    toggleNewPassword.addEventListener('click', () => {
      newPasswordInput.type = newPasswordInput.type === 'password' ? 'text' : 'password';
      eyeOpenNew?.classList.toggle('hidden');
      eyeClosedNew?.classList.toggle('hidden');
    });
  }

  const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
  const confirmPasswordInput = document.getElementById('password_confirmation');
  const eyeOpenConfirm = document.getElementById('eyeOpenConfirm');
  const eyeClosedConfirm = document.getElementById('eyeClosedConfirm');

  if (toggleConfirmPassword && confirmPasswordInput) {
    toggleConfirmPassword.addEventListener('click', () => {
      confirmPasswordInput.type = confirmPasswordInput.type === 'password' ? 'text' : 'password';
      eyeOpenConfirm?.classList.toggle('hidden');
      eyeClosedConfirm?.classList.toggle('hidden');
    });
  }
</script>
