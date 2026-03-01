@extends('admin.layouts.app')

@section('content')
<main class="fflex-1 p-11 fixed top-[60px] left-[220px]" w-[calc(100vw-200px)] h-[calc(100vh-60px)] overflow-hidden bg-gray-100" style="font-family: 'Poppins', sans-serif;">
  <h1 class="text-xl sm:text-2xl md:text-3xl font-bold mb-6" style="font-family: 'Poppins', sans-serif;">STAFF DETAILS</h1>
  <div class="bg-white shadow-md rounded-lg p-6" style="font-family: 'Poppins', sans-serif;">
    <div class="grid grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-medium text-gray-700" style="font-family: 'Poppins', sans-serif;">First Name</label>
        <p class="mt-1 text-sm text-gray-900" style="font-family: 'Poppins', sans-serif; font-size: 1rem;">{{ $staff->first_name }}</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700" style="font-family: 'Poppins', sans-serif;">Last Name</label>
        <p class="mt-1 text-sm text-gray-900" style="font-family: 'Poppins', sans-serif; font-size: 1rem;">{{ $staff->last_name }}</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700" style="font-family: 'Poppins', sans-serif;">Middle Name</label>
        <p class="mt-1 text-sm text-gray-900" style="font-family: 'Poppins', sans-serif; font-size: 1rem;">{{ $staff->middle_name ?: 'N/A' }}</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700" style="font-family: 'Poppins', sans-serif;">Suffix</label>
        <p class="mt-1 text-sm text-gray-900" style="font-family: 'Poppins', sans-serif; font-size: 1rem;">{{ $staff->suffix ?: 'N/A' }}</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700" style="font-family: 'Poppins', sans-serif;">Username</label>
        <p class="mt-1 text-sm text-gray-900" style="font-family: 'Poppins', sans-serif; font-size: 1rem;">{{ $staff->username }}</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700" style="font-family: 'Poppins', sans-serif;">Email</label>
        <p class="mt-1 text-sm text-gray-900" style="font-family: 'Poppins', sans-serif; font-size: 1rem;">{{ $staff->email }}</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700" style="font-family: 'Poppins', sans-serif;">Status</label>
        <p class="mt-1 text-sm text-gray-900" style="font-family: 'Poppins', sans-serif; font-size: 1rem;">{{ ucfirst($staff->status) }}</p>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700" style="font-family: 'Poppins', sans-serif;">Date Registered</label>
        <p class="mt-1 text-sm text-gray-900" style="font-family: 'Poppins', sans-serif; font-size: 1rem;">{{ $staff->created_at->format('d/m/Y') }}</p>
      </div>
    </div>
    <div class="mt-6 flex justify-end">
      <a href="{{ route('admin.staffs.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400" style="font-family: 'Poppins', sans-serif;">Back</a>
    </div>
  </div>
</main>
@endsection