@extends('admin.layouts.app')


@section('content')

<style>
    #modal-backdrop {
        position: fixed;
        top: 80px;
        left: 240px;
        width: calc(100vw - 240px);
        height: calc(100vh - 80px);
        background: rgba(0, 0, 0, 0.35);
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(2px);
        z-index: 110;
        pointer-events: auto;
    }

    .modal-container {
        filter: none !important;
        pointer-events: auto;
        z-index: 120;
    }
</style>

<main class="flex-1 p-8 fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-[#134573] to-[#0f3a5f] bg-clip-text text-transparent">ANNOUNCEMENTS</h1>
            <p class="text-gray-500 text-sm mt-1">Create, track, and manage barangay announcements.</p>
        </div>
        <button onclick="openModal('addAnnouncementModal')" class="flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold px-6 py-2.5 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 transform hover:scale-105">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
            </svg>
            <span class="text-sm font-semibold">Add Announcement</span>
        </button>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg hover:border-blue-200 transition duration-300 ease-in-out">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Announcements</p>
                <p class="text-4xl font-bold text-gray-900 mt-2">{{ $totalAnnouncements }}</p>
            </div>
            <div class="bg-blue-100 p-4 rounded-lg">
                <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg hover:border-emerald-200 transition duration-300 ease-in-out">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Ongoing</p>
                <p class="text-4xl font-bold text-gray-900 mt-2">{{ $ongoingCount }}</p>
            </div>
            <div class="bg-emerald-100 p-4 rounded-lg">
                <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg hover:border-red-200 transition duration-300 ease-in-out">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium">Ended</p>
                <p class="text-4xl font-bold text-gray-900 mt-2">{{ $endedCount }}</p>
            </div>
            <div class="bg-red-100 p-4 rounded-lg">
                <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
      </div>
    </div>

    {{-- Search and Filter --}}
    <div class="flex justify-between items-center mb-6 gap-4">
        <form method="GET" action="{{ route('admin.announcements.index') }}" class="flex items-center gap-3 flex-1">
            <div class="relative w-full max-w-md">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title or content..." class="w-full h-10 border border-gray-200 rounded-lg pl-11 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent transition" />
            </div>

            <div class="flex gap-2 w-fit">
                <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()" class="h-10 px-4 text-sm bg-white border border-gray-200 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-400" />
                <select name="status" onchange="this.form.submit()" class="h-10 px-4 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 transition bg-white w-40">
                    <option value="">Status</option>
                    <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="ended" {{ request('status') === 'ended' ? 'selected' : '' }}>Ended</option>
                </select>

                @if(request('search') || request('date') || request('status'))
                    <a href="{{ route('admin.announcements.index') }}" class="h-10 px-4 flex items-center gap-2 text-sm border border-gray-300 rounded-lg bg-white hover:bg-gray-50 text-gray-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white shadow-xl rounded-xl border border-gray-100 overflow-hidden">
        {{-- Fixed Header --}}
        <table class="w-full text-sm" style="table-layout: fixed;">
            <thead style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%); color: white;" class="shadow-sm">
                <tr class="text-xs font-semibold uppercase tracking-widest text-center">
                    <th class="py-5 px-6 w-1/5">Title</th>
                    <th class="py-5 px-6 w-1/5">Start Date</th>
                    <th class="py-5 px-6 w-1/5">End Date</th>
                    <th class="py-5 px-6 w-1/5">Status</th>
                    <th class="py-5 px-6 w-1/5">Action</th>
                </tr>
            </thead>
        </table>
        {{-- Scrollable Body --}}
        <div class="overflow-x-auto overflow-y-auto max-h-[360px]">
            <table class="w-full text-sm" style="table-layout: fixed;">
                <tbody class="divide-y divide-gray-100">
                    @forelse($announcements as $announcement)
                    <tr class="hover:bg-blue-50/70 transition-colors duration-150 ease-in-out text-center">
                        <td class="py-5 px-6 w-1/5 font-semibold text-gray-900">{{ $announcement->title }}</td>
                        <td class="py-5 px-6 w-1/5 text-gray-600 text-sm">{{ $announcement->start_date->format('d/m/Y') }}</td>
                        <td class="py-5 px-6 w-1/5 text-gray-600 text-sm">{{ $announcement->end_date ? $announcement->end_date->format('d/m/Y') : 'N/A' }}</td>
                        <td class="py-5 px-6 w-1/5">
                            @php
                                $displayStatus = $announcement->display_status ?? 'Ongoing';
                                $statusKey = strtolower($displayStatus);
                                $statusColors = [
                                    'ongoing' => 'bg-emerald-100 text-emerald-800 border border-emerald-300',
                                    'ended' => 'bg-red-100 text-red-800 border border-red-300',
                                    'upcoming' => 'bg-blue-100 text-blue-800 border border-blue-300',
                                    'inactive' => 'bg-gray-100 text-gray-800 border border-gray-300',
                                ];
                                $colorClass = $statusColors[$statusKey] ?? 'bg-gray-100 text-gray-800 border border-gray-300';
                            @endphp
                            <span class="{{ $colorClass }} text-xs font-bold px-3 py-2 rounded-full inline-block shadow-sm">{{ $displayStatus }}</span>
                        </td>
                        <td class="py-5 px-6 w-1/5 text-center">
                            <div class="flex justify-center items-center gap-2">
                                <div class="relative group">
                                    <button onclick="openViewModal(this)" 
                                        data-title="{{ $announcement->title }}"
                                        data-content="{{ $announcement->content }}"
                                        data-start="{{ $announcement->start_date->format('d/m/Y') }}"
                                        data-end="{{ $announcement->end_date ? $announcement->end_date->format('d/m/Y') : 'N/A' }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-blue-50 transition-all duration-200 hover:shadow-md">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <span class="absolute top-full mt-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity">View</span>
                                </div>
                                <div class="relative group">
                                    <button onclick="openEditModal(this)" 
                                        data-id="{{ $announcement->id }}"
                                        data-title="{{ $announcement->title }}"
                                        data-content="{{ $announcement->content }}"
                                        data-start="{{ $announcement->start_date->format('Y-m-d') }}"
                                        data-end="{{ $announcement->end_date ? $announcement->end_date->format('Y-m-d') : '' }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-gray-50 transition-all duration-200 hover:shadow-md text-gray-600 hover:text-gray-800">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <span class="absolute top-full mt-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-800 rounded opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity">Edit</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-6 px-5 text-center text-gray-500">No announcements found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>

