<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Meeting Log — {{ $group->group_name }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('thesis-groups.show', $group) }}" class="text-blue-600 underline text-sm">← Back to Group</a>
            <a href="{{ route('thesis-groups.meetings.create', $group) }}" class="bg-blue-600 text-white px-4 py-2 rounded">
                Log New Meeting
            </a>
        </div>

        @forelse ($meetings as $meeting)
            <div class="border rounded p-4 mb-4">
                <div class="flex justify-between items-start mb-2">
                    <span class="font-semibold">{{ $meeting->meeting_date->format('M d, Y') }}</span>
                    @if ($meeting->milestone)
                        <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded">{{ $meeting->milestone->title }}</span>
                    @endif
                </div>

                <p class="text-sm text-gray-500 mb-3">
                    Logged by {{ $meeting->loggedBy->name ?? 'Unknown' }}
                    &middot;
                    Attendees: {{ $meeting->attendees->pluck('name')->join(', ') ?: 'None recorded' }}
                </p>

                <div class="mb-2">
                    <span class="font-medium text-sm">Agenda:</span>
                    <p class="text-sm">{{ $meeting->agenda }}</p>
                </div>
                <div class="mb-2">
                    <span class="font-medium text-sm">Outcomes:</span>
                    <p class="text-sm">{{ $meeting->outcomes }}</p>
                </div>
                <div>
                    <span class="font-medium text-sm">Next Action Items:</span>
                    <p class="text-sm">{{ $meeting->next_action_items }}</p>
                </div>
            </div>
        @empty
            <p class="text-gray-500">No meetings logged yet.</p>
        @endforelse
    </div>
</x-app-layout>