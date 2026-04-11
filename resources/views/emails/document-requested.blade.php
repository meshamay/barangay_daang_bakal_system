{{-- Document Request Submitted Email --}}
<x-mail::message>
<x-mail::header>
    <img src="{{ asset('images/BARANGAY LOGO.png') }}" alt="Barangay Daang Bakal Logo" style="max-width: 150px; height: auto;">
</x-mail::header>

<x-mail::panel>
    <h2>Document Request Submitted</h2>
    <p>Hello!</p>
    <p>Your <strong>{{ $documentType }}</strong> request has been submitted successfully.</p>
    <p><strong>Tracking Number:</strong> {{ $trackingNumber }}</p>
    <p>You will receive updates on the status of your request.</p>
</x-mail::panel>

<x-mail::button :url="route('user.document-requests.index')" color="primary">
    View Details
</x-mail::button>

<x-mail::salutation>
    Regards,<br>
    Barangay Daang Bakal
</x-mail::salutation>
</x-mail::message>