{{-- Shared Backdrop for All Modals --}}
<div id="modal-backdrop" class="hidden"></div>

<!-- ADD ANNOUNCEMENT MODAL -->
<div id="addAnnouncementModal" class="modal-container hidden fixed top-0 left-0 w-full h-full flex items-center justify-center z-[120]" style="left: 240px; width: calc(100vw - 240px); top: 80px; height: calc(100vh - 80px);">
    <div class="bg-white w-[700px] rounded-3xl shadow-2xl overflow-hidden border-2 border-gray-100">
        <div class="px-6 py-4 flex items-center gap-3" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
            <h2 class="text-white font-bold text-xl tracking-wide font-['Barlow_Semi_Condensed']">Add New Announcement</h2>
        </div>
        <div class="p-8">
            <form action="{{ route('admin.announcements.store') }}" method="POST" novalidate>
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Title:</label>
                        <input type="text" name="title" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description:</label>
                        <textarea name="content" class="w-full h-32 rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700 resize-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Start Date:</label>
                            <input type="date" name="start_date" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">End Date:</label>
                            <input type="date" name="end_date" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" onclick="closeModal('addAnnouncementModal')" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-all duration-200 border border-gray-300 hover:shadow-md">CANCEL</button>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-lg transform hover:scale-105">ADD</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- VIEW ANNOUNCEMENT MODAL -->
