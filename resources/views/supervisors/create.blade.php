<x-app-layout>
    <x-slot name="header">
        <h2 class="font-heading font-bold text-2xl text-stone-900 leading-tight">
            Create Supervisor Profile
        </h2>
    </x-slot>

    <x-container size="narrow">
        <x-card>
            <form method="POST" action="{{ route('supervisors.store') }}">
                @csrf

                <div class="mb-5">
                    <label for="designation" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Designation</label>
                    <input type="text" name="designation" id="designation" value="{{ old('designation') }}"
                        placeholder="e.g. Associate Professor, Lecturer"
                        class="w-full border-stone-200 rounded-lg px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-blue-600">
                    @error('designation')<p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div class="mb-5">
                    <label for="research_areas" class="block text-xs font-semibold text-stone-700 uppercase tracking-wider mb-1.5">Research Areas (comma-separated)</label>
                    <input type="text" name="research_areas" id="research_areas" value="{{ old('research_areas') }}"
                        placeholder="e.g. Machine Learning, NLP, Distributed Systems"
                        class="w-full border-stone-200 rounded-lg px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-blue-600">
                    <p class="text-stone-400 text-xs mt-1.5">Used by the Supervisor Matching engine to calculate topic compatibility.</p>
                    @error('research_areas')<p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div class="mb-6">
                    <label for="max_capacity" class="block font-medium mb-1">Max Thesis Groups You Can Supervise</label>
                    <input type="number" name="max_capacity" id="max_capacity" min="1" max="10"
                        value="{{ old('max_capacity', 3) }}" class="w-full border-stone-200 rounded-lg px-3.5 py-2.5 text-sm focus:border-blue-600 focus:ring-blue-600">
                    <p class="text-stone-400 text-xs mt-1.5">Maximum concurrent active groups you can supervise (1–10).</p>
                    @error('max_capacity')<p class="text-rose-600 text-xs mt-1.5">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-end pt-4 border-t border-stone-100">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-xs px-5 py-2.5 rounded-lg shadow-xs transition">
                        Save Profile
                    </button>
                </div>
            </form>
        </x-card>
    </x-container>
</x-app-layout>
