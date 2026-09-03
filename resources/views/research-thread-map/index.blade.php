<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-heading font-bold text-2xl sm:text-3xl text-stone-900 leading-tight">
                    Research Thread Map
                </h2>
                <p class="text-xs sm:text-sm text-stone-500 mt-1">
                    Departmental academic output grouped by research discipline &middot; Connecting approved theses and course projects across time
                </p>
            </div>

            @php
                $totalEntries = $groupedEntries->flatten(1)->count();
                $totalTags = $groupedEntries->count();
            @endphp

            <div class="flex items-center gap-2 shrink-0">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-stone-200 text-xs font-medium text-stone-600 shadow-2xs">
                    <strong class="text-stone-900">{{ $totalTags }}</strong> Topics
                    <span class="text-stone-300">&middot;</span>
                    <strong class="text-stone-900">{{ $totalEntries }}</strong> Output Records
                </span>
            </div>
        </div>
    </x-slot>

    <x-container size="wide">
        @if ($groupedEntries->isEmpty())
            <x-card class="text-center py-12">
                <div class="w-12 h-12 rounded-full bg-stone-100 mx-auto flex items-center justify-center text-stone-400 mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                    </svg>
                </div>
                <h4 class="font-heading font-semibold text-stone-800 text-base">No research output mapped yet</h4>
                <p class="text-xs text-stone-500 mt-1 max-w-sm mx-auto">
                    Approved thesis proposals and course projects with research tags will automatically form topic threads here.
                </p>
            </x-card>
        @else
            <div
                x-data="{
                    activeTopic: 'all',
                    filterEntries(tag) {
                        return this.activeTopic === 'all' || this.activeTopic === tag;
                    }
                }"
                class="space-y-6"
            >
                <!-- Alpine Filter Chip Bar (Instant Client-Side Filtering) -->
                <div class="bg-white p-4 rounded-xl border border-stone-200/80 shadow-2xs">
                    <div class="flex items-center gap-2 mb-2.5">
                        <span class="text-[11px] font-semibold text-stone-400 uppercase tracking-wider">
                            Filter by Research Area:
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <button
                            type="button"
                            @click="activeTopic = 'all'"
                            class="px-3.5 py-1.5 rounded-lg text-xs font-medium transition shadow-2xs"
                            :class="activeTopic === 'all'
                                ? 'bg-blue-600 text-white'
                                : 'bg-stone-50 hover:bg-stone-100 text-stone-700 border border-stone-200'"
                        >
                            All Topics ({{ $totalTags }})
                        </button>

                        @foreach ($groupedEntries->keys() as $tag)
                            <button
                                type="button"
                                @click="activeTopic = '{{ addslashes($tag) }}'"
                                class="px-3.5 py-1.5 rounded-lg text-xs font-medium transition shadow-2xs"
                                :class="activeTopic === '{{ addslashes($tag) }}'
                                    ? 'bg-blue-600 text-white'
                                    : 'bg-stone-50 hover:bg-stone-100 text-stone-700 border border-stone-200'"
                            >
                                {{ $tag }}
                                <span class="opacity-60 ml-1 text-[10px]">
                                    ({{ count($groupedEntries[$tag]) }})
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Grouped Thread Cards -->
                <div class="space-y-6">
                    @foreach ($groupedEntries as $tag => $entries)
                        <div
                            x-show="filterEntries('{{ addslashes($tag) }}')"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="bg-white rounded-xl border border-stone-200/80 shadow-xs p-6"
                        >
                            <!-- Thread Header -->
                            <div class="flex items-center justify-between gap-3 pb-4 mb-4 border-b border-stone-100">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center font-heading font-bold text-sm shrink-0">
                                        #
                                    </div>
                                    <div>
                                        <h3 class="font-heading font-bold text-lg text-stone-900">
                                            {{ $tag }}
                                        </h3>
                                        <p class="text-[11px] text-stone-400">
                                            {{ count($entries) }} academic record{{ count($entries) === 1 ? '' : 's' }} in this thread
                                        </p>
                                    </div>
                                </div>

                                <x-badge variant="brand" size="sm">
                                    {{ count($entries) }} {{ count($entries) === 1 ? 'entry' : 'entries' }}
                                </x-badge>
                            </div>

                            <!-- Tactile Entry Tiles (Cards Within Cards) -->
                            <div class="space-y-3">
                                @foreach ($entries as $entry)
                                    @php
                                        $isProject = $entry['type'] === 'course_project';
                                    @endphp

                                    <div class="group rounded-xl border border-stone-200/80 bg-stone-50/50 hover:bg-white hover:border-stone-300 hover:shadow-xs p-4 transition-all duration-150 {{ $isProject ? 'border-l-4 border-l-blue-600' : 'border-l-4 border-l-amber-600' }}">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                            <!-- Left Side: Icon + Title + Meta -->
                                            <div class="flex items-start gap-3.5">
                                                <!-- Semantic Category Icon -->
                                                <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 mt-0.5 {{ $isProject ? 'bg-blue-50 text-blue-700 border border-blue-200/60' : 'bg-amber-50 text-amber-800 border border-amber-200/60' }}">
                                                    @if ($isProject)
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                                                        </svg>
                                                    @endif
                                                </div>

                                                <div>
                                                    @if ($isProject)
                                                        <a
                                                            href="{{ route('course-projects.show', $entry['model']) }}"
                                                            class="font-heading font-bold text-base text-stone-900 group-hover:text-blue-700 transition-colors flex items-center gap-1.5"
                                                        >
                                                            {{ $entry['title'] }}
                                                            <svg class="w-3.5 h-3.5 text-stone-400 group-hover:text-blue-600 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                            </svg>
                                                        </a>
                                                        <p class="text-xs text-stone-500 mt-0.5">
                                                            Course Project &middot; {{ $entry['model']->term ?? '' }} {{ $entry['model']->year ?? '' }} &middot; {{ $entry['model']->course_name ?? '' }}
                                                        </p>
                                                    @else
                                                        <div class="flex flex-wrap items-center gap-2">
                                                            <span class="font-heading font-bold text-base text-stone-900">
                                                                {{ $entry['title'] }}
                                                            </span>
                                                            <span class="inline-flex items-center gap-1 text-[10px] text-amber-900 bg-amber-50 px-2 py-0.5 rounded border border-amber-200 font-medium" title="In-progress thesis work is private to the group and assigned faculty">
                                                                <svg class="w-3 h-3 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                                </svg>
                                                                Under Supervision
                                                            </span>
                                                        </div>
                                                        <p class="text-xs text-stone-500 mt-0.5">
                                                            Thesis Proposal &middot; Private supervision workflow
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Right Side: Type Link & Date -->
                                            <div class="flex items-center gap-3 shrink-0 sm:self-center pl-12 sm:pl-0">
                                                @if ($isProject)
                                                    <span class="text-xs font-semibold text-blue-700 group-hover:underline flex items-center gap-1">
                                                        Showcase &rarr;
                                                    </span>
                                                @else
                                                    <x-badge variant="accent" size="sm">Thesis</x-badge>
                                                @endif

                                                <span class="font-mono text-xs text-stone-500 bg-white px-2.5 py-1 rounded-md border border-stone-200/80 shadow-2xs font-medium">
                                                    {{ $entry['created_at']->format('M Y') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </x-container>
</x-app-layout>
