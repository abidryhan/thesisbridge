<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Recommended Supervisors — {{ $group->group_name }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        <a href="{{ route('thesis-groups.show', $group) }}" class="text-blue-600 underline text-sm mb-6 inline-block">
            ← Back to Group
        </a>

        @if ($noTags)
            <div class="bg-yellow-100 text-yellow-800 px-4 py-3 rounded">
                Your group's proposal doesn't have any research tags set yet, so compatibility scores
                can't be computed. Add some research tags on your proposal to see recommended supervisors.
            </div>
        @elseif ($supervisors->isEmpty())
            <p class="text-gray-500">No supervisors currently have available capacity.</p>
        @else
            @foreach ($supervisors as $supervisor)
                <div class="border rounded p-4 mb-3">
                    <div class="flex justify-between items-start">
                        <h4 class="font-semibold text-gray-800">{{ $supervisor->user->name ?? 'Unknown' }}</h4>
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded font-medium">
                            {{ $supervisor->score }}% match
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">{{ $supervisor->designation }}</p>
                    <p class="text-sm text-gray-500 mt-2">
                        Research areas: {{ implode(', ', $supervisor->research_areas) }}
                    </p>
                    <p class="text-xs text-gray-400 mt-2">
                        Capacity: {{ $supervisor->currentLoad() }} / {{ $supervisor->max_capacity }}
                    </p>
                </div>
            @endforeach
        @endif
    </div>
</x-app-layout>
