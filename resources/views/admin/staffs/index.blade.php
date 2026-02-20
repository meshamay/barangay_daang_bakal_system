@extends('admin.layouts.app')


@section('content')

@push('styles')
	<link rel="stylesheet" href="{{ asset('css/admin-modals.css') }}">
@endpush

<style>
</style>

<main class="flex-1 p-8 fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
	<div class="flex items-center justify-between mb-8">
		<div>
			<h1 class="text-4xl font-bold bg-gradient-to-r from-[#134573] to-[#0f3a5f] bg-clip-text text-transparent">STAFF MANAGEMENT</h1>
			<p class="text-gray-500 text-sm mt-1">Manage and organize barangay staff members.</p>
		</div>
	</div>

	{{-- Search and Filter --}}
	<div class="flex justify-between items-center mb-6 gap-4">
		<form method="GET" action="{{ route('admin.staffs.index') }}" class="flex items-center gap-3 flex-1">
			<div class="relative w-full max-w-md">
				<div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
					<svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
						<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
					</svg>
				</div>
				<input type="text" name="search" value="{{ request('search') }}" placeholder="Search staff..." class="w-full h-10 border border-gray-200 rounded-lg pl-11 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition" />
			</div>

			<div class="flex gap-2 w-fit">
				<select name="status" onchange="this.form.submit()" class="h-10 px-4 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition bg-white w-32">
					<option value="" disabled="" {{ !request('status') ? 'selected' : '' }}>Status</option>
					<option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
					<option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
				</select>

				@if(request('search') || request('status'))
					<a href="{{ route('admin.staffs.index') }}" class="h-10 px-4 flex items-center gap-2 text-sm border border-gray-300 rounded-lg bg-white hover:bg-gray-50 text-gray-700 transition">
						<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
						</svg>
						Clear
					</a>
				@endif
			</div>
		</form>

	<button onclick="openModal('addStaffModal')" class="flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold px-6 py-2.5 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
		<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
			<path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
		</svg>
		<span class="text-sm">Add Staff</span>
	</button>
	</div>


	{{-- Table --}}
	<div class="bg-white shadow-xl rounded-xl overflow-hidden h-[calc(100vh-380px)] overflow-y-auto border border-gray-100">
		<table class="w-full text-sm">
			<thead style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%); color: white; position: sticky; top: 0; z-index: 10;" class="shadow-sm">
				<tr class="text-xs font-semibold uppercase tracking-widest text-center">
					<th class="py-5 px-6">Staff ID</th>
					<th class="py-5 px-6">Last Name</th>
					<th class="py-5 px-6">First Name</th>
					<th class="py-5 px-6">Role</th>
					<th class="py-5 px-6">Date Registered</th>
					<th class="py-5 px-6">Status</th>
					<th class="py-5 px-6">Action</th>
				</tr>
			</thead>
			<tbody class="divide-y divide-gray-100">
				@forelse($staff as $member)
				<tr class="hover:bg-blue-50/70 transition-colors duration-150 ease-in-out text-center">
					<td class="py-5 px-6 font-semibold text-gray-900">{{ $member->resident_id ?? 'N/A' }}</td>
					<td class="py-5 px-6 text-gray-700">{{ $member->last_name }}</td>
					<td class="py-5 px-6 text-gray-700">{{ $member->first_name }}</td>
                    <td class="py-5 px-6 text-gray-600">
                        @php
                            $roleRaw = strtolower($member->role ?? '');
                            $userTypeRaw = strtolower($member->user_type ?? '');
                            $isSuper = in_array($roleRaw, ['super admin','super_admin','superadmin'])
                                || in_array($userTypeRaw, ['super admin','super_admin','superadmin']);
                            $roleLabel = $isSuper ? 'Super Admin' : 'Admin';
                        @endphp
                        {{ $roleLabel }}
                    </td>
					<td class="py-5 px-6 text-gray-600 text-sm">{{ $member->created_at->format('d/m/Y') }}</td>
					<td class="py-5 px-6">
						@php
						    $statusRaw = strtolower($member->status ?? '');
						    $isActive = in_array($statusRaw, ['approved','active']);
						    $badge = $isActive ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-gray-100 text-gray-800 border border-gray-300';
						    $label = $isActive ? 'Active' : 'Inactive';
						@endphp
						<span class="{{ $badge }} text-xs font-bold px-3 py-2 rounded-full inline-block shadow-sm">{{ $label }}</span>
					</td>
					<td class="py-5 px-6 text-center">
						<div class="flex items-center justify-center gap-2">
                            <div class="relative group">
                                    <button
                                        type="button"
                                        onclick="viewStaff(this)"
                                        data-last-name="{{ $member->last_name }}"
                                        data-first-name="{{ $member->first_name }}"
                                        data-username="{{ $member->username }}"
                                        data-password="{{ $member->plain_password }}"
                                        data-password-hash="{{ $member->password }}"
                                        data-date-created="{{ $member->created_at->format('d/m/Y') }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-blue-50 transition-all duration-200 hover:shadow-md">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <span class="absolute top-full mt-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 rounded whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity duration-200 z-50">View Staff</span>
                            </div>
                            <div class="relative group">
                                <button onclick="editStaff('{{ $member->id }}','{{ $member->last_name }}','{{ $member->first_name }}','{{ $member->username }}','{{ $member->created_at->format('d/m/Y') }}')" class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-gray-50 transition-all duration-200 hover:shadow-md text-gray-600 hover:text-gray-800">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <span class="absolute top-full mt-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 rounded whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity duration-200 z-50">Edit Staff</span>
                            </div>
                            <div class="relative group">
                                <button onclick="prepareDeactivate({{ $member->id }})" class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-red-50 transition-all duration-200 hover:shadow-md text-red-600 hover:text-red-700">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                                <span class="absolute top-full mt-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 rounded whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity duration-200 z-50">Delete Staff</span>
                            </div>
                        </div>
                    </td>
				</tr>
				@empty
				<tr>
					<td colspan="7" class="py-6 px-5 text-center text-gray-500">No staff members found.</td>
				</tr>
				@endforelse
			</tbody>
		</table>
	</div>

