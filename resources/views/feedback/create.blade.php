<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Leave Feedback — {{ $milestone->title }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        <form method="POST" action="{{ route('thesis-groups.milestones.feedback.store', [$group, $milestone]) }}">
            @csrf

            <div class="mb-4">
                <label for="document_id" class="block font-medium mb-1">Document Version (optional)</label>
                <select name="document_id" id="document_id" class="w-full border rounded px-3 py-2">
                    <option value="">General feedback — not tied to a specific document</option>
                    @foreach ($documents as $document)
                        <option value="{{ $document->id }}" @selected(old('document_id') == $document->id)>
                            Version {{ $document->version_number }} — {{ $document->original_filename }}
                        </option>
                    @endforeach
                </select>
                @error('document_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label for="severity" class="block font-medium mb-1">Severity</label>
                <select name="severity" id="severity" class="w-full border rounded px-3 py-2">
                    <option value="">Select severity</option>
                    <option value="minor" @selected(old('severity') === 'minor')>Minor</option>
                    <option value="needs-revision" @selected(old('severity') === 'needs-revision')>Needs Revision</option>
                    <option value="blocking" @selected(old('severity') === 'blocking')>Blocking</option>
                </select>
                @error('severity')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label for="content" class="block font-medium mb-1">Feedback</label>
                <textarea name="content" id="content" rows="5"
                    class="w-full border rounded px-3 py-2">{{ old('content') }}</textarea>
                @error('content')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Submit Feedback</button>
                <a href="{{ route('thesis-groups.milestones.feedback.index', [$group, $milestone]) }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
