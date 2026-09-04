<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-heading font-bold text-2xl sm:text-3xl text-stone-900 leading-tight">
                    My Supervised Groups
                </h2>
                <p class="text-xs sm:text-sm text-stone-500 mt-1">
                    Active thesis teams under your supervision &middot; Live student-initiated activity tracking
                </p>
            </div>

            @php
                $ghostCount = $groups->where('isGhost', true)->count();
                $activeCount = $groups->where('isGhost', false)->count();
            @endphp

            <div class="flex items-center gap-2 shrink-0">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-stone-200 text-xs font-medium text-stone-600 shadow-2xs">
                    <strong class="text-stone-900">{{ $groups->count() }}</strong> Total Groups
                    @if ($ghostCount > 0)
                        <span class="text-stone-300">&middot;</span>
                        <span class="text-rose-700 font-semibold">{{ $ghostCount }} Silent</span>
                    @endif
                </span>
            </div>
        </div>
    </x-slot>

    <x-container size="wide">
        @if ($groups->isEmpty())
            <x-card class="text-center py-12">
                <div class="w-12 h-12 rounded-full bg-stone-100 mx-auto flex items-center justify-center text-stone-400 mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h4 class="font-heading font-semibold text-stone-800 text-base">No supervised thesis groups</h4>
                <p class="text-xs text-stone-500 mt-1 max-w-sm mx-auto">
                    You are not currently assigned as supervisor to any thesis groups. When students select you through the matching engine, their groups will appear here.
                </p>
            </x-card>
        @else
            <!-- Workload Overview Banner -->
            @if ($ghostCount > 0)
                <div class="bg-rose-50/80 border border-rose-200 rounded-xl p-4 mb-6 flex items-center justify-between gap-3 shadow-2xs">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-rose-100 text-rose-800 rounded-full shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-heading font-semibold text-rose-900 text-sm">
                                Attention Required: {{ $ghostCount }} Group{{ $ghostCount === 1 ? '' : 's' }} Flagged for Inactivity
                            </h4>
                            <p class="text-xs text-rose-700 mt-0.5">
                                Groups with no student-initiated document uploads or meeting logs exceeding the 14-day threshold.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- 2-Column Supervision Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach ($groups as $group)
                    @php
                        $isSilent = (bool) $group->isGhost;
                        $cardAccent = $isSilent
                            ? 'border-l-4 border-l-rose-500 bg-rose-50/15'
                            : 'border-l-4 border-l-emerald-500 bg-white';
                    @endphp

                    <div class="rounded-xl border border-stone-200/80 shadow-xs hover:shadow-md hover:border-stone-300 p-5 transition-all duration-200 flex flex-col justify-between {{ $cardAccent }}">
                        <div>
                            <!-- Header: Group Name & Health Badge -->
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div>
                                    <h3 class="font-heading font-bold text-lg text-stone-900 leading-snug">
                                        <a href="{{ route('thesis-groups.show', $group) }}" class="hover:text-blue-700 transition">
                                            {{ $group->group_name }}
                                        </a>
                                    </h3>
                                    <span class="text-[11px] font-medium text-stone-400">
                                        Formed {{ $group->created_at->format('M d, Y') }}
                                    </span>
                                </div>

                                @if ($isSilent)
                                    <x-badge variant="danger" size="sm">
                                        ⚠ Silent for {{ $group->daysSinceLastActivity }} days
                                    </x-badge>
                                @else
                                    <x-badge variant="success" size="sm">
                                        ✓ Active &amp; on track
                                    </x-badge>
                                @endif
                            </div>

                            <!-- Student Roster -->
                            <div class="mt-4 pt-3.5 border-t border-stone-100">
                                <span class="text-[10px] font-semibold text-stone-400 uppercase tracking-wider block mb-2">
                                    Student Members ({{ $group->students->count() }})
                                </span>

                                <div class="space-y-2">
                                    @foreach ($group->students as $student)
                                        <div class="flex items-center gap-2 text-xs">
                                            <div class="w-5 h-5 rounded-full bg-stone-100 text-stone-600 text-[10px] font-bold flex items-center justify-center shrink-0">
                                                {{ strtoupper(substr($student->user->name ?? 'S', 0, 1)) }}
                                            </div>
                                            <span class="font-medium text-stone-800 truncate">{{ $student->user->name ?? 'Student' }}</span>
                                            <span class="text-[10px] text-stone-400">({{ $student->department ?? 'CS' }}, Batch {{ $student->batch ?? 'N/A' }})</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer / Link -->
                        <div class="mt-5 pt-3 border-t border-stone-100 flex items-center justify-between text-xs">
                            <span class="text-[11px] text-stone-400 font-mono">
                                Last active: {{ $group->daysSinceLastActivity }} day(s) ago
                            </span>

                            <a
                                href="{{ route('thesis-groups.show', $group) }}"
                                class="inline-flex items-center gap-1 font-semibold text-blue-700 hover:text-blue-900 group transition"
                            >
                                <span>View Group</span>
                                <span class="transition-transform group-hover:translate-x-0.5">&rarr;</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-container>
</x-app-layout>
