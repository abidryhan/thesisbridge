<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ThesisBridge</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-serif-brand { font-family: 'Source Serif 4', serif; }
    </style>
</head>
<body class="bg-[#FBFBFA] font-sans antialiased">

    {{-- Top bar, matching the dashboard nav --}}
    <header class="bg-white border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('images/logo.png') }}" alt="ThesisBridge" class="h-6 w-auto">
                <span class="font-serif-brand text-lg font-bold text-[#1C1917]">ThesisBridge</span>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}"
                   class="px-4 py-2 text-sm font-medium rounded-lg border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                    Log in
                </a>
                <a href="{{ route('register') }}"
                   class="px-4 py-2 text-sm font-medium rounded-lg bg-[#223E66] text-white hover:bg-[#1a3050] transition-colors">
                    Register
                </a>
            </div>
        </div>
    </header>

    {{-- Center content --}}
    <div class="flex flex-col items-center justify-center px-6" style="min-height: calc(100vh - 4rem);">
        <img src="{{ asset('images/logo.png') }}" alt="ThesisBridge" class="h-12 w-auto mb-5">

        <h1 class="font-serif-brand text-4xl sm:text-5xl font-bold text-[#1C1917] mb-10">
            ThesisBridge
        </h1>

        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}"
               class="px-6 py-3 text-sm font-medium rounded-lg border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 transition-colors shadow-sm">
                Log in
            </a>
            <a href="{{ route('register') }}"
               class="px-6 py-3 text-sm font-medium rounded-lg bg-[#223E66] text-white hover:bg-[#1a3050] transition-colors shadow-sm">
                Register
            </a>
        </div>
    </div>

</body>
</html>