{{-- Complaint Submitted Email --}}
<x-mail::message>
<x-mail::header>
    <img src="{{ asset('images/BARANGAY LOGO.png') }}" alt="Barangay Daang Bakal Logo" style="max-width: 150px; height: auto;">
</x-mail::header>

<x-mail::panel>
    <h2>Complaint Submitted Successfully</h2>
    <p>Hello!</p>
    <p>Your complaint regarding <strong>{{ $complaintType }}</strong> has been submitted successfully.</p>
    <p><strong>Transaction ID:</strong> {{ $transactionId }}</p>
    <p>You will receive updates on the status of your complaint.</p>
</x-mail::panel>

<x-mail::button :url="route('user.complaints.index')" color="primary">
    View Details
</x-mail::button>

<x-mail::salutation>
    Regards,<br>
    Barangay Daang Bakal
</x-mail::salutation>
</x-mail::message>