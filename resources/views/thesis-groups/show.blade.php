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
                <span class="font-medium text-gray-700">Members ({{ $group->students->count() }}):</span>
                <ul class="mt-2 space-y-1">
                    @foreach ($group->students as $student)
                        <li class="flex items-center gap-2 text-sm bg-gray-50 px-3 py-2 rounded">
                            <span class="font-medium">{{ $student->user->name ?? 'Student #' . $student->id }}</span>
                            <span class="text-gray-400">— {{ $student->department }}, Batch {{ $student->batch }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="mb-3">
                <span class="font-medium text-gray-700">Created:</span>
                <span class="ml-1 text-sm text-gray-500">{{ $group->created_at->format('M d, Y') }}</span>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6 mt-6">
            <div class="flex justify-between items-center mb-4">
                <span class="font-medium text-gray-700">Milestones ({{ $group->milestones->count() }})</span>
                @if ($isSupervisor)
                    <a href="{{ route('thesis-groups.milestones.create', $group) }}" class="bg-blue-600 text-white px-3 py-1.5 rounded text-sm">
                        + Add Milestone
                    </a>
                @endif
            </div>

            @forelse ($group->milestones as $milestone)
                <div class="border rounded p-4 mb-3">
                    <div class="flex justify-between items-start">
                        <h4 class="font-semibold text-gray-800">{{ $milestone->title }}</h4>
                        <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded">{{ $milestone->deliverable_type }}</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">{{ $milestone->description }}</p>
                    <p class="text-xs text-gray-400 mt-2">Deadline: {{ $milestone->deadline->format('M d, Y') }}</p>
                    <div class="flex gap-4 mt-2">
                        <a href="{{ route('thesis-groups.milestones.documents.index', [$group, $milestone]) }}" class="text-blue-600 text-sm underline">
                            View Documents ({{ $milestone->documents_count }})
                        </a>
                        <a href="{{ route('thesis-groups.milestones.feedback.index', [$group, $milestone]) }}" class="text-blue-600 text-sm underline">
                            View Feedback ({{ $milestone->feedback_count }})
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-sm">No milestones created yet.</p>
            @endforelse

        </div>

        <div class="bg-white shadow rounded-lg p-6 mt-6">
            <div class="flex justify-between items-center">
                <span class="font-medium text-gray-700">Meeting Log</span>
                <a href="{{ route('thesis-groups.meetings.index', $group) }}" class="text-blue-600 text-sm underline">
                    View Meetings
                </a>
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <a href="{{ route('thesis-groups.edit', $group) }}" class="bg-blue-600 text-white px-4 py-2 rounded">Edit</a>

            <form method="POST" action="{{ route('thesis-groups.destroy', $group) }}" onsubmit="return confirm('Are you sure you want to delete this group?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
            </form>

            <a href="{{ route('thesis-groups.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded">Back to List</a>
        </div>
    </div>
</x-app-layout>
