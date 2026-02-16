<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Daang Bakal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@400;600;700;800&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/animations.css') }}">
    <link rel="stylesheet" href="{{ asset('css/scrollbars.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modals.css') }}">


    <style>
        html {
            scroll-behavior: smooth;
        }

        .landing-page .font-barlow {
            font-family: 'Poppins', sans-serif;
        }
    </style>


    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>


    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                        barlow: ['Barlow Semi Condensed', 'sans-serif'],
                    },
                    colors: {
                        'brand-blue': '#4c6b8a', // Approximate color from your image
                        'brand-dark': '#2c4356',
                    }
                }
            }
        }
    </script>
</head>


<body class="bg-white font-sans text-gray-800 antialiased flex flex-col min-h-screen">
    @yield('content')
</body>


</html>