</main>

<!-- ADD STAFF MODAL -->
<div id="addStaffModal" class="modal-container hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] flex items-center justify-center z-[9999]">
    <div class="bg-white w-[700px] rounded-3xl shadow-2xl overflow-hidden border-2 border-gray-100">
        <div class="px-6 py-4 flex items-center gap-3" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
            <h2 class="text-white font-bold text-xl tracking-wide font-['Barlow_Semi_Condensed']">Add New Staff</h2>
        </div>
        <div class="p-8">
            <form action="{{ route('admin.staffs.store') }}" method="POST" novalidate>
                @csrf
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name:</label>
                        <input type="text" name="last_name" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">First Name:</label>
                        <input type="text" name="first_name" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">User Name:</label>
                        <input type="text" name="username" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email:</label>
                        <input type="email" name="email" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password:</label>
                        <div class="relative">
                            <input type="password" id="addPassword" name="password" class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 pr-12 bg-gray-50 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" required>
                            <button type="button" onclick="togglePasswordVisibility('addPassword')" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-600 transition duration-200 p-1">
                                <svg id="addPassword-eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="addPassword-eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password:</label>
                        <div class="relative">
                            <input type="password" id="addPasswordConfirmation" name="password_confirmation" class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 pr-12 bg-gray-50 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" required>
                            <button type="button" onclick="togglePasswordVisibility('addPasswordConfirmation')" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-600 transition duration-200 p-1">
                                <svg id="addPasswordConfirmation-eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="addPasswordConfirmation-eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal('addStaffModal')" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-all duration-200 border border-gray-300 hover:shadow-md">CANCEL</button>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-lg transform hover:scale-105">ADD</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- VIEW STAFF MODAL -->
