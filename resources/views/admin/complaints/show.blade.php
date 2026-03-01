@extends('admin.layouts.app') 

@section('content')

<main class="flex-1 p-11 fixed top-[60px] left-[220px] w-[calc(100vw-200px)] h-[calc(100vh-60px)] overflow-y-auto bg-gray-100">
    
    <div class="max-w-5xl mx-auto bg-white p-8 rounded-xl shadow-xl">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6 border-b pb-3">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Complaint File</h1>
                <p class="text-gray-500 text-sm">Transaction No: {{ $complaint->transaction_no }}</p>
            </div>
            
            {{-- Status Badge --}}
            @php
                $status_color = match($complaint->status) {
                    'Pending' => 'bg-orange-500',
                    'In Progress' => 'bg-yellow-500',
                    'Completed' => 'bg-green-600',
                    default => 'bg-gray-500'
                };
            @endphp
            <span class="px-4 py-2 text-lg font-bold text-white rounded-full {{ $status_color }}">
                {{ strtoupper($complaint->status) }}
            </span>
        </div>

        <div class="grid grid-cols-3 gap-8">
            
            {{-- =================================================== --}}
            {{-- COLUMN 1: USER REGISTRATION INFO (Fetched via User Model) --}}
            {{-- =================================================== --}}
            <div class="col-span-1 border-r pr-8">
                <h3 class="text-xl font-bold mb-4 text-blue-900 border-b pb-2">Complainant Profile</h3>
                
                @if($complaint->user)
                    <div class="space-y-3 text-sm text-gray-700">
                        <p><strong>Full Name:</strong> <br> {{ $complaint->user->first_name }} {{ $complaint->user->last_name }}</p>
                        <p><strong>Resident ID:</strong> <br> {{ $complaint->user->resident_id }}</p>
                        <p><strong>Contact No:</strong> <br> {{ $complaint->user->contact_number }}</p>
                        <p><strong>Address:</strong> <br> {{ $complaint->user->address }}</p>
                        
                        {{-- Valid IDs Images --}}
                        <div class="mt-6">
                            <p class="font-bold mb-2">Valid ID (Front):</p>
                            @if($complaint->user->id_front_path)
                                {{-- Uses asset('storage/...') to load image from public folder --}}
                                <img src="{{ asset('storage/' . $complaint->user->id_front_path) }}" 
                                     class="w-full h-32 object-cover rounded border border-gray-300 hover:scale-105 transition-transform cursor-pointer"
                                     onclick="window.open(this.src, '_blank')">
                            @else
                                <span class="text-red-500 text-xs italic">No Image Uploaded</span>
                            @endif
                        </div>

                        <div class="mt-4">
                            <p class="font-bold mb-2">Valid ID (Back):</p>
                            @if($complaint->user->id_back_path)
                                <img src="{{ asset('storage/' . $complaint->user->id_back_path) }}" 
                                     class="w-full h-32 object-cover rounded border border-gray-300 hover:scale-105 transition-transform cursor-pointer"
                                     onclick="window.open(this.src, '_blank')">
                            @else
                                <span class="text-red-500 text-xs italic">No Image Uploaded</span>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="bg-red-50 border-l-4 border-red-500 p-4">
                        <p class="text-red-700 font-bold">User Not Found</p>
                        <p class="text-xs text-red-600">The account may have been permanently deleted.</p>
                    </div>
                @endif
            </div>

            {{-- =================================================== --}}
            {{-- COLUMN 2: COMPLAINT DETAILS (Fetched from Complaint Model) --}}
            {{-- =================================================== --}}
            <div class="col-span-2">
                <h3 class="text-xl font-bold mb-4 text-blue-900 border-b pb-2">Complaint Details</h3>
                
                {{-- Incident Specifics --}}
                <div class="grid grid-cols-2 gap-6 text-sm mb-6 bg-gray-50 p-4 rounded-lg">
                    <div>
                        <p class="text-gray-500 text-xs uppercase font-bold">Complaint Type</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $complaint->complaint_type }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase font-bold">Urgency Level</p>
                        <p class="text-lg font-semibold {{ $complaint->level_urgency == 'High' ? 'text-red-600' : 'text-gray-800' }}">
                            {{ $complaint->level_urgency }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase font-bold">Incident Date</p>
                        <p class="text-md text-gray-800">{{ \Carbon\Carbon::parse($complaint->incident_date)->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs uppercase font-bold">Incident Time</p>
                        <p class="text-md text-gray-800">{{ \Carbon\Carbon::parse($complaint->incident_time)->format('h:i A') }}</p>
                    </div>
                </div>

                {{-- Statement --}}
                <div class="mb-6">
                    <p class="text-gray-500 text-xs uppercase font-bold mb-1">Complaint Statement</p>
                    <div class="bg-white p-4 rounded-lg border border-gray-200 text-gray-800 whitespace-pre-wrap leading-relaxed">
                        {{ $complaint->complaint_statement }}
                    </div>
                </div>

                {{-- Defendant Info --}}
                <h4 class="text-lg font-bold mb-3 text-gray-800 border-t pt-4">Defendant Information</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="font-bold text-gray-600">Name:</p>
                        <p class="text-gray-900">{{ $complaint->defendant_name }}</p>
                    </div>
                    <div>
                        <p class="font-bold text-gray-600">Address:</p>
                        <p class="text-gray-900">{{ $complaint->defendant_address }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- FOOTER / ACTIONS --}}
        <div class="flex justify-between items-center mt-10 pt-6 border-t">
            <a href="{{ route('admin.complaints.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-lg transition">
                ← Back to List
            </a>

            {{-- Status Actions --}}
            @if ($complaint->status !== 'Completed')
                <div class="flex gap-3">
                    @if($complaint->status === 'Pending')
                    <form action="{{ route('admin.complaints.update', $complaint->id) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="In Progress">
                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-6 rounded-lg transition shadow">
                            Mark In Progress
                        </button>
                    </form>
                    @endif

                    <form action="{{ route('admin.complaints.update', $complaint->id) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="Completed">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition shadow">
                            Mark Completed
                        </button>
                    </form>
                </div>
            @endif
        </div>

    </div>
</main>
@endsection