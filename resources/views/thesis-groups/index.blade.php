<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Thesis Groups
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8 px-4">
        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <p class="text-gray-600">{{ $groups->count() }} group(s) found</p>
            <a href="{{ route('thesis-groups.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
                + Create Group
            </a>
        </div>

        @forelse ($groups as $group)
            <div class="bg-white shadow rounded-lg p-5 mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ $group->group_name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Supervisor:
                            @if ($group->supervisor)
                                {{ $group->supervisor->user->name ?? 'N/A' }}
                                ({{ $group->supervisor->designation }})
                            @else
                                <span class="italic">Not assigned</span>
                            @endif
                        </p>
                    </div>
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                        {{ $group->students->count() }} member(s)
                    </span>
                </div>

                <div class="mt-3">
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Members:</span>
                        {{ $group->students->pluck('user.name')->filter()->join(', ') ?: 'No members' }}
                    </p>
                </div>

                <div class="mt-4 flex gap-3">
                    <a href="{{ route('thesis-groups.show', $group) }}" class="text-blue-600 text-sm hover:underline">View Details →</a>
                </div>
            </div>
        @empty
            <div class="bg-white shadow rounded-lg p-8 text-center text-gray-500">
                No thesis groups have been created yet.
            </div>
        @endforelse
    </div>
</x-app-layout>
