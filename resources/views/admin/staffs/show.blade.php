@extends('admin.layouts.app')

@section('content')
<main class="fflex-1 p-11 fixed top-[60px] left-[220px]
    w-[calc(100vw-200px)] h-[calc(100vh-60px)]
    overflow-hidden bg-gray-100">
  <h1 class="text-3xl font-bold mb-6">STAFF DETAILS</h1>

  <div class="bg-white shadow-md rounded-lg p-6">
    <div class="grid grid-cols-2 gap-6">
      <div>
        <label class="block text-sm font-medium text-gray-700">First Name</label>
        <p class="mt-1 text-sm text-gray-900">{{ $staff->first_name }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Last Name</label>
        <p class="mt-1 text-sm text-gray-900">{{ $staff->last_name }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Middle Name</label>
        <p class="mt-1 text-sm text-gray-900">{{ $staff->middle_name ?: 'N/A' }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Suffix</label>
        <p class="mt-1 text-sm text-gray-900">{{ $staff->suffix ?: 'N/A' }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Username</label>
        <p class="mt-1 text-sm text-gray-900">{{ $staff->username }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <p class="mt-1 text-sm text-gray-900">{{ $staff->email }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Role</label>
        <p class="mt-1 text-sm text-gray-900">{{ ucfirst($staff->role) }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Status</label>
        <p class="mt-1 text-sm text-gray-900">{{ ucfirst($staff->status) }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Date Registered</label>
        <p class="mt-1 text-sm text-gray-900">{{ $staff->created_at->format('d/m/Y') }}</p>
      </div>
    </div>

    <div class="mt-6 flex justify-end">
      <a href="{{ route('admin.staffs.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">Back</a>
    </div>
  </div>
</main>
@endsection