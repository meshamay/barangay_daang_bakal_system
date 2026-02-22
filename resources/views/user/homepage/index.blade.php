@extends('layouts.user', ['title' => 'User Homepage'])

@section('content')

    <div class="relative overflow-hidden rounded-xl sm:rounded-2xl border border-blue-100/50 shadow-lg group" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-72 h-72 bg-white/5 rounded-full blur-3xl"></div>
        <div class="relative p-4 sm:p-6 md:p-12">
            <h1 class="text-2xl sm:text-3xl md:text-5xl lg:text-6xl font-bold mb-3 sm:mb-4 text-white">Welcome back, {{ $user->first_name }}! 👋</h1>
            <p class="text-blue-100 text-sm sm:text-base md:text-lg leading-relaxed max-w-full font-medium overflow-hidden">
                Access your barangay services quickly. Manage requests, file complaints, and view your history all in one place.
            </p>

            <br>

            <div class="flex flex-wrap gap-3 sm:gap-4 md:gap-10 text-xs sm:text-sm font-semibold text-white inline-flex px-3 sm:px-5 md:px-7 py-3 sm:py-4 rounded-lg sm:rounded-xl shadow-lg backdrop-blur-md border border-white/30 bg-white/10">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M11.54 22.351l-5.225-5.225a3.125 3.125 0 010-4.418l5.225-5.225a3.125 3.125 0 014.418 0l5.225 5.225a3.125 3.125 0 010 4.418l-5.225 5.225a3.125 3.125 0 01-4.418 0z" clip-rule="evenodd" />
                        <path d="M12 13a3 3 0 100-6 3 3 0 000 6z" />
                    </svg>
                    <span class="truncate max-w-[200px] sm:max-w-none">{{ $user->address ?? 'No address set' }}</span>
                </div>

                <div class="w-px h-4 bg-slate-400 hidden md:block"></div>

                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
                    </svg>
                    <span class="tracking-wider text-white font-bold">{{ $user->resident_id ?? 'RS-00000' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8 mt-4 sm:mt-6 md:mt-8">

        <div class="lg:col-span-2 space-y-4 mt-3 sm:mt-4 md:mt-6">
            <div class="flex items-center justify-between mb-3 sm:mb-4 md:mb-5">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-slate-800 flex items-center gap-2">
                    <div class="w-7 h-7 sm:w-8 sm:h-8 md:w-9 md:h-9 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-md">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    Recent Activity
                </h2>
                {{-- Note: This link defaults to document requests, user can navigate elsewhere --}}
                <a href="{{ route('user.document-requests.index') }}" class="text-xs sm:text-sm text-blue-600 font-semibold hover:text-blue-700 flex items-center gap-1 transition-colors">
                    View All
                    <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="bg-white rounded-lg sm:rounded-xl shadow-md border border-gray-100 overflow-hidden max-h-[20rem] sm:max-h-[24rem] md:max-h-[28rem]">
                <div class="divide-y divide-gray-200/50 overflow-y-auto max-h-[20rem] sm:max-h-[24rem] md:max-h-[28rem]">
                    @forelse ($recentActivities as $activity)
                        
                        {{-- ================================================= --}}
                        {{--  1. RENDER DOCUMENT REQUEST                       --}}
                        {{-- ================================================= --}}
                        @if ($activity instanceof \App\Models\DocumentRequest)
                            <a href="{{ route('user.document-requests.index') }}" class="p-3 sm:p-4 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200 group block border-l-2 sm:border-l-4 border-l-blue-500">
                                <div class="flex justify-between items-start gap-2">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors text-xs sm:text-sm truncate">{{ str_replace(['Certificate of Indigency', 'Certificate of Residency'], ['Indigency Clearance', 'Resident Certificate'], $activity->document_type) }}</h3>
                                        <div class="flex items-center gap-1 sm:gap-2 mt-1 sm:mt-1.5 text-xs sm:text-sm text-slate-500 flex-wrap">
                                            <span class="font-mono bg-blue-50 px-1.5 sm:px-2 py-0.5 rounded text-blue-700 font-semibold text-xs">{{ $activity->tracking_number }}</span>
                                            <span>&bull;</span>
                                            <span>{{ $activity->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                    {{-- Status Badge for Documents --}}
                                    <span @class([
                                        'inline-flex items-center px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-xs font-bold flex-shrink-0',
                                        'bg-amber-500/10 text-amber-600' => strtolower($activity->status) == 'pending',
                                        'bg-green-500/10 text-green-600' => strtolower($activity->status) == 'completed',
                                        'bg-red-500/10 text-red-600' => strtolower($activity->status) == 'rejected',
                                        'bg-blue-100 text-blue-800 text-xs font-bold px-3 py-2 rounded-full inline-block whitespace-nowrap shadow-sm' => strtolower($activity->status) == 'in progress',
                                    ])>
                                        {{ ucwords($activity->status) }}
                                    </span>
                                </div>
                            </a>

                        {{-- ================================================= --}}
                        {{--  2. RENDER COMPLAINT REQUEST                      --}}
                        {{-- ================================================= --}}
                        @elseif ($activity instanceof \App\Models\Complaint)
                            <a href="{{ route('user.complaints.index') }}" class="p-3 sm:p-4 hover:bg-gradient-to-r hover:from-red-50 hover:to-pink-50 transition-all duration-200 group block border-l-2 sm:border-l-4 border-l-red-500">
                                <div class="flex justify-between items-start gap-2">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-slate-800 group-hover:text-red-600 transition-colors text-xs sm:text-sm truncate">{{ str_replace('Physical Harrasments', 'Physical Harassment', $activity->complaint_type) }}</h3>
                                        <div class="flex items-center gap-1 sm:gap-2 mt-1 sm:mt-1.5 text-xs sm:text-sm text-slate-500 flex-wrap">
                                            {{-- 🚀 FIX: Changed $activity->id to $activity->transaction_no --}}
                                            <span class="font-mono bg-red-50 px-1.5 sm:px-2 py-0.5 rounded text-red-700 font-semibold text-xs">{{ $activity->transaction_no }}</span>
                                            <span>&bull;</span>
                                            <span>{{ $activity->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                    
                                    {{-- 🚀 FIX: Adjusted Status Logic to match DB (Capitalized) --}}
                                    <span @class([
                                        'inline-flex items-center px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-xs font-bold flex-shrink-0',
                                        // Pending Status
                                        'bg-amber-500/10 text-amber-600' => $activity->status === 'Pending',
                                        // Completed Status
                                        'bg-green-500/10 text-green-600' => $activity->status === 'Completed',
                                        // Fallback/Default
                                        'bg-gray-500/10 text-gray-600' => !in_array($activity->status, ['Pending', 'In Progress', 'Completed']),
                                    ])
                                    @if($activity->status === 'In Progress') style="background-color: rgba(14, 165, 233, 0.12); color: #0284c7;" @endif>
                                        {{ ucwords($activity->status) }}
                                    </span>
                                </div>
                            </a>
                        @endif

                    @empty
                        <div class="p-6 sm:p-8 md:p-12 text-center">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <p class="text-slate-500 font-medium text-sm sm:text-base">No recent activity to show.</p>
                            <p class="text-slate-400 text-xs sm:text-sm mt-1">Start by requesting a document or filing a complaint.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-4 sm:space-y-5 md:space-y-6">
            <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-slate-800 flex items-center gap-2 sm:gap-3">
                <div class="w-8 h-8 sm:w-9 sm:h-9 md:w-10 md:h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center shadow-md">
                    <svg class="w-5 h-5 sm:w-5.5 sm:h-5.5 md:w-6 md:h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                Quick Actions
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-3 sm:gap-4 md:gap-5">
                <a href="{{ route('user.document-requests.index') }}" class="group block p-4 sm:p-5 md:p-7 bg-gradient-to-br from-white to-blue-50 rounded-xl sm:rounded-2xl shadow-md border border-blue-100 hover:border-blue-300 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-6 -mr-6 w-16 h-16 sm:w-20 sm:h-20 bg-blue-500 opacity-10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative">
                        <div class="w-12 h-12 sm:w-13 sm:h-13 md:w-14 md:h-14 bg-gradient-to-br from-blue-100 to-blue-50 rounded-lg sm:rounded-xl flex items-center justify-center text-blue-600 mb-3 sm:mb-4 group-hover:bg-gradient-to-br group-hover:from-blue-500 group-hover:to-blue-600 group-hover:text-white transition-all duration-300 shadow-md">
                            <svg class="w-5 h-5 sm:w-5.5 sm:h-5.5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-slate-800 group-hover:text-blue-600 transition-colors">Request a Document</h3>
                        <p class="text-xs sm:text-sm text-slate-600 mt-1.5 sm:mt-2 leading-relaxed">Get certificates, clearances and other documents online.</p>
                    </div>
                </a>

                <a href="{{ route('user.complaints.index') }}" class="group block p-4 sm:p-5 md:p-7 bg-gradient-to-br from-white to-red-50 rounded-xl sm:rounded-2xl shadow-md border border-red-100 hover:border-red-300 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-6 -mr-6 w-16 h-16 sm:w-20 sm:h-20 bg-red-500 opacity-10 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                    <div class="relative">
                        <div class="w-12 h-12 sm:w-13 sm:h-13 md:w-14 md:h-14 bg-gradient-to-br from-red-100 to-red-50 rounded-lg sm:rounded-xl flex items-center justify-center text-red-600 mb-3 sm:mb-4 group-hover:bg-gradient-to-br group-hover:from-red-500 group-hover:to-red-600 group-hover:text-white transition-all duration-300 shadow-md">
                            <svg class="w-5 h-5 sm:w-5.5 sm:h-5.5 md:w-6 md:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-slate-800 group-hover:text-red-600 transition-colors">File a Complaint</h3>
                        <p class="text-xs sm:text-sm text-slate-600 mt-1.5 sm:mt-2 leading-relaxed">Report issues and concerns in the barangay.</p>
                    </div>
                </a>
            </div>
        </div>

    </div>
@endsection