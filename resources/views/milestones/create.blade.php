<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Milestone — {{ $group->group_name }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        <form method="POST" action="{{ route('thesis-groups.milestones.store', $group) }}">
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
                <label for="deadline" class="block font-medium mb-1">Deadline</label>
                <input type="date" name="deadline" id="deadline" value="{{ old('deadline') }}"
                    class="w-full border rounded px-3 py-2">
                @error('deadline')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label for="deliverable_type" class="block font-medium mb-1">Deliverable Type</label>
                <select name="deliverable_type" id="deliverable_type" class="w-full border rounded px-3 py-2">
                    <option value="">Select type</option>
                    @foreach (['Document', 'Presentation', 'Code Repository'] as $type)
                        <option value="{{ $type }}" @selected(old('deliverable_type') === $type)>{{ $type }}</option>
                    @endforeach
                </select>
                @error('deliverable_type')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Create Milestone</button>
                <a href="{{ route('thesis-groups.show', $group) }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
