<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Document History — {{ $milestone->title }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('thesis-groups.show', $group) }}" class="text-blue-600 underline text-sm">← Back to Group</a>
            <a href="{{ route('thesis-groups.milestones.documents.create', [$group, $milestone]) }}" class="bg-blue-600 text-white px-4 py-2 rounded">
                Upload New Version
            </a>
        </div>

        @forelse ($documents as $document)
            <div class="border rounded p-4 mb-3">
                <div class="flex justify-between items-start">
                    <span class="font-semibold">Version {{ $document->version_number }}</span>
                    <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="text-blue-600 underline text-sm">
                        {{ $document->original_filename }}
                    </a>
                </div>
                <p class="text-sm text-gray-500 mt-1">
                    @if ($document->user)
                        Uploaded by {{ $document->user->name }}
                        @if ($document->user->student)
                            (Student)
                        @elseif ($document->user->supervisor)
                            (Supervisor)
                        @endif
                    @else
                        Uploaded by a user who has since been removed
                    @endif
                    &middot;
                    {{ $document->created_at->format('M d, Y g:i A') }}
                </p>
            </div>
        @empty
            <p class="text-gray-500">No documents uploaded yet for this milestone.</p>
        @endforelse
    </div>
</x-app-layout>
