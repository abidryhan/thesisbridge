@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-6">Student Profile</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-3"><span class="font-medium">Department:</span> {{ $student->department ?? 'Not set' }}</div>
    <div class="mb-3"><span class="font-medium">Batch:</span> {{ $student->batch ?? 'Not set' }}</div>
    <div class="mb-3"><span class="font-medium">Academic Year:</span> {{ $student->academic_year ?? 'Not set' }}</div>
    <div class="mb-3"><span class="font-medium">Research Interests:</span> {{ $student->research_interests ?? 'Not set' }}</div>

    <div class="mt-6 flex gap-3">
        <a href="{{ route('students.edit', $student) }}" class="bg-blue-600 text-white px-4 py-2 rounded">Edit</a>

        <form method="POST" action="{{ route('students.destroy', $student) }}" onsubmit="return confirm('Delete this profile?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
        </form>
    </div>
</div>
@endsection
