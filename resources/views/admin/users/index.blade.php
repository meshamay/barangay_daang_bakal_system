@extends('admin.layouts.app')

@section('content')

@push('styles')
	<link rel="stylesheet" href="{{ asset('css/admin-modals.css') }}">
@endpush

<style>	.modal-container:not(.hidden) {
		display: flex !important;
	}
</style>

<main class="flex-1 p-8 fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-[#134573] to-[#0f3a5f] bg-clip-text text-transparent">USERS</h1>
            <p class="text-gray-500 text-sm mt-1">Manage resident accounts and statuses.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg hover:border-blue-200 transition duration-300 ease-in-out">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Registered Residents</p>
                        <p class="text-4xl font-bold text-gray-900 mt-2">{{ $totalResidents ?? 0 }}</p>
                </div>
                <div class="bg-blue-100 p-4 rounded-lg">
                    <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 6a3 3 0 11-6 0 3 3 0 016 0zM6 20h12a6 6 0 00-6-6 6 6 0 00-6 6z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg hover:border-emerald-200 transition duration-300 ease-in-out">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Male</p>
                    <p class="text-4xl font-bold text-gray-900 mt-2">{{ $maleCount ?? 0 }}</p>
                </div>
                <div class="bg-emerald-100 p-4 rounded-lg">
                    <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4a4 4 0 110 8 4 4 0 010-8zm0 8c-3.5 0-6 2.5-6 5.5V19h12v-1.5C18 14.5 15.5 12 12 12z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg hover:border-amber-200 transition duration-300 ease-in-out">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Female</p>
                    <p class="text-4xl font-bold text-gray-900 mt-2">{{ $femaleCount ?? 0 }}</p>
                </div>
                <div class="bg-amber-100 p-4 rounded-lg">
                    <svg class="h-8 w-8 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4a4 4 0 110 8 4 4 0 010-8zm0 8c-3.5 0-6 2.5-6 5.5V19h12v-1.5C18 14.5 15.5 12 12 12z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg hover:border-red-200 transition duration-300 ease-in-out">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Archived Accounts</p>
                    <p class="text-4xl font-bold text-gray-900 mt-2">{{ $archivedCount ?? 0 }}</p>
                </div>
                <div class="bg-red-100 p-4 rounded-lg">
                    <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7H4m0 0l1-3h14l1 3zm-1 3h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2v-7zm4 3h6" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.users.index') }}" method="GET" class="flex justify-between items-center mb-6 gap-4">

        <div class="flex items-center gap-3 flex-1">

            <div class="relative w-full max-w-md">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search residents..." class="w-full h-10 border border-gray-200 rounded-lg pl-11 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition" />
                <button type="submit" class="hidden"></button>
            </div>

            <div class="flex gap-2 w-fit">

                <select name="gender" onchange="this.form.submit()" class="h-10 px-4 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition bg-white w-32">
                    <option value="">Gender</option> <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Non Binary" {{ request('gender') == 'Non Binary' ? 'selected' : '' }}>Non Binary</option>
                </select>

                <select name="status" onchange="this.form.submit()" class="h-10 px-4 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition bg-white w-32">
                    <option value="">Status</option> <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Reject" {{ request('status') == 'Reject' ? 'selected' : '' }}>Reject</option>
                    <option value="Archived" {{ request('status') == 'Archived' ? 'selected' : '' }}>Archived</option>
                </select>

            </div>

            @if(request('search') || request('gender') || request('status'))
                <a href="{{ route('admin.users.index') }}" class="h-10 px-4 flex items-center gap-2 text-sm border border-gray-300 rounded-lg bg-white hover:bg-gray-50 text-gray-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Clear
                </a>
            @endif
        </div>

    </form>

    <div class="relative">
        <div class="bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden">
            {{-- Fixed Header --}}
            <table class="w-full text-sm relative z-10" style="table-layout: fixed;">
                <thead style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%); color: white;" class="shadow-sm">
                    <tr class="text-xs font-semibold uppercase tracking-widest text-center">
                        <th class="py-5 px-6 w-1/7">Resident ID No.</th>
                        <th class="py-5 px-6 w-1/7">Last Name</th>
                        <th class="py-5 px-6 w-1/7">First Name</th>
                        <th class="py-5 px-6 w-1/7">Gender</th>
                        <th class="py-5 px-6 w-1/7">Date Registered</th>
                        <th class="py-5 px-6 w-1/7">Status</th>
                        <th class="py-5 px-6 w-1/7">Action</th>
                    </tr>
                </thead>
            </table>
            {{-- Scrollable Body --}}
            <div class="overflow-x-auto overflow-y-auto max-h-[360px] relative z-0">
            <table class="w-full text-sm" style="table-layout: fixed;">
                <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="hover:bg-blue-50/70 transition-colors duration-150 ease-in-out text-center">
                    <td class="py-5 px-6 w-1/7 font-semibold text-gray-900">
                        {{ $user->resident_id ?? 'N/A' }}
                    </td>
                    <td class="py-5 px-6 w-1/7 text-gray-700">{{ $user->last_name }}</td>
                    <td class="py-5 px-6 w-1/7 text-gray-700">{{ $user->first_name }}</td>
                    <td class="py-5 px-6 w-1/7 text-gray-600">{{ $user->gender }}</td>
                    <td class="py-5 px-6 w-1/7 text-gray-600 text-sm">{{ $user->created_at->format('m/d/Y') }}</td>
                    <td class="py-5 px-6 w-1/7">
                        @php
                            $status = strtolower($user->status ?? 'pending');
                            $isArchived = $user->trashed();
                            $colorClass = $isArchived
                                ? 'bg-gray-100 text-gray-800 border border-gray-300'
                                : match($status) {
                                    'approved', 'accepted' => 'bg-emerald-100 text-emerald-800 border border-emerald-300',
                                    'reject'   => 'bg-red-100 text-red-800 border border-red-300',
                                    default    => 'bg-amber-100 text-amber-800 border border-amber-300', // Pending
                                };
                        @endphp
                        <span class="{{ $colorClass }} text-xs font-bold px-3 py-2 rounded-full inline-block capitalize shadow-sm">
                            {{ $isArchived ? 'archived' : $status }}
                        </span>
                    </td>
                    <td class="py-5 px-6 w-1/7 text-center">
                        <div class="flex items-center justify-center gap-1 relative z-30">
                            {{-- View Button --}}
                            <div class="relative group">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="w-9 h-9 flex items-center justify-center rounded-lg bg-transparent hover:bg-blue-100 transition-all duration-200 hover:shadow-md">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <span class="absolute z-[100] top-full mt-1 left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">View</span>
                            </div>
                            {{-- Archive Button --}}
                            <div class="relative group">
                                <button type="button" onclick="openArchiveModal('{{ route('admin.users.archive', $user->id) }}')" class="w-9 h-9 flex items-center justify-center rounded-lg bg-transparent hover:bg-gray-200 transition-all duration-200 hover:shadow-md" @if($isArchived) disabled style="opacity:0.5;cursor:not-allowed;" @endif>
                                    <svg class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                    </svg>
                                </button>
                                <span class="absolute z-[100] top-full mt-1 left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Archive</span>
                            </div>
                            {{-- Accept Button --}}
                            <div class="relative group inline-block">
                                <button type="button" onclick="openApproveModal('{{ route('admin.users.approve', $user->id) }}')" class="w-9 h-9 flex items-center justify-center rounded-lg bg-transparent hover:bg-emerald-100 transition-all duration-200 hover:shadow-md" title="Accept">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="9" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.5l2 2 4-4" />
                                    </svg>
                                </button>
                                <span class="absolute z-[100] top-full mt-1 left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Accept</span>
                            </div>
                            {{-- Reject Button --}}
                            <div class="relative group inline-block">
                                <button type="button" onclick="openRejectModal('{{ route('admin.users.reject', $user->id) }}')" class="w-9 h-9 flex items-center justify-center rounded-lg bg-transparent hover:bg-red-100 transition-all duration-200 hover:shadow-md" title="Reject">
                                    <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="9" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 9l6 6m0-6l-6 6" />
                                    </svg>
                                </button>
                                <span class="absolute z-[100] top-full mt-1 left-1/2 transform -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Reject</span>
                            </div>
                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-16 text-center text-gray-400 text-sm">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="font-medium">No users found</p>
                        <p class="text-xs mt-1">Try adjusting your search or filters</p>
                    </td>
                </tr>
                @endforelse

                </tbody>
            </table>
        </div>
        </div>
    </div>

    <!-- Pagination removed as requested -->
