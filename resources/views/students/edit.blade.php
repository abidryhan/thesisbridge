@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-6">Edit Your Student Profile</h1>

    <form method="POST" action="{{ route('students.update', $student) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="department" class="block font-medium mb-1">Department</label>
            <input type="text" name="department" id="department" value="{{ old('department', $student->department) }}"
                class="w-full border rounded px-3 py-2">
            @error('department')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="batch" class="block font-medium mb-1">Batch</label>
            <input type="text" name="batch" id="batch" value="{{ old('batch', $student->batch) }}"
                class="w-full border rounded px-3 py-2">
            @error('batch')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="academic_year" class="block font-medium mb-1">Academic Year</label>
            <input type="number" name="academic_year" id="academic_year" value="{{ old('academic_year', $student->academic_year) }}"
                class="w-full border rounded px-3 py-2">
            @error('academic_year')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="research_interests" class="block font-medium mb-1">Research Interests</label>
            <textarea name="research_interests" id="research_interests" rows="4"
                class="w-full border rounded px-3 py-2">{{ old('research_interests', $student->research_interests) }}</textarea>
            @error('research_interests')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            Update Profile
        </button>
    </form>
</div>
@endsection
