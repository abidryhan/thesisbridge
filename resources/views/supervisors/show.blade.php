<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Supervisor Profile
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-3"><span class="font-medium">Designation:</span> {{ $supervisor->designation }}</div>
        <div class="mb-3">
            <span class="font-medium">Research Areas:</span>
            {{ implode(', ', $supervisor->research_areas) }}
        </div>

        <div class="mb-3"><span class="font-medium">Max Capacity:</span> {{ $supervisor->max_capacity }} thesis group(s)</div>

        <div class="mb-6 border-t pt-4">
            <h3 class="font-semibold mb-3">Track Record</h3>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <span class="text-gray-500 text-sm block">Average Completion Time</span>
                    <span class="text-lg font-medium">
                        {{ $averageCompletionTime !== null ? round($averageCompletionTime) . ' days' : 'Not enough completed milestones yet' }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-500 text-sm block">Total Supervised (all-time)</span>
                    <span class="text-lg font-medium">{{ $totalSupervised }}</span>
                </div>
            </div>

            <div class="mb-4">
                <span class="text-gray-500 text-sm block">Milestone Adherence Rate</span>
                <span class="text-lg font-medium">
                    {{ $adherenceRate !== null ? $adherenceRate . '%' : 'No resolved milestones yet' }}
                </span>
                <span class="text-gray-400 text-sm">(based on {{ $totalSupervised }} group{{ $totalSupervised === 1 ? '' : 's' }} supervised)</span>
            </div>

            <div>
                <span class="text-gray-500 text-sm block mb-1">Research Areas Covered</span>
                @forelse ($researchAreasCovered as $area => $count)
                    <span class="inline-block bg-gray-200 text-gray-800 text-sm px-2 py-1 rounded mr-2 mb-2">
                        {{ $area }} <span class="text-gray-500">&middot; {{ $count }} group{{ $count === 1 ? '' : 's' }}</span>
                    </span>
                @empty
                    <p class="text-gray-500 text-sm">No supervised groups with research tags yet.</p>
                @endforelse
            </div>
        </div>

        <div class="mt-6 flex gap-3">
            <a href="{{ route('supervisors.edit', $supervisor) }}" class="bg-blue-600 text-white px-4 py-2 rounded">Edit</a>

            <form method="POST" action="{{ route('supervisors.destroy', $supervisor) }}" onsubmit="return confirm('Delete this profile?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
            </form>
        </div>
    </div>
</x-app-layout>
