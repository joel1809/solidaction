<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tableau de bord') &middot; Administration {{ setting('site_name', 'SolidAction') }}</title>
    <link rel="icon" type="image/png" href="{{ theme_asset('assets/images/favicon/favicon-32x32.png') }}" sizes="32x32">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-zinc-100 text-zinc-800 antialiased">

    <div class="flex min-h-screen">

        {{-- ================== Barre latérale ================== --}}
        <aside id="admin-sidebar"
            class="fixed inset-y-0 left-0 z-40 w-64 shrink-0 -translate-x-full overflow-y-auto bg-brand-green text-white/80 transition-transform duration-200 lg:static lg:translate-x-0">

            <div class="flex h-16 items-center gap-3 border-b border-white/10 px-5">
                <span class="grid size-12 shrink-0 place-items-center overflow-hidden rounded-lg bg-white p-1">
                    <img src="{{ media(setting('site_logo'), 'theme/assets/images/logo/logo-header.png') }}"
                         alt="{{ setting('site_name', 'SolidAction') }}" class="max-h-full max-w-full object-contain">
                </span>
                <div class="min-w-0 leading-tight">
                    <p class="truncate text-sm font-semibold text-white">{{ setting('site_name', 'SolidAction') }}</p>
                    <p class="text-[11px] uppercase tracking-wide text-white/60">Administration</p>
                </div>
                <button type="button" data-sidebar-close
                    class="ml-auto grid size-8 place-items-center rounded-lg text-white/70 hover:bg-white/10 lg:hidden">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            @include('partials.admin.nav')
        </aside>

        <div id="admin-backdrop" class="fixed inset-0 z-30 hidden bg-zinc-900/50 lg:hidden"></div>

        {{-- ================== Contenu ================== --}}
        <div class="flex min-w-0 flex-1 flex-col">

            <header class="sticky top-0 z-20 flex h-16 items-center gap-3 border-b border-zinc-200 bg-white px-4 sm:px-6">
                <button type="button" data-sidebar-open
                    class="grid size-9 place-items-center rounded-lg text-zinc-500 hover:bg-zinc-100 lg:hidden">
                    <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="min-w-0 flex-1">
                    <h1 class="truncate text-base font-semibold text-zinc-900">@yield('title', 'Tableau de bord')</h1>
                    @hasSection('subtitle')
                        <p class="truncate text-xs text-zinc-500">@yield('subtitle')</p>
                    @endif
                </div>

                <a href="{{ route('home') }}" target="_blank" rel="noopener"
                    class="hidden items-center gap-2 rounded-lg border border-zinc-200 px-3 py-2 text-sm font-medium text-zinc-600 transition hover:bg-zinc-50 sm:inline-flex">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                    </svg>
                    Voir le site
                </a>

                <div data-dropdown class="relative">
                    <button type="button" data-dropdown-trigger
                        class="flex items-center gap-2 rounded-lg px-2 py-1.5 transition hover:bg-zinc-100">
                        <span class="grid size-9 place-items-center rounded-full bg-zinc-900 text-xs font-semibold text-white">
                            {{ auth()->user()->initials() }}
                        </span>
                        <span class="hidden text-left sm:block">
                            <span class="block text-sm font-medium leading-tight text-zinc-900">{{ auth()->user()->name }}</span>
                            <span class="block text-xs leading-tight text-zinc-500">{{ auth()->user()->role_label }}</span>
                        </span>
                    </button>

                    <div data-dropdown-menu
                        class="absolute right-0 z-30 mt-2 hidden w-56 overflow-hidden rounded-xl border border-zinc-200 bg-white py-1 shadow-lg">
                        <div class="border-b border-zinc-100 px-4 py-3">
                            <p class="text-sm font-medium text-zinc-900">{{ auth()->user()->name }}</p>
                            <p class="truncate text-xs text-zinc-500">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50">Mon compte</a>
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.settings.edit') }}" class="block px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50">Paramètres du site</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-rose-600 hover:bg-rose-50">
                                Se déconnecter
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <x-admin.flash />
                @yield('content')
            </main>

            <footer class="border-t border-zinc-200 px-6 py-4 text-xs text-zinc-500">
                {{ setting('site_name', 'SolidAction') }} &copy; {{ date('Y') }} &mdash; interface d'administration
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
