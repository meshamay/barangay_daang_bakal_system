<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Forgot Password - Barangay Daang Bakal</title>
  <link rel="icon" type="image/png" href="{{ asset('images/BARANGAY LOGO.png') }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');
    body { font-family: 'Poppins', sans-serif; }
  </style>
</head>
<body class="relative overflow-hidden min-h-screen flex items-center justify-center p-0 bg-gradient-to-br from-blue-50 via-white to-blue-100 overflow-y-hidden">
  <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-20" style="background-image: url('https://media.karousell.com/media/photos/products/2025/3/4/lot_for_sale_in_barangay_daang_1741074654_b1a72035');"></div>

  <div class="relative z-20 w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-100 p-6 sm:p-8">
    <h1 class="text-2xl font-bold text-blue-600 text-center mb-2">Forgot Password</h1>
    <p class="text-sm text-gray-500 text-center mb-6">Enter your phone number to reset your password.</p>

    @if (session('status'))
      <div class="mb-4 rounded-xl bg-green-50 border-l-4 border-green-500 p-4 text-sm text-green-700">
        {{ session('status') }}
      </div>
    @endif

    @if ($errors->any())
      <div class="mb-4 rounded-xl bg-red-50 border-l-4 border-red-500 p-4 text-sm text-red-700">
        <ul class="list-disc list-inside space-y-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
      @csrf
      <div>
        <label for="contact_number" class="block text-gray-700 mb-2 font-semibold text-sm">Phone Number</label>
        <input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" required
          class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 text-gray-700 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" />
      </div>

      <button type="submit"
        class="w-full py-3 rounded-xl font-semibold shadow-lg text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all duration-200">
        Continue
      </button>
    </form>

    <p class="text-center mt-5 text-sm">
      <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-semibold hover:underline">Back to Sign In</a>
    </p>
  </div>
</body>
</html>
