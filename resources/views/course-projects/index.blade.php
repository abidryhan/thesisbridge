@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Course Project Showcase</h1>
        @auth
            <a href="{{ route('course-projects.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
                Submit a Project
            </a>
        @endauth
    </div>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="grid gap-4">
        @forelse ($projects as $project)
            <a href="{{ route('course-projects.show', $project) }}" class="block border rounded p-4 hover:bg-gray-50">
                <h2 class="text-lg font-semibold">{{ $project->title }}</h2>
                <p class="text-gray-500 text-sm">{{ $project->term }} {{ $project->year }} &middot; {{ $project->course_name }}</p>
            </a>
        @empty
            <p class="text-gray-500">No projects submitted yet.</p>
        @endforelse
    </div>
</div>
@endsection
