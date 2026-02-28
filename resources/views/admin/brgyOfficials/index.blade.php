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
            <h1 class="text-4xl font-bold bg-gradient-to-r from-[#134573] to-[#0f3a5f] bg-clip-text text-transparent">BARANGAY OFFICIALS</h1>
            <p class="text-gray-500 text-sm mt-1">Manage and organize your barangay leadership records.</p>
        </div>
        <button onclick="openModal('addOfficialModal')" class="flex items-center gap-2 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold px-6 py-2.5 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 transform hover:scale-105">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            <span class="text-sm font-semibold">Add Official</span>
        </button>
    </div>

    @if(session('success'))
    @endif

    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-2 text-sm">
            <ul class="list-disc ml-4 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-xl rounded-xl overflow-hidden h-[calc(100vh-380px)] overflow-y-auto border border-gray-100">
        <table class="w-full text-sm">
            <thead style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%); color: white; position: sticky; top: 0; z-index: 10;" class="shadow-sm">
                <tr class="text-xs font-semibold uppercase tracking-widest text-center">
                    <th class="py-5 px-6">Last Name</th>
                    <th class="py-5 px-6">First Name</th>
                    <th class="py-5 px-6">Middle Initial</th>
                    <th class="py-5 px-6">Position</th>
                    <th class="py-5 px-6">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($officials as $official)
                    @php
                        $photoUrl = $official->photo_path ? asset('storage/'.$official->photo_path) : null;
                    @endphp
                    <tr class="hover:bg-blue-50/70 transition-colors duration-150 ease-in-out text-center">
                        <td class="py-5 px-6 font-semibold text-gray-900">{{ $official->last_name }}</td>
                        <td class="py-5 px-6 text-gray-700">{{ $official->first_name }}</td>
                        <td class="py-5 px-6 text-gray-600">{{ $official->middle_initial }}</td>
                        <td class="py-5 px-6 text-gray-600">{{ $official->position }}</td>
                        <td class="py-5 px-6 text-center">
                            <div class="flex justify-center items-center gap-2">
                                <!-- View Button - Always visible -->
                                <div class="relative group">
                                    <button onclick="openViewOfficial(@js(['last_name' => $official->last_name, 'first_name' => $official->first_name, 'middle_initial' => $official->middle_initial, 'position' => $official->position, 'photo' => $photoUrl]))" class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-blue-50 transition-all duration-200 hover:shadow-md" aria-label="View">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <span class="absolute top-full mt-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 rounded whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity duration-200 z-50">View</span>
                                </div>
                                
                                <!-- Edit Button -->
                                <div class="relative group">
                                    <button onclick="openEditOfficial(@js(['id' => $official->id, 'last_name' => $official->last_name, 'first_name' => $official->first_name, 'middle_initial' => $official->middle_initial, 'position' => $official->position, 'photo' => $photoUrl]))" class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-gray-50 transition-all duration-200 hover:shadow-md text-gray-600 hover:text-gray-800" aria-label="Edit">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <span class="absolute top-full mt-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 rounded whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity duration-200 z-50">Edit</span>
                                </div>
                                
                                <!-- Complete Button - For In Progress status -->
                                @if(isset($official->status) && strtolower($official->status) === 'in progress')
                                <div class="relative group">
                                    <button type="button" onclick="openCompleteOfficial({{ $official->id }}, '{{ $official->first_name }} {{ $official->last_name }}')" class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-green-50 transition-all duration-200 hover:shadow-md text-green-600 hover:text-green-700 cursor-pointer" aria-label="Complete">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                    <span class="absolute top-full mt-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 rounded whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity duration-200 z-50">Complete Official</span>
                                </div>
                                @endif
                                
                                <!-- Delete Button -->
                                <div class="relative group">
                                    <button type="button" onclick="openDeleteOfficial({{ $official->id }}, '{{ $official->first_name }} {{ $official->last_name }}')" class="w-9 h-9 flex items-center justify-center rounded-lg hover:bg-red-50 transition-all duration-200 hover:shadow-md text-red-600 hover:text-red-700 cursor-pointer" aria-label="Delete">
                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    <span class="absolute top-full mt-2 left-1/2 -translate-x-1/2 px-2 py-1 text-xs text-white bg-gray-900 rounded whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity duration-200 z-50">Delete</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-6 px-5 text-center text-gray-500">No officials yet. Add one to get started.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>


