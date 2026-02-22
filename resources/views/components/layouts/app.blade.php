<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Misplant Catalog' }} - Trichocereus Seeds</title>
    <meta name="description" content="{{ $description ?? 'Browse rare Trichocereus cactus seed crosses. Bridgesii, Pachanoi, Peruvianus and more.' }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-50 text-stone-900">
    <nav class="bg-green-800 text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-xl tracking-tight">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C9.5 2 8 4.5 8 7c0 1.5.5 3 1.5 4.2C8.5 12.5 7 14.5 7 17c0 3 2.5 5 5 5s5-2 5-5c0-2.5-1.5-4.5-2.5-5.8C15.5 10 16 8.5 16 7c0-2.5-1.5-5-4-5z"/></svg>
                    Misplant
                </a>
                <div class="hidden sm:flex items-center gap-6">
                    <a href="{{ route('crosses.index') }}" class="hover:text-green-200 transition {{ request()->routeIs('crosses.*') ? 'text-green-200 font-semibold' : '' }}">
                        Seed Crosses
                    </a>
                    <a href="{{ route('clones.index') }}" class="hover:text-green-200 transition {{ request()->routeIs('clones.*') ? 'text-green-200 font-semibold' : '' }}">
                        Parent Clones
                    </a>
                </div>
                <div class="sm:hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-4 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                        <a href="{{ route('crosses.index') }}" class="block px-4 py-2 text-stone-700 hover:bg-green-50">Seed Crosses</a>
                        <a href="{{ route('clones.index') }}" class="block px-4 py-2 text-stone-700 hover:bg-green-50">Parent Clones</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    <footer class="bg-stone-800 text-stone-400 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center text-sm">
            <p>Misplant Catalog &mdash; Rare Trichocereus Cactus Seeds</p>
        </div>
    </footer>
</body>
</html>
