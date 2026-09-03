<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold text-stone-400 uppercase tracking-wider">
                        {{ $project->term }} {{ $project->year }} &middot; {{ $project->course_name }}
                    </span>
                    @if ($project->is_open_for_continuation)
                        <x-badge variant="success" size="sm">Open for Continuation</x-badge>
                    @endif
                </div>
                <h2 class="font-heading font-bold text-2xl sm:text-3xl text-stone-900 leading-tight mt-1">
                    {{ $project->title }}
                </h2>
            </div>

            <!-- Header Action Controls -->
            <div class="flex items-center gap-2 shrink-0">
                @if ($canToggle)
                    <form method="POST" action="{{ route('course-projects.toggle-continuation', $project) }}">
                        @csrf
                        @method('PATCH')
                        <button
                            type="submit"
                            class="inline-flex items-center text-xs font-medium px-3.5 py-2 rounded-lg border transition shadow-xs {{ $project->is_open_for_continuation ? 'bg-stone-100 hover:bg-stone-200 text-stone-700 border-stone-200' : 'bg-white hover:bg-stone-50 text-stone-700 border-stone-200' }}"
                        >
                            {{ $project->is_open_for_continuation ? 'Close Continuation' : 'Open for Continuation' }}
                        </button>
                    </form>
                @endif

                @if ($isOwner)
                    <a href="{{ route('course-projects.edit', $project) }}" class="inline-flex items-center text-xs font-medium text-stone-700 bg-white hover:bg-stone-50 border border-stone-200 px-3.5 py-2 rounded-lg shadow-xs transition">
                        Edit
                    </a>
                    <x-delete-modal
                        :action="route('course-projects.destroy', $project)"
                        title="Delete Course Project"
                        message="Are you sure you want to delete this project? Its screenshot records, continuations link, and contributions will be removed."
                        buttonText="Delete"
                    />
                @endif
            </div>
        </div>
    </x-slot>

    <x-container size="wide">
        <!-- Continuation Claim Banner -->
        @if ($project->is_open_for_continuation && auth()->check() && !$canToggle)
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 shadow-xs">
                <div>
                    <h4 class="font-heading font-semibold text-emerald-900 text-sm">This project is open for continuation</h4>
                    <p class="text-xs text-emerald-700 mt-0.5">You can claim this project to build upon its code, architecture, or research for your own course.</p>
                </div>
                <a href="{{ route('course-projects.claim', $project) }}" class="inline-flex items-center justify-center text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800 px-4 py-2 rounded-lg shadow-xs transition shrink-0">
                    Claim &amp; Continue
                </a>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Main Column (Description & Screenshots) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Overview Card -->
                <x-card title="Project Overview">
                    <p class="text-stone-700 leading-relaxed text-sm sm:text-base whitespace-pre-line">
                        {{ $project->description }}
                    </p>

                    <!-- Links (GitHub / Demo) -->
                    @if ($project->github_link || $project->demo_link)
                        <div class="flex flex-wrap gap-3 mt-6 pt-5 border-t border-stone-100">
                            @if ($project->github_link)
                                <a
                                    href="{{ $project->github_link }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center gap-2 text-xs font-medium text-stone-700 bg-stone-50 hover:bg-stone-100 border border-stone-200 px-3.5 py-2 rounded-lg transition"
                                >
                                    <svg class="w-4 h-4 text-stone-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" />
                                    </svg>
                                    GitHub Repository
                                </a>
                            @endif

                            @if ($project->demo_link)
                                <a
                                    href="{{ $project->demo_link }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center gap-2 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3.5 py-2 rounded-lg transition"
                                >
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                    Live Demo
                                </a>
                            @endif
                        </div>
                    @endif
                </x-card>

                <!-- Screenshots Gallery Card -->
                @if ($project->screenshot_paths && count($project->screenshot_paths) > 0)
                    <x-card title="Screenshots & Previews" subtitle="Artifacts from project deliverables">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            @foreach ($project->screenshot_paths as $path)
                                <a
                                    href="{{ asset('storage/' . $path) }}"
                                    target="_blank"
                                    class="group rounded-lg border border-stone-200 overflow-hidden bg-stone-100 aspect-video block relative"
                                >
                                    <img
                                        src="{{ asset('storage/' . $path) }}"
                                        alt="Screenshot"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    >
                                    <div class="absolute inset-0 bg-stone-900/0 group-hover:bg-stone-900/20 transition-colors flex items-center justify-center opacity-0 group-hover:opacity-100">
                                        <span class="bg-white/90 text-stone-800 text-[11px] font-medium px-2.5 py-1 rounded shadow-xs">
                                            View full image
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </x-card>
                @endif

                <!-- Feature 12: Project Lineage Card -->
                @if ($project->continuedFrom || $project->continuations->isNotEmpty())
                    <x-card title="Project Lineage" subtitle="Academic continuity across semesters">
                        @if ($project->continuedFrom)
                            <div class="mb-4 pb-4 border-b border-stone-100">
                                <span class="text-xs font-semibold text-stone-400 uppercase tracking-wider block mb-1">Continued From</span>
                                <a href="{{ route('course-projects.show', $project->continuedFrom) }}" class="font-heading font-medium text-blue-700 hover:underline flex items-center gap-1.5 text-sm">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    {{ $project->continuedFrom->title }}
                                </a>
                            </div>
                        @endif

                        @if ($project->continuations->isNotEmpty())
                            <div>
                                <span class="text-xs font-semibold text-stone-400 uppercase tracking-wider block mb-2">Subsequent Continuations</span>
                                <ul class="space-y-2">
                                    @foreach ($project->continuations as $continuation)
                                        <li class="flex items-center gap-2 text-sm bg-stone-50 px-3 py-2 rounded-lg border border-stone-100">
                                            <svg class="w-3.5 h-3.5 text-stone-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                            </svg>
                                            <a href="{{ route('course-projects.show', $continuation) }}" class="font-medium text-stone-800 hover:text-blue-700">
                                                {{ $continuation->title }}
                                            </a>
                                            <span class="text-xs text-stone-400 ml-auto">
                                                {{ $continuation->term }} {{ $continuation->year }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </x-card>
                @endif
            </div>

            <!-- Right Sidebar Column (Meta, Tech Stack, Contributions) -->
            <div class="space-y-6">
                <!-- Stack & Topics Card -->
                <x-card title="Attributes">
                    <div class="mb-4">
                        <span class="text-xs font-semibold text-stone-400 uppercase tracking-wider block mb-2">Tech Stack</span>
                        <div class="flex flex-wrap gap-1.5">
                            @forelse ($project->tech_stack ?? [] as $tech)
                                <x-badge variant="brand" size="md">{{ $tech }}</x-badge>
                            @empty
                                <span class="text-xs text-stone-400 italic">None specified.</span>
                            @endforelse
                        </div>
                    </div>

                    @if (!empty($project->research_tags))
                        <div class="pt-4 border-t border-stone-100">
                            <span class="text-xs font-semibold text-stone-400 uppercase tracking-wider block mb-2">Research Tags</span>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach ($project->research_tags as $tag)
                                    <x-badge variant="accent" size="md">{{ $tag }}</x-badge>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-stone-100">
                        <span class="text-xs font-semibold text-stone-400 uppercase tracking-wider block mb-1">Authors &amp; Team</span>
                        <div class="text-sm text-stone-700 space-y-1 mt-2">
                            @if ($project->students->isNotEmpty())
                                @foreach ($project->students as $member)
                                    <div class="flex items-center gap-2">
                                        <div class="w-5 h-5 rounded-full bg-stone-100 text-stone-600 text-[10px] font-bold flex items-center justify-center">
                                            {{ strtoupper(substr($member->user->name ?? 'M', 0, 1)) }}
                                        </div>
                                        <span>{{ $member->user->name }}</span>
                                    </div>
                                @endforeach
                            @endif
                            @if (!empty($project->team_members))
                                @foreach ($project->team_members as $external)
                                    <div class="flex items-center gap-2 text-stone-500 text-xs">
                                        <span class="w-5 text-center text-stone-400">&bull;</span>
                                        <span>{{ $external }}</span>
                                        <span class="text-[10px] text-stone-400 italic">(external)</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </x-card>

                <!-- Feature 18: Contribution Transparency Card -->
                <x-card title="Contribution Breakdown" subtitle="Locked self-reported student contributions">
                    <div class="space-y-3 pt-1">
                        @forelse ($project->students as $member)
                            @php
                                $contribution = $project->contributions->firstWhere('student_id', $member->id);
                            @endphp
                            <div class="p-3 rounded-lg border border-stone-100 bg-stone-50/70">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="font-medium text-xs text-stone-900">{{ $member->user->name }}</span>
                                    @if ($contribution && $contribution->percentage !== null)
                                        <span class="text-[11px] font-semibold text-stone-600 bg-white border border-stone-200 px-2 py-0.5 rounded">
                                            ~{{ $contribution->percentage }}%
                                        </span>
                                    @endif
                                </div>
                                @if ($contribution)
                                    <p class="text-xs text-stone-600 mt-1.5 leading-relaxed">{{ $contribution->description }}</p>
                                @else
                                    <p class="text-[11px] text-stone-400 mt-1 italic">No contribution logged yet.</p>
                                @endif
                            </div>
                        @empty
                            <p class="text-xs text-stone-400 italic">No linked team accounts for this project.</p>
                        @endforelse

                        @if ($canLogContribution)
                            <div class="pt-3 border-t border-stone-100">
                                <a
                                    href="{{ route('course-projects.contributions.create', $project) }}"
                                    class="w-full inline-flex items-center justify-center text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 py-2 px-3 rounded-lg transition"
                                >
                                    Log Your Contribution
                                </a>
                            </div>
                        @endif
                    </div>
                </x-card>
            </div>
        </div>
    </x-container>
</x-app-layout>
