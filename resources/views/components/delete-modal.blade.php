@props([
    'action',
    'title' => 'Confirm Deletion',
    'message' => 'Are you sure you want to delete this item? This action cannot be undone.',
    'buttonText' => 'Delete',
])

<div x-data="{ open: false }" class="inline-block">
    <!-- Trigger Button -->
    <button
        type="button"
        @click="open = true"
        {{ $attributes->merge(['class' => 'inline-flex items-center text-rose-700 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-xs font-medium px-3 py-1.5 rounded-lg transition-colors']) }}
    >
        {{ $buttonText }}
    </button>

    <!-- Alpine Modal Overlay -->
    <div
        x-cloak
        x-show="open"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/40 backdrop-blur-xs"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @keydown.escape.window="open = false"
    >
        <!-- Modal Dialog -->
        <div
            class="bg-white rounded-2xl shadow-xl border border-stone-200 max-w-md w-full p-6 relative overflow-hidden"
            @click.outside="open = false"
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            <div class="flex items-start gap-4">
                <div class="p-2.5 bg-rose-100 text-rose-700 rounded-full shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-heading font-semibold text-lg text-stone-900">
                        {{ $title }}
                    </h4>
                    <p class="text-sm text-stone-600 mt-1.5 leading-relaxed">
                        {{ $message }}
                    </p>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button
                    type="button"
                    @click="open = false"
                    class="px-4 py-2 text-xs font-medium text-stone-700 bg-stone-100 hover:bg-stone-200 rounded-lg transition"
                >
                    Cancel
                </button>
                <form method="POST" action="{{ $action }}">
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="px-4 py-2 text-xs font-medium text-white bg-rose-600 hover:bg-rose-700 rounded-lg shadow-xs transition"
                    >
                        Confirm Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
