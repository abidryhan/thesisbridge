<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Research Thread Map
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4">
        <p class="text-gray-600 mb-8 text-sm">
            Department academic output grouped by research area — approved thesis proposals and
            course projects that share a research tag are grouped together below.
        </p>

        @forelse ($groupedEntries as $tag => $entries)
            <div class="mb-8">
                <h3 class="font-semibold text-lg mb-3 border-b pb-1">{{ $tag }}</h3>

                @foreach ($entries as $entry)
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <div>
                            @if ($entry['type'] === 'course_project')
                                <a href="{{ route('course-projects.show', $entry['model']) }}" class="text-blue-600 underline">
                                    {{ $entry['title'] }}
                                </a>
                            @else
                                <span class="text-gray-800">{{ $entry['title'] }}</span>
                            @endif
                            <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded ml-2">
                                {{ $entry['type'] === 'thesis' ? 'Thesis' : 'Course Project' }}
                            </span>
                        </div>
                        <span class="text-xs text-gray-400">{{ $entry['created_at']->format('M Y') }}</span>
                    </div>
                @endforeach
            </div>
        @empty
            <p class="text-gray-500">No tagged research output yet.</p>
        @endforelse
    </div>
</x-app-layout>
