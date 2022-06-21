{{--
-- Important note:
--
-- This template is based on an example from Tailwind UI, and is used here for educational purposes only.
-- Please do not use this template in your own projects without purchasing a
-- Tailwind UI license, or they’ll have to tighten up the licensing and you’ll ruin the fun for everyone.
--
-- Purchase here: https://tailwindui.com/
--}}
<nav class="bg-gray-800">
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <div class="relative flex items-center justify-between h-16">
            <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
                <div class="flex-shrink-0 flex items-center">
                    <x-icon name="template" class="h-7 w-7 text-primary-600"/>
                    <div class="font-bold flex ml-1 text-primary-400 text-lg">
                        {{ config('app.name') }}
                    </div>
                </div>
                @if(Auth::check())
                    <div class="hidden sm:block sm:ml-6">
                        <div class="flex space-x-4">
                            <a href="{{ route('dashboard') }}" class="bg-gray-900 text-white px-3 py-2 rounded-md text-sm font-medium" aria-current="page">Dashboard</a>
                        </div>
                    </div>
                @endif
            </div>
            <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0 space-x-1">
                @if(Auth::check())
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <x-button type="submit" xs icon="x" />
                    </form>
                    <x-button primary label="{{ Auth::user()->name }}" icon="user"/>
                @else
                    <a href="{{ route('oauth.twitch.redirect') }}">
                        <x-button primary label="Login with Twitch" icon="user"/>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="sm:hidden" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="bg-gray-900 text-white block px-3 py-2 rounded-md text-base font-medium" aria-current="page">Dashboard</a>
        </div>
    </div>
</nav>
