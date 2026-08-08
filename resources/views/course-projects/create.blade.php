<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Submit a Course Project
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        <form method="POST" action="{{ route('course-projects.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="title" class="block font-medium mb-1">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                    class="w-full border rounded px-3 py-2">
                @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block font-medium mb-1">Description</label>
                <textarea name="description" id="description" rows="4"
                    class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label for="tech_stack" class="block font-medium mb-1">Tech Stack (comma-separated)</label>
                <input type="text" name="tech_stack" id="tech_stack" value="{{ old('tech_stack') }}"
                    placeholder="Laravel, PostgreSQL, TailwindCSS" class="w-full border rounded px-3 py-2">
                @error('tech_stack')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label for="team_members" class="block font-medium mb-1">Team Members (comma-separated)</label>
                <input type="text" name="team_members" id="team_members" value="{{ old('team_members') }}"
                    placeholder="Jane Doe, John Smith" class="w-full border rounded px-3 py-2">
                @error('team_members')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4 flex gap-4">
                <div class="flex-1">
                    <label for="term" class="block font-medium mb-1">Term</label>
                    <select name="term" id="term" class="w-full border rounded px-3 py-2">
                        <option value="">Select term</option>
                        @foreach (['Spring', 'Summer', 'Fall'] as $term)
                            <option value="{{ $term }}" @selected(old('term') === $term)>{{ $term }}</option>
                        @endforeach
                    </select>
                    @error('term')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex-1">
                    <label for="year" class="block font-medium mb-1">Year</label>
                    <input type="number" name="year" id="year" value="{{ old('year') }}"
                        class="w-full border rounded px-3 py-2">
                    @error('year')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="course_name" class="block font-medium mb-1">Course Name</label>
                <input type="text" name="course_name" id="course_name" value="{{ old('course_name') }}"
                    class="w-full border rounded px-3 py-2">
                @error('course_name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label for="github_link" class="block font-medium mb-1">GitHub Link (optional)</label>
                <input type="url" name="github_link" id="github_link" value="{{ old('github_link') }}"
                    class="w-full border rounded px-3 py-2">
                @error('github_link')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label for="demo_link" class="block font-medium mb-1">Demo Link (optional)</label>
                <input type="url" name="demo_link" id="demo_link" value="{{ old('demo_link') }}"
                    class="w-full border rounded px-3 py-2">
                @error('demo_link')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label for="screenshots" class="block font-medium mb-1">Screenshots (optional, multiple allowed)</label>
                <input type="file" name="screenshots[]" id="screenshots" multiple accept="image/*"
                    class="w-full border rounded px-3 py-2">
                @error('screenshots.*')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Submit Project</button>
        </form>
    </div>
</x-app-layout>
