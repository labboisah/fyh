<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <title>FAYHOS | @yield('title')</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    
    {{-- Bootstrap CSS --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Flux / Tailwind --}}
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    @livewireStyles

</head>

<body class="bg-gray-100">

    @include('layouts.partials.navbar')

    <main class="max-w-7xl mx-auto p-6">

        {{ $slot }}

    </main>

    @livewireScripts
    <script>
        document.addEventListener('livewire:init', () => {

            Livewire.on('toast', (event) => {

                alert(event.message);

            });

        });
    </script>
</body>

</html>