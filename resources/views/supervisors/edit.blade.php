<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Your Supervisor Profile
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        <form method="POST" action="{{ route('supervisors.update', $supervisor) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="designation" class="block font-medium mb-1">Designation</label>
                <input type="text" name="designation" id="designation" value="{{ old('designation', $supervisor->designation) }}"
                    class="w-full border rounded px-3 py-2">
                @error('designation')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label for="research_areas" class="block font-medium mb-1">Research Areas (comma-separated)</label>
                <input type="text" name="research_areas" id="research_areas"
                    value="{{ old('research_areas', implode(', ', $supervisor->research_areas)) }}"
                    class="w-full border rounded px-3 py-2" placeholder="Machine Learning, NLP, Computer Vision">
                @error('research_areas')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label for="max_capacity" class="block font-medium mb-1">Max Thesis Groups You Can Supervise</label>
                <input type="number" name="max_capacity" id="max_capacity" min="1" max="10"
                    value="{{ old('max_capacity', $supervisor->max_capacity) }}" class="w-full border rounded px-3 py-2">
                @error('max_capacity')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update Profile</button>
        </form>
    </div>
</x-app-layout>