<div id="viewStaffModal" class="modal-container hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] flex items-center justify-center z-[9999]">
    <div class="bg-white w-[650px] rounded-3xl shadow-2xl overflow-hidden border-2 border-gray-100">
        <div class="px-6 py-4 flex items-center gap-3" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
            <h2 class="text-white font-bold text-xl tracking-wide font-['Barlow_Semi_Condensed']">Staff Details</h2>
        </div>
        <div class="p-8">
            <div class="grid grid-cols-2 gap-5 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name:</label>
                    <input type="text" id="viewLastName" readonly class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">First Name:</label>
                    <input type="text" id="viewFirstName" readonly class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-5 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">User Name:</label>
                    <input type="text" id="viewUsername" readonly class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password:</label>
                    <div class="relative">
                        <input type="password" id="viewPassword" readonly class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 pr-12 bg-gray-50 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" value="••••••••">
                        <button type="button" onclick="togglePasswordVisibility('viewPassword')" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-600 transition duration-200 p-1">
                            <svg id="viewPassword-eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg id="viewPassword-eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-5 mb-8">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date Created:</label>
                    <input type="text" id="viewDateCreated" readonly class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700">
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('viewStaffModal')" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-all duration-200 border border-gray-300 hover:shadow-md">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- EDIT STAFF MODAL -->
<div id="editStaffModal" class="modal-container hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] flex items-center justify-center z-[9999]">
    <div class="bg-white w-[650px] rounded-3xl shadow-2xl overflow-hidden border-2 border-gray-100">
        <div class="px-6 py-4 flex items-center gap-3" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
            <h2 class="text-white font-bold text-xl tracking-wide font-['Barlow_Semi_Condensed']">Edit Staff Member</h2>
        </div>
        <div class="p-8">
            <form id="editStaffForm" action="" method="POST" novalidate>
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-5 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name:</label>
                        <input type="text" id="editLastName" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">First Name:</label>
                        <input type="text" id="editFirstName" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">User Name:</label>
                    <input type="text" id="editUsername" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>
                <div class="grid grid-cols-2 gap-5 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password:</label>
                        <div class="relative">
                            <input type="password" id="editPassword" placeholder="Leave blank to keep current" class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 pr-12 bg-gray-50 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            <button type="button" onclick="togglePasswordVisibility('editPassword')" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-600 transition duration-200 p-1">
                                <svg id="editPassword-eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="editPassword-eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password:</label>
                        <div class="relative">
                            <input type="password" id="editPasswordConfirm" placeholder="Confirm new password" class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 pr-12 bg-gray-50 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            <button type="button" onclick="togglePasswordVisibility('editPasswordConfirm')" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-600 transition duration-200 p-1">
                                <svg id="editPasswordConfirm-eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="editPasswordConfirm-eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-5 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Date Created:</label>
                        <input type="text" id="editDateCreated" readonly class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700">
                    </div>
                    <div></div>
                </div>

                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" onclick="closeModal('editStaffModal')" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-all duration-200 border border-gray-300 hover:shadow-md">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-lg transform hover:scale-105">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- DEACTIVATE STAFF MODAL -->
<div id="deleteModal" class="modal-container hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] flex items-center justify-center z-[9999]">
    <div class="bg-white w-[520px] rounded-3xl shadow-2xl p-10 text-center border-2 border-gray-100 transform transition-all">
        
        <!-- Icon Badge -->
        <div class="flex justify-center mb-6">
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-red-50 to-red-100 border-4 border-red-500 flex items-center justify-center shadow-lg">
                <svg class="w-12 h-12 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </div>
        </div>
        
        <!-- Title -->
        <h2 class="font-bold text-2xl mb-4 text-gray-800 tracking-tight">Delete Staff Member</h2>
        
        <!-- Description -->
        <div class="bg-red-50 rounded-xl p-4 mb-6 border border-red-200">
            <p class="text-sm text-gray-700 leading-relaxed">
                Are you sure you want to deactivate this staff member? Their status will be set to inactive and they won't be able to log in.
            </p>
        </div>

        <form id="deactivateForm" method="POST" action="">
            @csrf
            @method('PATCH')
            <div class="flex justify-center gap-4 mt-6">
                <button type="button" onclick="closeModal('deleteModal')" class="px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-md border border-gray-300">Cancel</button>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-lg transform hover:scale-105">Delete Staff</button>
            </div>
        </form>
    </div>
</div>

{{-- Shared Backdrop for All Modals --}}
<div id="modal-backdrop" class="hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] bg-black/50 backdrop-blur-sm z-[9998]"></div>

@endsection

@push('scripts')
<script src="{{ asset('js/admin-staffs.js') }}" defer></script>
@endpush

