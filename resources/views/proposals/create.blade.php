<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Submit Your Thesis Proposal
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        <form method="POST" action="{{ route('proposals.store') }}">
            @csrf

            <div class="mb-4">
                <label for="title" class="block font-medium mb-1">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                    class="w-full border rounded px-3 py-2">
                @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label for="abstract" class="block font-medium mb-1">Abstract</label>
                <textarea name="abstract" id="abstract" rows="5"
                    class="w-full border rounded px-3 py-2">{{ old('abstract') }}</textarea>
                @error('abstract')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label for="objectives" class="block font-medium mb-1">Objectives</label>
                <textarea name="objectives" id="objectives" rows="5"
                    class="w-full border rounded px-3 py-2">{{ old('objectives') }}</textarea>
                @error('objectives')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label for="methodology" class="block font-medium mb-1">Methodology</label>
                <textarea name="methodology" id="methodology" rows="5"
                    class="w-full border rounded px-3 py-2">{{ old('methodology') }}</textarea>
                @error('methodology')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Submit Proposal</button>
        </form>
    </div>
</x-app-layout>