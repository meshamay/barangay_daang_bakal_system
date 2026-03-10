@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-md mt-10">
    <h2 class="text-2xl font-bold mb-4">Two Factor Authentication</h2>
    <form method="POST" action="{{ route('2fa.verify') }}">
        @csrf
        <div class="mb-4">
            <label for="code" class="block text-gray-700">Enter the code sent to your phone:</label>
            <input type="text" name="code" id="code" class="w-full border rounded px-3 py-2 mt-2" required>
            @error('code')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Verify</button>
    </form>
    <form method="POST" action="{{ route('2fa.send') }}" class="mt-4">
        @csrf
        <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded">Resend Code</button>
    </form>
    @if(session('status'))
        <div class="mt-4 text-green-600">{{ session('status') }}</div>
    @endif
</div>
@endsection
