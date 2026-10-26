<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $title ?? 'Connexion' }} &middot; {{ setting('site_name', 'SolidAction') }}</title>
        <link rel="icon" type="image/png" href="{{ theme_asset('assets/images/favicon/favicon-32x32.png') }}" sizes="32x32">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-zinc-50 text-zinc-800 antialiased">
        <div class="flex min-h-svh flex-col items-center justify-center p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-8">
                <a href="{{ route('home') }}" class="mx-auto" wire:navigate>
                    <img src="{{ media(setting('site_logo'), 'theme/assets/images/logo/logo-header.png') }}"
                         alt="{{ setting('site_name', 'SolidAction') }}" class="h-32 w-auto object-contain">
                </a>

                <div class="flex flex-col gap-6 rounded-xl border border-zinc-200 bg-white p-8 [--color-accent:var(--color-brand-green)] [--color-accent-content:var(--color-brand-green)] [--color-accent-foreground:var(--color-white)]">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