</main>

{{-- MODALS (Keep Z-index high for modal and backdrop) --}}

    <div id="archiveModal" class="modal-container hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] flex items-center justify-center z-[9999] pointer-events-none">
        <div class="bg-white w-[520px] h-[400px] rounded-3xl shadow-2xl overflow-hidden border-2 border-gray-100 transform transition-all pointer-events-auto">
            
            <!-- Content -->
            <div class="p-8 text-center flex flex-col justify-between h-full">
                <!-- Icon Badge -->
                <div class="flex justify-center">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-gray-50 to-gray-100 border-4 border-gray-400 flex items-center justify-center shadow-lg">
                        <svg class="w-12 h-12 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                </div>
                
                <!-- Title -->
                <h2 class="font-bold text-2xl mb-2 text-gray-800 tracking-tight">Archive User Account</h2>
                
                <!-- Description -->
                <div class="bg-gray-50 rounded-xl p-4 mb-4 border border-gray-200">
                    <p class="text-sm text-gray-700 leading-relaxed">
                        This action will temporarily deactivate the user's account. The user will not be able to access the system until the account is restored.
                    </p>
                </div>
                
                <!-- Action Buttons -->
                <form method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="flex justify-center gap-4">
                        <button type="button" onclick="closeModal('archiveModal')" 
                                class="px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-md border border-gray-300">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-lg transform hover:scale-105">
                            Archive User
                        </button>
                    </div> 
                </form>
            </div>
        </div>
    </div>

    <div id="approvedModal" class="modal-container hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] flex items-center justify-center z-[9999] pointer-events-none">
        <div class="bg-white w-[520px] h-[400px] rounded-3xl shadow-2xl overflow-hidden border-2 border-gray-100 transform transition-all pointer-events-auto">
            
            <!-- Content -->
            <div class="p-8 text-center flex flex-col justify-between h-full">
                <!-- Icon Badge -->
                <div class="flex justify-center">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-green-50 to-green-100 border-4 border-green-500 flex items-center justify-center shadow-lg">
                        <svg class="w-12 h-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                
                <!-- Title -->
                <h2 class="font-bold text-2xl mb-2 text-gray-800 tracking-tight">Approve Resident Registration</h2>
                
                <!-- Description -->
                <div class="bg-green-50 rounded-xl p-4 mb-4 border border-green-200">
                    <p class="text-sm text-gray-700 leading-relaxed">
                        This action will approve the resident's registration and grant them full access to the system.
                    </p>
                </div>
                
                <!-- Action Buttons -->
                <form method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="flex justify-center gap-4">
                        <button type="button" onclick="closeModal('approvedModal')" 
                                class="px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-md border border-gray-300">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-lg transform hover:scale-105">
                            Approve
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="rejectModal" class="modal-container hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] flex items-center justify-center z-[9999] pointer-events-none">
        <div class="bg-white w-[520px] h-[400px] rounded-3xl shadow-2xl overflow-hidden border-2 border-gray-100 transform transition-all pointer-events-auto">
            
            <!-- Content -->
            <div class="p-8 text-center flex flex-col justify-between h-full">
                <!-- Icon Badge -->
                <div class="flex justify-center">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-red-50 to-red-100 border-4 border-red-500 flex items-center justify-center shadow-lg">
                        <svg class="w-12 h-12 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="9" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 9l6 6m0-6l-6 6" />
                        </svg>
                    </div>
                </div>
                
                <!-- Title -->
                <h2 class="font-bold text-2xl mb-2 text-gray-800 tracking-tight">Reject Registration</h2>
                
                <!-- Description -->
                <div class="bg-red-50 rounded-xl p-4 mb-4 border border-red-200">
                    <p class="text-sm text-gray-700 leading-relaxed">
                        This action will permanently reject the user's registration. This cannot be undone.
                    </p>
                </div>
                
                <!-- Action Buttons -->
                <form method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="flex justify-center gap-4">
                        <button type="button" onclick="closeModal('rejectModal')" 
                                class="px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-md border border-gray-300">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-lg transform hover:scale-105">
                            Reject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

{{-- Shared Backdrop for All Modals --}}
<div id="modal-backdrop" class="hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] bg-black/50 backdrop-blur-sm z-[9998]"></div>

<script>
    function openArchiveModal(actionUrl) {
        const modal = document.getElementById('archiveModal');
        const backdrop = document.getElementById('modal-backdrop');
        modal.classList.remove('hidden');
        backdrop.classList.remove('hidden');
        // Set form action
        const form = modal.querySelector('form');
        form.action = actionUrl;
    }
    function openApproveModal(actionUrl) {
        const modal = document.getElementById('approvedModal');
        const backdrop = document.getElementById('modal-backdrop');
        modal.classList.remove('hidden');
        backdrop.classList.remove('hidden');
        // Set form action
        const form = modal.querySelector('form');
        form.action = actionUrl;
    }
    function openRejectModal(actionUrl) {
        const modal = document.getElementById('rejectModal');
        const backdrop = document.getElementById('modal-backdrop');
        modal.classList.remove('hidden');
        backdrop.classList.remove('hidden');
        // Set form action
        const form = modal.querySelector('form');
        form.action = actionUrl;
    }
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.getElementById('modal-backdrop').classList.add('hidden');
    }
</script>
@endsection