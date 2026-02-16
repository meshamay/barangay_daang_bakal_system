@extends('admin.layouts.app')

@section('content')
<main class="flex-1 p-8 fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] overflow-y-auto bg-gradient-to-br from-gray-50 to-gray-100">
    
    <div class="max-w-full">
    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-[#134573] to-[#0f3a5f] bg-clip-text text-transparent">Audit Logs</h1>
        <p class="text-gray-600">Track and monitor all system activities and user actions</p>
    </div>

    {{-- Filter Section --}}
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 mb-8 border border-gray-100">
        <div class="flex flex-wrap items-end gap-4">
            
            {{-- Date Range Filter --}}
            <form method="GET" action="{{ route('admin.auditlogs.index') }}" class="flex items-end gap-4">
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-700 mb-2">From Date</label>
                    <input
                        type="date"
                        name="from_date"
                        value="{{ request('from_date') }}"
                        class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                    >
                </div>

                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-700 mb-2">To Date</label>
                    <input
                        type="date"
                        name="to_date"
                        value="{{ request('to_date') }}"
                        class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                    >
                </div>

                <button
                    type="submit"
                    class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg text-sm font-medium hover:from-green-700 hover:to-green-800 transform hover:scale-105 transition-all duration-200 shadow-md hover:shadow-lg">
                    Apply Filter
                </button>
            </form>

            {{-- Role Filter --}}
            <form method="GET" action="{{ route('admin.auditlogs.index') }}" class="flex flex-col">
                <label class="text-sm font-medium text-gray-700 mb-2">Role</label>
                <select 
                    name="role" 
                    onchange="this.form.submit()" 
                    class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white cursor-pointer">
                    <option value="">All Roles</option>
                    <option value="resident" {{ request('role') == 'resident' ? 'selected' : '' }}>Resident</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                </select>
            </form>

            {{-- Clear Filter --}}
            @if(request('from_date') || request('to_date') || request('role'))
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-700 mb-2 opacity-0">Clear</label>
                    <a href="{{ route('admin.auditlogs.index') }}" class="px-6 py-2.5 flex items-center gap-2 text-sm border border-gray-300 rounded-lg bg-white hover:bg-gray-50 text-gray-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Clear
                    </a>
                </div>
            @endif

        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%); color: white;">
                    <tr>
                        <th class="py-4 px-6 text-center text-xs font-semibold uppercase tracking-wider">User ID</th>
                        <th class="py-4 px-6 text-center text-xs font-semibold uppercase tracking-wider">Last Name</th>
                        <th class="py-4 px-6 text-center text-xs font-semibold uppercase tracking-wider">First Name</th>
                        <th class="py-4 px-6 text-center text-xs font-semibold uppercase tracking-wider">Role</th>
                        <th class="py-4 px-6 text-center text-xs font-semibold uppercase tracking-wider">Date</th>
                        <th class="py-4 px-6 text-center text-xs font-semibold uppercase tracking-wider">Time</th>
                        <th class="py-4 px-6 text-center text-xs font-semibold uppercase tracking-wider">Action Performed</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($auditLogs as $log)
                    <tr class="hover:bg-blue-50/70 transition-all duration-200 ease-in-out">
                        <td class="py-4 px-6 text-sm font-medium text-gray-900 text-center">{{ $log->user->resident_id ?? 'N/A' }}</td>
                        <td class="py-4 px-6 text-sm text-gray-700 text-center">{{ $log->user->last_name ?? 'N/A' }}</td>
                        <td class="py-4 px-6 text-sm text-gray-700 text-center">{{ $log->user->first_name ?? 'N/A' }}</td>
                        <td class="py-4 px-6 text-sm text-center">
                            @php
                                $role = $log->user->role ?? $log->user->user_type ?? 'N/A';
                                $roleLabel = match(strtolower($role)) {
                                    'superadmin', 'super admin', 'super_admin' => 'Super Admin',
                                    'admin' => 'Admin',
                                    'user', 'resident' => 'Resident',
                                    default => ucfirst($role)
                                };
                                $roleColor = match(strtolower($role)) {
                                    'superadmin', 'super admin', 'super_admin' => 'bg-purple-100 text-purple-800',
                                    'admin' => 'bg-blue-100 text-blue-800',
                                    'user', 'resident' => 'bg-green-100 text-green-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $roleColor }}">
                                {{ $roleLabel }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-sm text-gray-600 text-center">{{ $log->created_at->setTimezone('Asia/Manila')->format('F d, Y') }}</td>
                        <td class="py-4 px-6 text-sm text-gray-600 text-center">{{ $log->created_at->setTimezone('Asia/Manila')->format('g:i A') }}</td>
                        <td class="py-4 px-6 text-sm text-gray-600 text-center">{{ $log->action }}@if($log->description) - {{ $log->description }}@endif</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-16 px-6 text-center">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-gray-400 text-base font-medium">No audit logs found</p>
                            <p class="text-gray-400 text-sm mt-1">Try adjusting your filters</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-8 flex justify-end pb-8">
        {{ $auditLogs->links() }}
    </div>
    
    </div>
</main>
@endsection
