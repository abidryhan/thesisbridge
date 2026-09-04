<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold text-stone-400 uppercase tracking-wider">
                        Advisor Recommendations &middot; {{ $group->group_name }}
                    </span>
                </div>
                <h2 class="font-heading font-bold text-2xl sm:text-3xl text-stone-900 leading-tight mt-0.5">
                    Recommended Supervisors
                </h2>
            </div>

            <!-- Back Link -->
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('thesis-groups.show', $group) }}" class="inline-flex items-center text-xs font-medium text-stone-600 hover:text-stone-900 bg-white border border-stone-200 px-3.5 py-2 rounded-lg shadow-xs transition">
                    &larr; Back to Thesis Group
                </a>
            </div>
        </div>
    </x-slot>

    <x-container size="compact">
        @if ($noTags)
            <!-- Missing Tags Advisory Banner -->
            <x-card class="border-amber-200 bg-amber-50/50">
                <div class="flex items-start gap-3.5">
                    <div class="p-2 bg-amber-100 text-amber-800 rounded-full shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-heading font-bold text-base text-amber-900">
                            Proposal Research Tags Required
                        </h4>
                        <p class="text-xs sm:text-sm text-amber-800 mt-1 leading-relaxed">
                            Your group's proposal does not have any research tags set yet. The matching engine requires topic tags to compute compatibility scores against faculty research areas.
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('thesis-groups.show', $group) }}" class="inline-flex items-center text-xs font-semibold text-amber-900 bg-white border border-amber-300 hover:bg-amber-100 px-3.5 py-2 rounded-lg transition shadow-2xs">
                                View Proposal &amp; Add Tags &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </x-card>
        @elseif ($supervisors->isEmpty())
            <!-- All Supervisors At Capacity -->
            <x-card class="text-center py-12">
                <div class="w-12 h-12 rounded-full bg-stone-100 mx-auto flex items-center justify-center text-stone-400 mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h4 class="font-heading font-semibold text-stone-800 text-base">No available supervisors</h4>
                <p class="text-xs text-stone-500 mt-1 max-w-sm mx-auto">
                    All faculty members in the department are currently at their maximum supervision capacity. Check back as groups complete their milestones.
                </p>
            </x-card>
        @else
            @php
                // Pre-calculate capacity order for client-side sorting
                $byCapacity = $supervisors->sortByDesc(fn ($s) => max(0, $s->max_capacity - $s->currentLoad()))->values();
            @endphp

            <div x-data="{ sortBy: 'score' }">
                <!-- Alpine Client-Side Re-Sort Control Bar -->
                <div class="bg-white p-3.5 rounded-xl border border-stone-200/80 shadow-2xs mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-stone-900">Faculty Recommendations</span>
                        <span class="text-[11px] text-stone-400 font-medium">({{ $supervisors->count() }} available)</span>
                    </div>

                    <!-- Segmented Sort Buttons -->
                    <div class="inline-flex items-center p-1 rounded-lg bg-stone-100 border border-stone-200/60 text-xs">
                        <button
                            type="button"
                            @click="sortBy = 'score'"
                            class="px-3 py-1.5 rounded-md font-medium transition"
                            :class="sortBy === 'score'
                                ? 'bg-white text-stone-900 shadow-2xs font-semibold'
                                : 'text-stone-500 hover:text-stone-800'"
                        >
                            Topic Match %
                        </button>
                        <button
                            type="button"
                            @click="sortBy = 'capacity'"
                            class="px-3 py-1.5 rounded-md font-medium transition"
                            :class="sortBy === 'capacity'
                                ? 'bg-white text-stone-900 shadow-2xs font-semibold'
                                : 'text-stone-500 hover:text-stone-800'"
                        >
                            Available Capacity
                        </button>
                    </div>
                </div>

                <!-- Flex Container for Instant Client-Side Reordering -->
                <div class="flex flex-col space-y-4">
                    @foreach ($supervisors as $index => $supervisor)
                        @php
                            $isCurrent = $group->supervisor_id === $supervisor->id;
                            $openSlots = max(0, $supervisor->max_capacity - $supervisor->currentLoad());

                            // Rank indices for both sorting dimensions
                            $scoreRank = $index + 1;
                            $capacityRank = $byCapacity->search(fn ($s) => $s->id === $supervisor->id) + 1;

                            // Score bar colors
                            $scoreBarColor = match (true) {
                                $supervisor->score >= 60 => 'bg-emerald-600',
                                $supervisor->score >= 30 => 'bg-blue-600',
                                default => 'bg-stone-400',
                            };

                            $scoreTextColor = match (true) {
                                $supervisor->score >= 60 => 'text-emerald-700',
                                $supervisor->score >= 30 => 'text-blue-700',
                                default => 'text-stone-600',
                            };
                        @endphp

                        <div
                            :style="sortBy === 'score' ? 'order: {{ $scoreRank }}' : 'order: {{ $capacityRank }}'"
                            class="transition-all duration-200"
                        >
                            <x-card class="hover:border-stone-300 transition-all duration-200 {{ $isCurrent ? 'border-emerald-300 bg-emerald-50/10' : '' }}">
                                <!-- Top Row: Faculty Header & Dynamic Score Badge -->
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-start gap-3.5">
                                        <!-- Faculty Avatar -->
                                        <div class="w-11 h-11 rounded-full bg-blue-50 border border-blue-200/60 flex items-center justify-center text-blue-700 font-heading font-bold text-base shrink-0 mt-0.5">
                                            {{ strtoupper(substr($supervisor->user->name ?? 'F', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h3 class="font-heading font-bold text-lg text-stone-900 leading-snug">
                                                    {{ $supervisor->user->name ?? 'Unknown Supervisor' }}
                                                </h3>

                                                <!-- Dynamic Rank Badge (Updates with active sort) -->
                                                <span
                                                    class="text-[11px] font-mono font-bold text-stone-400 bg-stone-100 px-2 py-0.5 rounded"
                                                    x-text="sortBy === 'score' ? '#{{ $scoreRank }}' : '#{{ $capacityRank }}'"
                                                >
                                                    #{{ $scoreRank }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-blue-700 font-medium mt-0.5">
                                                {{ $supervisor->designation }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Match Score Percentage Pill -->
                                    <div class="text-right shrink-0">
                                        <span class="font-mono font-bold text-sm sm:text-base {{ $scoreTextColor }}">
                                            {{ $supervisor->score }}%
                                        </span>
                                        <span class="block text-[10px] uppercase font-semibold text-stone-400 tracking-wider">
                                            Match
                                        </span>
                                    </div>
                                </div>

                                <!-- Visual Compatibility Meter -->
                                <div class="my-4 p-3 rounded-xl bg-stone-50/70 border border-stone-100">
                                    <div class="flex items-center justify-between text-xs mb-1.5">
                                        <span class="text-[11px] font-semibold text-stone-500 uppercase tracking-wider">Topic Overlap</span>
                                        <span class="text-xs font-semibold {{ $scoreTextColor }}">
                                            {{ $supervisor->score }}% Compatibility
                                        </span>
                                    </div>
                                    <div class="w-full bg-stone-200/80 rounded-full h-2 overflow-hidden">
                                        <div
                                            class="h-full rounded-full transition-all duration-500 {{ $scoreBarColor }}"
                                            style="width: {{ max(4, $supervisor->score) }}%"
                                        ></div>
                                    </div>
                                </div>

                                <!-- Research Areas -->
                                <div class="mb-4">
                                    <span class="text-[11px] font-semibold text-stone-400 uppercase tracking-wider block mb-1.5">
                                        Research Areas
                                    </span>
                                    <div class="flex flex-wrap gap-1.5">
                                        @forelse ($supervisor->research_areas ?? [] as $area)
                                            <x-badge variant="brand" size="sm">{{ $area }}</x-badge>
                                        @empty
                                            <span class="text-xs text-stone-400 italic">No areas specified.</span>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- Bottom Action & Capacity Bar -->
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-3.5 border-t border-stone-100 text-xs">
                                    <!-- Capacity Indicator -->
                                    <div class="flex items-center gap-2 text-stone-600">
                                        <span class="font-medium">
                                            Load: {{ $supervisor->currentLoad() }} / {{ $supervisor->max_capacity }} groups
                                        </span>
                                        <span class="text-stone-300">&middot;</span>
                                        <span class="font-semibold text-emerald-700">
                                            {{ $openSlots }} slot{{ $openSlots === 1 ? '' : 's' }} open
                                        </span>
                                    </div>

                                    <!-- Assignment Button or Badge -->
                                    <div>
                                        @if ($isCurrent)
                                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-800 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg shadow-2xs">
                                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Currently Assigned
                                            </span>
                                        @else
                                            <form method="POST" action="{{ route('thesis-groups.choose-supervisor', $group) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="supervisor_id" value="{{ $supervisor->id }}">
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg shadow-xs transition"
                                                >
                                                    Choose This Supervisor
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </x-card>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </x-container>
</x-app-layout>
