@extends('admin.layouts.app')

@section('content')
<main class="flex-1 p-8 fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] overflow-y-auto bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-[#134573] to-[#0f3a5f] bg-clip-text text-transparent">PROFILE SETTINGS</h1>
            <p class="text-gray-600 mt-2 text-lg">Manage your account information.</p>
        </div>

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <h3 class="text-red-800 font-semibold mb-2">Errors:</h3>
                <ul class="text-red-700 text-sm">
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-green-800 font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Personal Information Section -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                <div class="flex items-center gap-4 mb-8 pb-6 border-b border-gray-200">
                    <div class="w-24 h-24 border-4 border-blue-200 rounded-2xl flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-50 shadow-md">
                        @if($user->photo_path)
                            <img src="{{ asset('storage/' . $user->photo_path) }}" alt="Profile" class="w-full h-full object-cover rounded-lg">
                        @else
                            <svg class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">
                            @if(in_array($user->user_type, ['super_admin', 'superadmin']) || in_array($user->role, ['super_admin', 'superadmin']))
                                Super Administrator
                            @else
                                {{ $user->first_name }} {{ $user->last_name }}
                            @endif
                        </h2>
                        <p class="text-gray-600 text-sm mt-1 bg-blue-50 inline-block px-3 py-1 rounded-full font-semibold">
                            @if(in_array($user->user_type, ['super_admin', 'superadmin']) || in_array($user->role, ['super_admin', 'superadmin']))
                                Super Administrator
                            @else
                                {{ ucfirst($user->role) }}
                            @endif
                        </p>
                    </div>
                </div>

                <h3 class="text-lg font-bold mb-6 text-gray-900 flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    Personal Information
                </h3>

                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">First Name</label>
                            <div class="flex items-center bg-gradient-to-r from-gray-50 to-blue-50 text-gray-700 rounded-lg h-11 text-sm font-semibold px-4 border border-gray-200 shadow-sm">{{ $user->first_name }}</div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Last Name</label>
                            <div class="flex items-center bg-gradient-to-r from-gray-50 to-blue-50 text-gray-700 rounded-lg h-11 text-sm font-semibold px-4 border border-gray-200 shadow-sm">{{ $user->last_name }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Middle Name</label>
                            <div class="flex items-center bg-gradient-to-r from-gray-50 to-blue-50 text-gray-700 rounded-lg h-11 text-sm font-semibold px-4 border border-gray-200 shadow-sm">{{ $user->middle_name ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Suffix</label>
                            <div class="flex items-center bg-gradient-to-r from-gray-50 to-blue-50 text-gray-700 rounded-lg h-11 text-sm font-semibold px-4 border border-gray-200 shadow-sm">{{ $user->suffix ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Email Address</label>
                            <div class="flex items-center bg-gradient-to-r from-gray-50 to-blue-50 text-gray-700 rounded-lg h-11 text-sm font-semibold px-4 border border-gray-200 shadow-sm">{{ $user->email }}</div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Contact Number</label>
                            <div class="flex items-center bg-gradient-to-r from-gray-50 to-blue-50 text-gray-700 rounded-lg h-11 text-sm font-semibold px-4 border border-gray-200 shadow-sm">{{ $user->contact_number ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Role</label>
                            <div class="flex items-center bg-gradient-to-r from-gray-50 to-blue-50 text-gray-700 rounded-lg h-11 text-sm font-semibold px-4 border border-gray-200 shadow-sm">
                                @if(in_array($user->user_type, ['super_admin', 'superadmin']) || in_array($user->role, ['super_admin', 'superadmin']))
                                    Super Administrator
                                @else
                                    {{ ucfirst($user->role) }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Status Section -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                <h3 class="text-lg font-bold mb-6 text-gray-900 flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    Account Status
                </h3>

                <div class="space-y-6">
                    <div class="p-6 rounded-xl bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Account Created</p>
                                <p class="text-xs text-gray-600">{{ $user->created_at->format('F d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 rounded-xl bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Last Updated</p>
                                <p class="text-xs text-gray-600">{{ $user->updated_at->format('F d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
