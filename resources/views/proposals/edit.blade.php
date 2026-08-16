<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Proposal
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        <form method="POST" action="{{ route('proposals.update', $proposal) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="title" class="block font-medium mb-1">Title</label>

                <input
                    type="text"
                    name="title"
                    id="title"
                    value="{{ old('title', $proposal->title) }}"
                    class="w-full border rounded px-3 py-2"
                >

                @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="abstract" class="block font-medium mb-1">Abstract</label>

                <textarea
                    name="abstract"
                    id="abstract"
                    rows="4"
                    class="w-full border rounded px-3 py-2"
                >{{ old('abstract', $proposal->abstract) }}</textarea>

                @error('abstract')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="objectives" class="block font-medium mb-1">Objectives</label>

                <textarea
                    name="objectives"
                    id="objectives"
                    rows="4"
                    class="w-full border rounded px-3 py-2"
                >{{ old('objectives', $proposal->objectives) }}</textarea>

                @error('objectives')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="methodology" class="block font-medium mb-1">Methodology</label>

                <textarea
                    name="methodology"
                    id="methodology"
                    rows="4"
                    class="w-full border rounded px-3 py-2"
                >{{ old('methodology', $proposal->methodology) }}</textarea>

                @error('methodology')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button
                    type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded"
                >
                    Save Changes
                </button>

                <a
                    href="{{ route('proposals.show', $proposal) }}"
                    class="bg-gray-200 text-gray-700 px-4 py-2 rounded"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-app-layout>