<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $project->title }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">{{ session('error') }}</div>
        @endif

        <p class="text-gray-500 mb-6">{{ $project->term }} {{ $project->year }} &middot; {{ $project->course_name }}</p>

        <p class="mb-6">{{ $project->description }}</p>

        <div class="mb-4">
            <span class="font-medium">Tech Stack:</span>
            <div class="flex gap-2 flex-wrap mt-1">
                @foreach ($project->tech_stack as $tech)
                    <span class="bg-gray-200 px-2 py-1 rounded text-sm">{{ $tech }}</span>
                @endforeach
            </div>
        </div>

        <div class="mb-4">
            <span class="font-medium">Team:</span>
            <p>
                @if ($project->students->isNotEmpty())
                    {{ $project->students->pluck('user.name')->implode(', ') }}
                @endif
                @if (!empty($project->team_members))
                    @if ($project->students->isNotEmpty())<span class="text-gray-400"> + </span>@endif
                    {{ implode(', ', $project->team_members) }}
                    <span class="text-gray-400 text-xs">(no platform account)</span>
                @endif
            </p>
        </div>

        @if ($project->continuedFrom)
            <p class="text-sm text-gray-600 mb-4">
                Continued from:
                <a href="{{ route('course-projects.show', $project->continuedFrom) }}" class="text-blue-600 underline">
                    {{ $project->continuedFrom->title }}
                </a>
            </p>
        @endif

        @if ($project->github_link)
            <div class="mb-2">
                <a href="{{ $project->github_link }}" class="text-blue-600 underline" target="_blank">GitHub Repository</a>
            </div>
        @endif

        @if ($project->demo_link)
            <div class="mb-4">
                <a href="{{ $project->demo_link }}" class="text-blue-600 underline" target="_blank">Live Demo</a>
            </div>
        @endif

        @if ($project->screenshot_paths)
            <div class="mb-6">
                <span class="font-medium block mb-2">Screenshots</span>
                <div class="flex gap-3 flex-wrap">
                    @foreach ($project->screenshot_paths as $path)
                        <img src="{{ asset('storage/' . $path) }}" class="w-48 rounded border">
                    @endforeach
                </div>
            </div>
        @endif

        @if (!empty($project->research_tags))
            <div class="mb-4">
                <span class="font-medium">Research Tags:</span>
                <div class="flex gap-2 flex-wrap mt-1">
                    @foreach ($project->research_tags as $tag)
                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">{{ $tag }}</span>
                    @endforeach
                </div>
            </div>
        @endif


        @if ($project->continuations->isNotEmpty())
            <div class="mb-6">
                <h3 class="font-semibold mb-1">Continued By</h3>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($project->continuations as $continuation)
                        <li>
                            <a href="{{ route('course-projects.show', $continuation) }}" class="text-blue-600 underline">
                                {{ $continuation->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($canToggle)
            <form method="POST" action="{{ route('course-projects.toggle-continuation', $project) }}" class="mb-4">
                @csrf
                @method('PATCH')
                <button type="submit"
                    class="{{ $project->is_open_for_continuation ? 'bg-gray-200 text-gray-700' : 'bg-blue-600 text-white' }} px-4 py-2 rounded text-sm">
                    {{ $project->is_open_for_continuation ? 'Close for Continuation' : 'Open for Continuation' }}
                </button>
            </form>
        @endif

        @if ($project->is_open_for_continuation && auth()->check() && !$canToggle)
            <a href="{{ route('course-projects.claim', $project) }}" class="bg-green-600 text-white px-4 py-2 rounded text-sm inline-block mb-4">
                Claim &amp; Continue This Project
            </a>
        @endif

        @if ($isOwner)
            <div class="flex gap-3">
                <a href="{{ route('course-projects.edit', $project) }}" class="bg-blue-600 text-white px-4 py-2 rounded">Edit</a>
                <form method="POST" action="{{ route('course-projects.destroy', $project) }}" onsubmit="return confirm('Delete this project?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
                </form>
            </div>
        @endif
    </div>



</x-app-layout>