<div id="viewAnnouncementModal" class="modal-container hidden fixed top-0 left-0 w-full h-full flex items-center justify-center z-[120]" style="left: 240px; width: calc(100vw - 240px); top: 80px; height: calc(100vh - 80px);">
    <div class="bg-white w-[700px] rounded-3xl shadow-2xl overflow-hidden border-2 border-gray-100">
        <div class="px-6 py-4 flex items-center gap-3" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
            <h2 class="text-white font-bold text-xl tracking-wide font-['Barlow_Semi_Condensed']">Announcement Details</h2>
        </div>
        <div class="p-8">
            <form action="" method="POST" novalidate>
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Title:</label>
                        <input type="text" id="viewTitle" readonly class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description:</label>
                        <textarea id="viewContent" readonly class="w-full h-32 rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700 resize-none"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Start Date:</label>
                            <input type="text" id="viewStartDate" readonly class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">End Date:</label>
                            <input type="text" id="viewEndDate" readonly class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" onclick="closeModal('viewAnnouncementModal')" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-all duration-200 border border-gray-300 hover:shadow-md">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- EDIT ANNOUNCEMENT MODAL  -->
<div id="editAnnouncementModal" class="modal-container hidden fixed top-0 left-0 w-full h-full flex items-center justify-center z-[120]" style="left: 240px; width: calc(100vw - 240px); top: 80px; height: calc(100vh - 80px);">
    <div class="bg-white w-[700px] rounded-3xl shadow-2xl overflow-hidden border-2 border-gray-100">
        <div class="px-6 py-4 flex items-center gap-3" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
            <h2 class="text-white font-bold text-xl tracking-wide font-['Barlow_Semi_Condensed']">Edit Announcement Details</h2>
        </div>
        <div class="p-8">
            <form id="editAnnouncementForm" action="" method="POST" novalidate>
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Title:</label>
                        <input type="text" name="title" id="editTitle" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description:</label>
                        <textarea name="content" id="editContent" class="w-full h-32 rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700 resize-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Start Date:</label>
                            <input type="date" name="start_date" id="editStartDate" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">End Date:</label>
                            <input type="date" name="end_date" id="editEndDate" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" onclick="closeModal('editAnnouncementModal')" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-all duration-200 border border-gray-300 hover:shadow-md">CANCEL</button>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-lg transform hover:scale-105">SAVE CHANGES</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    const backdrop = document.getElementById('modal-backdrop');
    if (backdrop && backdrop.parentElement !== document.body) {
        document.body.appendChild(backdrop);
    }

    function showBackdrop() {
        if (backdrop) backdrop.classList.remove('hidden');
    }

    function hideBackdrop() {
        if (backdrop) backdrop.classList.add('hidden');
    }

    function openModal(modalId) {
        document.getElementById(modalId)?.classList.remove('hidden');
        showBackdrop();
    }


    function closeModal(modalId) {
        document.getElementById(modalId)?.classList.add('hidden');
        hideBackdrop();
    }

    function openEditModal(button) {
        const id = button.dataset.id;
        const title = button.dataset.title;
        const content = button.dataset.content;
        const start = button.dataset.start;
        const end = button.dataset.end;

        // Update Form Action URL
        const form = document.getElementById('editAnnouncementForm');
        form.action = "{{ route('admin.announcements.index') }}/" + id;

        // Populate Fields
        document.getElementById('editTitle').value = title;
        document.getElementById('editContent').value = content;
        document.getElementById('editStartDate').value = start;
        document.getElementById('editEndDate').value = end;

        openModal('editAnnouncementModal');
    }

    function openViewModal(button) {
        document.getElementById('viewTitle').value = button.dataset.title;
        document.getElementById('viewContent').value = button.dataset.content;
        document.getElementById('viewStartDate').value = button.dataset.start;
        document.getElementById('viewEndDate').value = button.dataset.end;

        openModal('viewAnnouncementModal');
    }
</script>


@endsection
