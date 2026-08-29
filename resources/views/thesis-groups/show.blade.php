<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $group->group_name }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow rounded-lg p-6">
            <div class="mb-4">
                <span class="font-medium text-gray-700">Group Name:</span>
                <span class="ml-1">{{ $group->group_name }}</span>
            </div>

            <div class="mb-4">
                <span class="font-medium text-gray-700">Supervisor:</span>

                @if ($group->supervisor)
                    <span class="ml-1">
                        {{ $group->supervisor->user->name ?? 'N/A' }}
                        ({{ $group->supervisor->designation }})
                    </span>
                @else
                    <span class="ml-1 italic text-gray-400">Not assigned</span>
                @endif
            </div>

            <div class="mb-4">
                <span class="font-medium text-gray-700">
                    Members ({{ $group->students->count() }}):
                </span>

                <ul class="mt-2 space-y-1">
                    @foreach ($group->students as $student)
                        <li class="flex items-center gap-2 text-sm bg-gray-50 px-3 py-2 rounded">
                            <span class="font-medium">
                                {{ $student->user->name ?? 'Student #' . $student->id }}
                            </span>

                            <span class="text-gray-400">
                                — {{ $student->department }}, Batch {{ $student->batch }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="mb-3">
                <span class="font-medium text-gray-700">Created:</span>
                <span class="ml-1 text-sm text-gray-500">
                    {{ $group->created_at->format('M d, Y') }}
                </span>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6 mt-6">
            <div class="flex justify-between items-center">
                <span class="font-medium text-gray-700">
                    Supervisor Matching
                </span>

                <a
                    href="{{ route('thesis-groups.supervisor-matches', $group) }}"
                    class="text-blue-600 text-sm underline"
                >
                    Browse Recommended Supervisors
                </a>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6 mt-6">
            <div class="flex justify-between items-center">
                <span class="font-medium text-gray-700">Thesis Proposal</span>

                @if ($group->proposal)
                    <a
                        href="{{ route('proposals.show', $group->proposal) }}"
                        class="text-blue-600 text-sm underline"
                    >
                        View Proposal
                    </a>
                @else
                    <a
                        href="{{ route('proposals.create') }}"
                        class="text-blue-600 text-sm underline"
                    >
                        Submit Proposal
                    </a>
                @endif
            </div>
        </div>

        <div class="mb-6 border-t pt-4">
            <h3 class="font-semibold mb-3">Thesis Progress Heatmap</h3>

            @if (empty($activityHeatmap))
                <p class="text-gray-500 text-sm">
                    The heatmap will appear once the group's proposal is approved and work has officially begun.
                </p>
            @else
                <div class="flex flex-wrap gap-1">
                    @foreach ($activityHeatmap as $week)
                        @php
                            $shade = match (true) {
                                $week['total'] === 0 => 'bg-gray-100',
                                $week['total'] === 1 => 'bg-green-200',
                                $week['total'] <= 3 => 'bg-green-400',
                                default => 'bg-green-600',
                            };
                            $tooltip = $week['week_start']->format('M d, Y') . ': '
                                . $week['documents'] . ' document(s), '
                                . $week['meetings'] . ' meeting(s), '
                                . $week['milestones'] . ' milestone(s) completed';
                        @endphp
                        <div class="w-4 h-4 rounded-sm {{ $shade }}" title="{{ $tooltip }}"></div>
                    @endforeach
                </div>

                <div class="flex items-center gap-2 mt-3 text-xs text-gray-500">
                    <span>Less</span>
                    <div class="w-3 h-3 rounded-sm bg-gray-100"></div>
                    <div class="w-3 h-3 rounded-sm bg-green-200"></div>
                    <div class="w-3 h-3 rounded-sm bg-green-400"></div>
                    <div class="w-3 h-3 rounded-sm bg-green-600"></div>
                    <span>More</span>
                </div>
            @endif
        </div>

        <div class="bg-white shadow rounded-lg p-6 mt-6">
            <div class="flex justify-between items-center mb-4">
                <span class="font-medium text-gray-700">
                    Milestones ({{ $group->milestones->count() }})
                </span>

                @if ($isSupervisor && $group->proposal && $group->proposal->status === 'approved')
                    <a
                        href="{{ route('thesis-groups.milestones.create', $group) }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded"
                    >
                        Add Milestone
                    </a>
                @endif
            </div>

            @forelse ($group->milestones as $milestone)
                <div class="border rounded p-4 mb-3">
                    <div class="flex justify-between items-start">
                        <h4 class="font-semibold text-gray-800">
                            {{ $milestone->title }}
                        </h4>

                        <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded">
                            {{ $milestone->deliverable_type }}
                        </span>
                    </div>

                    <p class="text-sm text-gray-600 mt-1">
                        {{ $milestone->description }}
                    </p>

                    <p class="text-xs text-gray-400 mt-2">
                        Deadline: {{ $milestone->deadline->format('M d, Y') }}
                    </p>

                    <div class="flex gap-4 mt-2 items-center">
                        <a
                            href="{{ route('thesis-groups.milestones.documents.index', [$group, $milestone]) }}"
                            class="text-blue-600 text-sm underline"
                        >
                            View Documents ({{ $milestone->documents_count }})
                        </a>

                        <a
                            href="{{ route('thesis-groups.milestones.feedback.index', [$group, $milestone]) }}"
                            class="text-blue-600 text-sm underline"
                        >
                            View Feedback ({{ $milestone->feedback_count }})
                        </a>

                        @if ($isSupervisor)
                            <form
                                method="POST"
                                action="{{ route('thesis-groups.milestones.toggle-complete', [$group, $milestone]) }}"
                            >
                                @csrf
                                @method('PATCH')

                                <button
                                    type="submit"
                                    class="{{ $milestone->completed_at ? 'bg-gray-200 text-gray-700' : 'bg-green-600 text-white' }} text-xs px-2 py-1 rounded"
                                >
                                    {{ $milestone->completed_at ? 'Mark Incomplete' : 'Mark Complete' }}
                                </button>
                            </form>
                        @endif

                        @if ($milestone->completed_at)
                            <span class="text-xs text-green-700">
                                ✓ Completed {{ $milestone->completed_at->format('M d, Y') }}
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-sm">
                    No milestones created yet.
                </p>
            @endforelse
        </div>

        <div class="bg-white shadow rounded-lg p-6 mt-6">
            <div class="flex justify-between items-center">
                <span class="font-medium text-gray-700">
                    Meeting Log
                </span>

                <a
                    href="{{ route('thesis-groups.meetings.index', $group) }}"
                    class="text-blue-600 text-sm underline"
                >
                    View Meetings
                </a>
            </div>
        </div>



        @if ($isMember)
            <div class="flex gap-3">
                <a href="{{ route('thesis-groups.edit', $group) }}" class="bg-blue-600 text-white px-4 py-2 rounded">Edit</a>
                <form method="POST" action="{{ route('thesis-groups.destroy', $group) }}" onsubmit="return confirm('Delete this thesis group?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
                </form>
            </div>
        @endif
    </div>
</x-app-layout>
