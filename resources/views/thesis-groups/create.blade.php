<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create a Thesis Group
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        @if (session('error'))
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('thesis-groups.store') }}">
            @csrf

            <div class="mb-4">
                <label for="group_name" class="block font-medium mb-1">Group Name</label>
                <input type="text" name="group_name" id="group_name" value="{{ old('group_name') }}"
                    class="w-full border rounded px-3 py-2">
                @error('group_name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="supervisor_id" class="block font-medium mb-1">Supervisor (optional)</label>
                <select name="supervisor_id" id="supervisor_id" class="w-full border rounded px-3 py-2">
                    <option value="">— No supervisor —</option>
                    @foreach ($supervisors as $supervisor)
                        <option value="{{ $supervisor->id }}"
                            {{ old('supervisor_id') == $supervisor->id ? 'selected' : '' }}>
                            {{ $supervisor->user->name ?? 'Supervisor #' . $supervisor->id }}
                            — {{ $supervisor->designation }}
                        </option>
                    @endforeach
                </select>
                @error('supervisor_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">Select Team Members (1–3 additional students)</label>
                <p class="text-sm text-gray-500 mb-2">You will be automatically added to the group.</p>
                @error('student_ids')
                    <p class="text-red-600 text-sm mt-1 mb-2">{{ $message }}</p>
                @enderror

                <div class="border rounded p-3 max-h-60 overflow-y-auto space-y-2">
                    @forelse ($availableStudents as $student)
                        <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1 rounded">
                            <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                {{ in_array($student->id, old('student_ids', [])) ? 'checked' : '' }}
                                class="rounded border-gray-300">
                            <span>
                                {{ $student->user->name ?? 'Student #' . $student->id }}
                                <span class="text-gray-400 text-sm">— {{ $student->department }}, Batch {{ $student->batch }}</span>
                            </span>
                        </label>
                    @empty
                        <p class="text-gray-500 text-sm">No available students found.</p>
                    @endforelse
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Create Group
                </button>
                <a href="{{ route('thesis-groups.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
