@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-6">Edit Course Project</h1>

    <form method="POST" action="{{ route('course-projects.update', $project) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="title" class="block font-medium mb-1">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $project->title) }}"
                class="w-full border rounded px-3 py-2">
            @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label for="description" class="block font-medium mb-1">Description</label>
            <textarea name="description" id="description" rows="4"
                class="w-full border rounded px-3 py-2">{{ old('description', $project->description) }}</textarea>
            @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label for="tech_stack" class="block font-medium mb-1">Tech Stack (comma-separated)</label>
            <input type="text" name="tech_stack" id="tech_stack"
                value="{{ old('tech_stack', implode(', ', $project->tech_stack)) }}"
                class="w-full border rounded px-3 py-2">
            @error('tech_stack')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label for="team_members" class="block font-medium mb-1">Team Members (comma-separated)</label>
            <input type="text" name="team_members" id="team_members"
                value="{{ old('team_members', implode(', ', $project->team_members)) }}"
                class="w-full border rounded px-3 py-2">
            @error('team_members')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4 flex gap-4">
            <div class="flex-1">
                <label for="term" class="block font-medium mb-1">Term</label>
                <select name="term" id="term" class="w-full border rounded px-3 py-2">
                    @foreach (['Spring', 'Summer', 'Fall'] as $term)
                        <option value="{{ $term }}" @selected(old('term', $project->term) === $term)>{{ $term }}</option>
                    @endforeach
                </select>
                @error('term')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="flex-1">
                <label for="year" class="block font-medium mb-1">Year</label>
                <input type="number" name="year" id="year" value="{{ old('year', $project->year) }}"
                    class="w-full border rounded px-3 py-2">
                @error('year')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mb-4">
            <label for="course_name" class="block font-medium mb-1">Course Name</label>
            <input type="text" name="course_name" id="course_name" value="{{ old('course_name', $project->course_name) }}"
                class="w-full border rounded px-3 py-2">
            @error('course_name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label for="github_link" class="block font-medium mb-1">GitHub Link (optional)</label>
            <input type="url" name="github_link" id="github_link" value="{{ old('github_link', $project->github_link) }}"
                class="w-full border rounded px-3 py-2">
            @error('github_link')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label for="demo_link" class="block font-medium mb-1">Demo Link (optional)</label>
            <input type="url" name="demo_link" id="demo_link" value="{{ old('demo_link', $project->demo_link) }}"
                class="w-full border rounded px-3 py-2">
            @error('demo_link')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        @if ($project->screenshot_paths)
            <div class="mb-4">
                <span class="block font-medium mb-1">Current Screenshots</span>
                <div class="flex gap-2 flex-wrap">
                    @foreach ($project->screenshot_paths as $path)
                        <img src="{{ asset('storage/' . $path) }}" class="w-24 h-24 object-cover rounded border">
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mb-4">
            <label for="screenshots" class="block font-medium mb-1">Add More Screenshots (optional)</label>
            <input type="file" name="screenshots[]" id="screenshots" multiple accept="image/*"
                class="w-full border rounded px-3 py-2">
            @error('screenshots.*')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update Project</button>
    </form>
</div>
@endsection
