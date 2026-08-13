<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Feedback — {{ $milestone->title }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('thesis-groups.show', $group) }}" class="text-blue-600 underline text-sm">← Back to Group</a>
            @if (auth()->user()->supervisor && $group->isSupervisedBy(auth()->user()->supervisor))
                <a href="{{ route('thesis-groups.milestones.feedback.create', [$group, $milestone]) }}" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Leave Feedback
                </a>
            @endif
        </div>

        @forelse ($feedbackEntries as $entry)
            @php
                $severityClasses = match ($entry->severity) {
                    'blocking' => 'bg-red-100 text-red-800',
                    'needs-revision' => 'bg-yellow-100 text-yellow-800',
                    default => 'bg-gray-200 text-gray-700',
                };
            @endphp
            <div class="border rounded p-4 mb-4">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-xs px-2 py-1 rounded {{ $severityClasses }}">
                        {{ ucfirst(str_replace('-', ' ', $entry->severity)) }}
                    </span>
                    @if ($entry->document)
                        <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded">
                            Version {{ $entry->document->version_number }}
                        </span>
                    @endif
                </div>

                <p class="text-sm">{{ $entry->content }}</p>

                <p class="text-xs text-gray-500 mt-3">
                    {{ $entry->supervisor->user->name ?? 'A supervisor who has since been removed' }}
                    &middot;
                    {{ $entry->created_at->format('M d, Y g:i A') }}
                </p>
            </div>
        @empty
            <p class="text-gray-500">No feedback yet for this milestone.</p>
        @endforelse
    </div>
</x-app-layout>
