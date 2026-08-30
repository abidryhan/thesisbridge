<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Log Your Contribution — {{ $project->title }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        <p class="text-gray-500 text-sm mb-4">
            This will be visible to anyone viewing this project, and cannot be edited or removed once submitted.
        </p>

        <form method="POST" action="{{ route('course-projects.contributions.store', $project) }}">
            @csrf

            <div class="mb-4">
                <label for="description" class="block font-medium mb-1">What did you specifically contribute?</label>
                <textarea name="description" id="description" rows="5"
                    class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label for="percentage" class="block font-medium mb-1">Estimated Contribution % (optional, self-reported)</label>
                <input type="number" name="percentage" id="percentage" min="0" max="100" value="{{ old('percentage') }}"
                    class="w-full border rounded px-3 py-2">
                <p class="text-gray-500 text-xs mt-1">This is your own estimate — there's no requirement that team members' percentages add up to 100%.</p>
                @error('percentage')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Submit Contribution</button>
        </form>
    </div>
</x-app-layout>
