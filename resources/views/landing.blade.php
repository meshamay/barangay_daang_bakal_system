@extends('layouts.public')


@section('content')
    <div class="landing-page font-sans">
        {{-- 1. Navigation --}}
        <x-public.navbar />


        {{-- 2. Ticker (Placed immediately under navbar) --}}
        <x-public.announcement-ticker :announcements="$announcements" />


        {{-- 3. Hero Section --}}
        <x-public.hero />


        {{-- 4. Announcements List --}}
        <x-public.announcements-list :announcements="$announcements" />


        {{-- 5. Services --}}
        <x-public.services />


        {{-- 6. About Us --}}
        <x-public.about />


        {{-- 7. Officials --}}
        <x-public.officials :officials="$officials" />


        {{-- 8. FAQ Chatbot --}}
        <x-public.faq />


        {{-- 9. Footer --}}
        <x-public.footer />
    </div>
@endsection