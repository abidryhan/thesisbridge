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

        <h1 class="text-2xl font-bold mb-2">{{ $project->title }}</h1>
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
            <span class="font-medium">Team Members:</span>
            <p>{{ implode(', ', $project->team_members) }}</p>
        </div>

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
