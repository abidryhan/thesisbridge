<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ThesisBridge') }}</title>

        <!-- Fonts: Figtree (Clean Sans) + Lora (Editorial Academic Serif) -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600|lora:500,600,700,500i,600i&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-stone-50/60 text-stone-900 min-h-screen flex flex-col">
        <div class="min-h-screen bg-stone-50/60 flex flex-col">
            @include('layouts.navigation')

            <!-- Global Toast Flash Notification (Alpine.js Auto-dismiss) -->
            <div
                x-data="{
                    show: false,
                    message: '',
                    type: 'success',
                    init() {
                        @if (session('success'))
                            this.message = {{ json_encode(session('success')) }};
                            this.type = 'success';
                            this.show = true;
                        @elseif (session('error'))
                            this.message = {{ json_encode(session('error')) }};
                            this.type = 'error';
                            this.show = true;
                        @elseif (session('status'))
                            this.message = {{ json_encode(session('status')) }};
                            this.type = 'status';
                            this.show = true;
                        @endif

                        if (this.show) {
                            setTimeout(() => { this.show = false; }, 4000);
                        }
                    }
                }"
                x-cloak
                x-show="show"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-2"
                x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed bottom-5 right-5 z-50 max-w-md w-full pointer-events-none"
            >
                <div
                    class="pointer-events-auto rounded-xl shadow-lg border p-4 flex items-start gap-3 bg-white"
                    :class="{
                        'border-emerald-200 text-emerald-900': type === 'success',
                        'border-rose-200 text-rose-900': type === 'error',
                        'border-indigo-200 text-indigo-900': type === 'status'
                    }"
                >
                    <template x-if="type === 'success'">
                        <div class="p-1 bg-emerald-100 rounded-full text-emerald-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </template>
                    <template x-if="type === 'error'">
                        <div class="p-1 bg-rose-100 rounded-full text-rose-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                    </template>
                    <template x-if="type === 'status'">
                        <div class="p-1 bg-indigo-100 rounded-full text-indigo-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </template>

                    <div class="flex-1 text-sm font-medium pt-0.5 leading-snug" x-text="message"></div>

                    <button @click="show = false" type="button" class="text-stone-400 hover:text-stone-600 text-sm p-1">
                        &times;
                    </button>
                </div>
            </div>

            <!-- Page Header -->
            @isset($header)
                <header class="bg-white border-b border-stone-200/80 shadow-xs">
                    <div class="max-w-5xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1 py-8">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
