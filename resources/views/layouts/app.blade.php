{{--
-- Important note:
--
-- This template is based on an example from Tailwind UI, and is used here for educational purposes only.
-- Please do not use this template in your own projects without purchasing a
-- Tailwind UI license, or they’ll have to tighten up the licensing and you’ll ruin the fun for everyone.
--
-- Purchase here: https://tailwindui.com/
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? config('app.name') }}</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @livewireStyles
    @powerGridStyles
</head>
<body x-data="{}">
<div>
    <div x-data="{ open: false }" class="flex min-h-screen bg-gray-100">
        <div class="flex flex-col w-0 flex-1">
            <x-topbar/>
            <main class="relative flex-1 overflow-y-auto focus:outline-none">
                <div class="px-4 py-6 mx-auto space-y-2 max-w-7xl sm:px-6 md:px-8">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</div>
@wireUiScripts
<script src="{{ mix('js/app.js') }}" defer></script>
@livewireScripts
@powerGridScripts
</body>
</html>
