<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

<meta charset="utf-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1">

<meta name="csrf-token"
      content="{{ csrf_token() }}">

<title>
    {{ config('app.name') }}
    | @yield('title')
</title>

<link rel="icon"
      href="{{ asset('images/logo.png') }}"
      type="image/png">

@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles
@stack('styles')


</head>

<body>

<div class="min-vh-100 d-flex flex-column">

    @include('layouts.partials.navbar')

    @include('layouts.partials.breadcrumb')

    @hasSection('header')

        <header class="bg-white shadow-sm">

            <div class="container py-4">

                @yield('header')

            </div>

        </header>

    @endif

    <main class="flex-grow-1 py-4">

        <div class="container">

            @include('layouts.partials.alerts')

            @yield('content')

        </div>

    </main>
    @livewireScripts
</div>


<script src="{{ asset('js/ajax/address.js') }}"></script>

@stack('scripts')


</body>

</html>
