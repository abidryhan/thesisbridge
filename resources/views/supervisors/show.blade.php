<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Supervisor Profile
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-3"><span class="font-medium">Designation:</span> {{ $supervisor->designation }}</div>
        <div class="mb-3"><span class="font-medium">Research Areas:</span> {{ $supervisor->research_areas }}</div>
        <div class="mb-3"><span class="font-medium">Max Capacity:</span> {{ $supervisor->max_capacity }} thesis group(s)</div>

        <div class="mt-6 flex gap-3">
            <a href="{{ route('supervisors.edit', $supervisor) }}" class="bg-blue-600 text-white px-4 py-2 rounded">Edit</a>

            <form method="POST" action="{{ route('supervisors.destroy', $supervisor) }}" onsubmit="return confirm('Delete this profile?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
            </form>
        </div>
    </div>
</x-app-layout>
