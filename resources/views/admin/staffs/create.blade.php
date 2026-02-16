@extends('admin.layouts.app')

@section('content')
<main class="fflex-1 p-11 fixed top-[60px] left-[220px]
    w-[calc(100vw-200px)] h-[calc(100vh-60px)]
    overflow-hidden bg-gray-100">
  <h1 class="text-3xl font-bold mb-6">ADD STAFF</h1>

  <div class="bg-white shadow-md rounded-lg p-6">
    <form action="{{ route('admin.staffs.store') }}" method="POST">
      @csrf

      <div class="grid grid-cols-2 gap-6">
        <!-- First Name -->
        <div>
          <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
          <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
          @error('first_name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Last Name -->
        <div>
          <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
          <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
          @error('last_name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Middle Name -->
        <div>
          <label for="middle_name" class="block text-sm font-medium text-gray-700">Middle Name</label>
          <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
          @error('middle_name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Suffix -->
        <div>
          <label for="suffix" class="block text-sm font-medium text-gray-700">Suffix</label>
          <input type="text" name="suffix" id="suffix" value="{{ old('suffix') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
          @error('suffix')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Username -->
        <div>
          <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
          <input type="text" name="username" id="username" value="{{ old('username') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
          @error('username')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
          @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input type="password" name="password" id="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
          @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Confirm Password -->
        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
          <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        <!-- Role -->
        <div>
          <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
          <select name="role" id="role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
          </select>
          @error('role')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
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