<!-- ADD OFFICIAL MODAL -->
<div id="addOfficialModal" class="modal-container hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] flex items-center justify-center z-[9999]">
    <div class="bg-white w-[700px] rounded-3xl shadow-2xl overflow-hidden border-2 border-gray-100">
        <div class="px-6 py-4 flex items-center gap-3" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
            <h2 class="text-white font-bold text-xl tracking-wide font-['Barlow_Semi_Condensed']">Add New Barangay Official</h2>
        </div>
        <div class="p-8">
            <form action="{{ route('admin.brgyOfficials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex gap-8 mb-6">
                    <div class="w-[220px] flex flex-col items-center">
                        <div class="relative flex h-[220px] w-full flex-col items-center justify-center overflow-hidden rounded-xl border-2 border-gray-200 bg-gray-100 shadow-md cursor-pointer">
                            <img id="addPhotoPreview" src="https://via.placeholder.com/400x400.png?text=Add+Photo" alt="Staff Photo" class="absolute inset-0 w-full h-full object-cover">
                            <div id="addCameraIconPlaceholder" class="flex items-center justify-center w-full h-full hidden">
                                <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.437 4h3.126a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                        </div>
                        <label class="cursor-pointer mt-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-2.5 px-6 rounded-xl shadow-md text-sm transition-all duration-200 transform hover:scale-105">
                            UPLOAD PHOTO
                            <input type="file" name="photo" id="photoInput" class="hidden" accept="image/*" onchange="previewImage(event, 'addPhotoPreview', 'addCameraIconPlaceholder', 'addPhotoThumb')">
                        </label>
                    </div>
                    <div class="flex-1 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name:</label>
                            <input type="text" name="last_name" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">First Name:</label>
                            <input type="text" name="first_name" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Middle Initial:</label>
                            <input type="text" name="middle_initial" maxlength="5" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Position:</label>
                            <input type="text" name="position" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" onclick="closeModal('addOfficialModal')" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-all duration-200 border border-gray-300 hover:shadow-md">CANCEL</button>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-lg transform hover:scale-105">ADD</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- VIEW OFFICIAL MODAL -->
