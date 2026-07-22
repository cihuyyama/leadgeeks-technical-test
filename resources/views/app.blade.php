<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light" style="color-scheme: light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="color-scheme" content="light">

        {{-- Force light theme (no system/dark flip for showcase demo) --}}
        <script>
            (function () {
                document.documentElement.classList.remove('dark');
                document.documentElement.classList.add('light');
                document.documentElement.style.colorScheme = 'light';
                try {
                    localStorage.setItem('appearance', 'light');
                    document.cookie = 'appearance=light;path=/;max-age=31536000;SameSite=Lax';
                } catch (e) {}
            })();
        </script>

        {{-- Match LeadGeeks light surface before CSS loads --}}
        <style>
            html {
                background-color: #f7f6f4;
                color-scheme: light;
            }
        </style>

        <link rel="icon" href="/images/leadgeeks-mark.png" sizes="any">
        <link rel="apple-touch-icon" href="/images/leadgeeks-mark.png">

        {{-- LeadGeeks Inc brand font (Archivo) --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700&display=swap"
            rel="stylesheet"
        >

        @fonts

        @vite(['resources/css/app.css', 'resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        <x-inertia::head>
            <title>{{ config('app.name', 'LeadGeeks IT') }}</title>
        </x-inertia::head>
    </head>
    <body class="font-sans antialiased">
        <x-inertia::app />
    </body>
</html>
