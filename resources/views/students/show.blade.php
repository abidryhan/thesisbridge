<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Student Profile
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-3"><span class="font-medium">Department:</span> {{ $student->department ?? 'Not set' }}</div>
        <div class="mb-3"><span class="font-medium">Batch:</span> {{ $student->batch ?? 'Not set' }}</div>
        <div class="mb-3"><span class="font-medium">Academic Year:</span> {{ $student->academic_year ?? 'Not set' }}</div>
        <div class="mb-3"><span class="font-medium">Research Interests:</span> {{ $student->research_interests ?? 'Not set' }}</div>

        <div class="mb-6">
            <span class="font-medium block mb-2">Skill Fingerprint</span>
            @forelse ($skillFingerprint as $skill => $count)
                <span class="inline-block bg-gray-200 text-gray-800 text-sm px-2 py-1 rounded mr-2 mb-2">
                    {{ $skill }} <span class="text-gray-500">&middot; {{ $count }}</span>
                </span>
            @empty
                <p class="text-gray-500 text-sm">No verified skills yet — submit a course project to start building one.</p>
            @endforelse
        </div>

        <div class="mt-6 flex gap-3">
            <a href="{{ route('students.edit', $student) }}" class="bg-blue-600 text-white px-4 py-2 rounded">Edit</a>

            <form method="POST" action="{{ route('students.destroy', $student) }}" onsubmit="return confirm('Delete this profile?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
            </form>
        </div>
    </div>
</x-app-layout>
