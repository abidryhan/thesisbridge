<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Supervised Groups
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">

        @forelse ($groups as $group)

            <div class="border rounded p-4 mb-3">

                <div class="flex justify-between items-start">

                    <h4 class="font-semibold text-gray-800">
                        <a
                            href="{{ route('thesis-groups.show', $group) }}"
                            class="hover:underline"
                        >
                            {{ $group->group_name }}
                        </a>
                    </h4>

                    @if ($group->isGhost)
                        <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded font-medium whitespace-nowrap">
                            ⚠ No student activity in {{ $group->daysSinceLastActivity }} days
                        </span>
                    @endif

                </div>

                <p class="text-sm text-gray-600 mt-1">
                    {{ $group->students->pluck('user.name')->implode(', ') }}
                </p>

            </div>

        @empty

            <p class="text-gray-500">
                You are not currently supervising any thesis groups.
            </p>

        @endforelse

    </div>
</x-app-layout>