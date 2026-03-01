@extends('admin.layouts.app')

@section('content')
<main class="fflex-1 p-11 fixed top-[60px] left-[220px]"
  w-[calc(100vw-200px)] h-[calc(100vh-60px)]
  overflow-hidden bg-gray-100" style="font-family: 'Poppins', sans-serif;">
  <h1 class="text-xl sm:text-2xl md:text-3xl font-bold mb-6" style="font-family: 'Poppins', sans-serif;">ADD STAFF</h1>

  <div class="bg-white shadow-md rounded-lg p-6" style="font-family: 'Poppins', sans-serif;">
    <form action="{{ route('admin.staffs.store') }}" method="POST">
      @csrf
      <div class="grid grid-cols-2 gap-6">
        <!-- First Name -->
        <div>
          <label for="first_name" class="block text-sm font-medium text-gray-700" style="font-family: 'Poppins', sans-serif;">First Name</label>
          <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" class="mt-1 block w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" style="font-family: 'Poppins', sans-serif; font-size: 1rem;" required>
          @error('first_name')
            <p class="mt-1 text-sm text-red-600" style="font-family: 'Poppins', sans-serif;">{{ $message }}</p>
          @enderror
        </div>
        <!-- Last Name -->
        <div>
          <label for="last_name" class="block text-sm font-medium text-gray-700" style="font-family: 'Poppins', sans-serif;">Last Name</label>
          <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="mt-1 block w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" style="font-family: 'Poppins', sans-serif; font-size: 1rem;" required>
          @error('last_name')
            <p class="mt-1 text-sm text-red-600" style="font-family: 'Poppins', sans-serif;">{{ $message }}</p>
          @enderror
        </div>
        <!-- Middle Name -->
        <div>
          <label for="middle_name" class="block text-sm font-medium text-gray-700" style="font-family: 'Poppins', sans-serif;">Middle Name</label>
          <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}" class="mt-1 block w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" style="font-family: 'Poppins', sans-serif; font-size: 1rem;">
          @error('middle_name')
            <p class="mt-1 text-sm text-red-600" style="font-family: 'Poppins', sans-serif;">{{ $message }}</p>
          @enderror
        </div>
        <!-- Suffix -->
        <div>
          <label for="suffix" class="block text-sm font-medium text-gray-700" style="font-family: 'Poppins', sans-serif;">Suffix</label>
          <input type="text" name="suffix" id="suffix" value="{{ old('suffix') }}" class="mt-1 block w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" style="font-family: 'Poppins', sans-serif; font-size: 1rem;">
          @error('suffix')
            <p class="mt-1 text-sm text-red-600" style="font-family: 'Poppins', sans-serif;">{{ $message }}</p>
          @enderror
        </div>
        <!-- Username -->
        <div>
          <label for="username" class="block text-sm font-medium text-gray-700" style="font-family: 'Poppins', sans-serif;">Username</label>
          <input type="text" name="username" id="username" value="{{ old('username') }}" class="mt-1 block w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" style="font-family: 'Poppins', sans-serif; font-size: 1rem;" required>
          @error('username')
            <p class="mt-1 text-sm text-red-600" style="font-family: 'Poppins', sans-serif;">{{ $message }}</p>
          @enderror
        </div>
        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700" style="font-family: 'Poppins', sans-serif;">Email</label>
          <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" style="font-family: 'Poppins', sans-serif; font-size: 1rem;" required>
          @error('email')
            <p class="mt-1 text-sm text-red-600" style="font-family: 'Poppins', sans-serif;">{{ $message }}</p>
          @enderror
        </div>
        <!-- Password -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700" style="font-family: 'Poppins', sans-serif;">Password</label>
          <input type="password" name="password" id="password" class="mt-1 block w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" style="font-family: 'Poppins', sans-serif; font-size: 1rem;" required>
          @error('password')
            <p class="mt-1 text-sm text-red-600" style="font-family: 'Poppins', sans-serif;">{{ $message }}</p>
          @enderror
        </div>
        <!-- Confirm Password -->
        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700" style="font-family: 'Poppins', sans-serif;">Confirm Password</label>
          <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full bg-gray-50 border-2 border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" style="font-family: 'Poppins', sans-serif; font-size: 1rem;" required>
        </div>
      </div>

      <div class="mt-6 flex justify-end space-x-4">
        <a href="{{ route('admin.staffs.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">Cancel</a>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Create Staff</button>
      </div>
    </form>
  </div>
</main>
@endsection