<div id="viewOfficialModal" class="modal-container hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] flex items-center justify-center z-[9999]">
    <div class="bg-white w-[700px] rounded-3xl shadow-2xl overflow-hidden border-2 border-gray-100">
        <div class="px-6 py-4 flex items-center gap-3" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
            <h2 class="text-white font-bold text-xl tracking-wide font-['Barlow_Semi_Condensed']">Barangay Official Details</h2>
        </div>
        <div class="p-8">
            <div class="flex gap-8 mb-6">
                <div class="w-[220px] flex flex-col items-center">
                    <div class="relative flex h-[220px] w-full flex-col items-center justify-center overflow-hidden rounded-xl border-2 border-gray-200 bg-gray-100 shadow-md">
                        <img id="viewPhotoPreview" src="#" alt="Official Photo" class="absolute inset-0 w-full h-full object-cover">
                    </div>
                </div>
                <div class="flex-1 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name:</label>
                        <input id="viewLastName" type="text" readonly class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">First Name:</label>
                        <input id="viewFirstName" type="text" readonly class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Middle Initial:</label>
                        <input id="viewMiddleInitial" type="text" readonly class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Position:</label>
                        <input id="viewPosition" type="text" readonly class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700">
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('viewOfficialModal')" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-all duration-200 border border-gray-300 hover:shadow-md">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- EDIT OFFICIAL MODAL -->
<div id="editOfficialModal" class="modal-container hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] flex items-center justify-center z-[9999]">
    <div class="bg-white w-[700px] rounded-3xl shadow-2xl overflow-hidden border-2 border-gray-100">
        <div class="px-6 py-4 flex items-center gap-3" style="background: linear-gradient(135deg, #134573 0%, #0d2d47 100%);">
            <h2 class="text-white font-bold text-xl tracking-wide font-['Barlow_Semi_Condensed']">Edit Barangay Official Details</h2>
        </div>
        <div class="p-8">
            <form id="editOfficialForm" action="#" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="flex gap-8 mb-6">
                    <div class="w-[220px] flex flex-col items-center">
                        <div class="relative flex h-[220px] w-full flex-col items-center justify-center overflow-hidden rounded-xl border-2 border-gray-200 bg-gray-100 shadow-md cursor-pointer">
                            <div id="editCameraIconPlaceholder" class="flex items-center justify-center w-full h-full hidden">
                                <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.437 4h3.126a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <img id="editPhotoPreview" src="https://via.placeholder.com/400x400.png?text=Add+Photo" alt="Staff Photo" class="absolute inset-0 w-full h-full object-cover">
                        </div>
                        <label class="cursor-pointer mt-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-2.5 px-6 rounded-xl shadow-md text-sm transition-all duration-200 transform hover:scale-105">
                            UPLOAD PHOTO
                            <input type="file" name="photo" class="hidden" accept="image/*" onchange="previewImage(event, 'editPhotoPreview', 'editCameraIconPlaceholder', 'editPhotoThumb')">
                        </label>
                    </div>
                    <div class="flex-1 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name:</label>
                            <input id="editLastName" type="text" name="last_name" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">First Name:</label>
                            <input id="editFirstName" type="text" name="first_name" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Middle Initial:</label>
                            <input id="editMiddleInitial" type="text" name="middle_initial" maxlength="5" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Position:</label>
                            <input id="editPosition" type="text" name="position" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 bg-gray-50 text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-8">
                    <button type="button" onclick="closeModal('editOfficialModal')" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-all duration-200 border border-gray-300 hover:shadow-md">CANCEL</button>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-lg transform hover:scale-105">SAVE CHANGES</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- DELETE OFFICIAL MODAL -->
<div id="deleteModal" class="modal-container hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] flex items-center justify-center z-[9999]">
    <div class="bg-white w-[520px] rounded-3xl shadow-2xl p-10 text-center border-2 border-gray-100 transform transition-all">
        
        <!-- Icon Badge -->
        <div class="flex justify-center mb-6">
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-red-50 to-red-100 border-4 border-red-500 flex items-center justify-center shadow-lg">
                <svg class="w-12 h-12 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
        </div>
        
        <!-- Title -->
        <h2 class="font-bold text-2xl mb-4 text-gray-800 tracking-tight">Delete Official</h2>
        
        <!-- Description -->
        <div class="bg-red-50 rounded-xl p-4 mb-6 border border-red-200">
            <p class="text-sm text-gray-700 leading-relaxed">
                Are you sure you want to delete this official? This action cannot be undone and will permanently remove their record.
            </p>
        </div>

        <form id="deleteOfficialForm" method="POST" action="#">
            @csrf
            @method('DELETE')
            <div class="flex justify-center gap-4 mt-6">
                <button type="button" onclick="closeModal('deleteModal')" class="px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-md border border-gray-300">Cancel</button>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-xl text-sm font-semibold transition-all duration-200 hover:shadow-lg transform hover:scale-105">Delete Official</button>
            </div>
        </form>
    </div>
</div>


{{-- Shared Backdrop for All Modals --}}
<div id="modal-backdrop" class="hidden fixed top-[80px] left-[240px] w-[calc(100vw-240px)] h-[calc(100vh-80px)] bg-black/50 backdrop-blur-sm z-[9998]"></div>

@endsection

@push('scripts')
<script src="{{ asset('js/admin-brgyOfficials.js') }}" defer></script>
@endpush