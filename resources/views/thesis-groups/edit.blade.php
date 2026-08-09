<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Thesis Group
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        <form method="POST" action="{{ route('thesis-groups.update', $group) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="group_name" class="block font-medium mb-1">Group Name</label>
                <input type="text" name="group_name" id="group_name"
                    value="{{ old('group_name', $group->group_name) }}"
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
                            {{ old('supervisor_id', $group->supervisor_id) == $supervisor->id ? 'selected' : '' }}>
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
                <label class="block font-medium mb-1">Members (2–4 students)</label>
                @error('student_ids')
                    <p class="text-red-600 text-sm mt-1 mb-2">{{ $message }}</p>
                @enderror

                <div class="border rounded p-3 max-h-60 overflow-y-auto space-y-2">
                    @foreach ($selectableStudents as $student)
                        <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1 rounded">
                            <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                {{ in_array($student->id, old('student_ids', $currentMemberIds)) ? 'checked' : '' }}
                                class="rounded border-gray-300">
                            <span>
                                {{ $student->user->name ?? 'Student #' . $student->id }}
                                <span class="text-gray-400 text-sm">— {{ $student->department }}, Batch {{ $student->batch }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Update Group
                </button>
                <a href="{{ route('thesis-groups.show', $group) }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
