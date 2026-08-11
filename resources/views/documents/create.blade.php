<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Upload Document — {{ $milestone->title }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        <form method="POST" action="{{ route('thesis-groups.milestones.documents.store', [$group, $milestone]) }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="document" class="block font-medium mb-1">Document File</label>
                <input type="file" name="document" id="document"
                    class="w-full border rounded px-3 py-2">
                <p class="text-gray-500 text-sm mt-1">Accepted: PDF, Word, PowerPoint, text, Markdown, or image files. Max 10MB.</p>
                @error('document')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Upload</button>
                <a href="{{ route('thesis-groups.milestones.documents.index', [$group, $milestone]) }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
