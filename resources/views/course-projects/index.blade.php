<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-heading font-bold text-2xl sm:text-3xl text-stone-900 leading-tight">
                    Course Project Showcase
                </h2>
                <p class="text-xs sm:text-sm text-stone-500 mt-1">
                    Department repository of student engineering projects, software prototypes, and continuations
                </p>
            </div>
            @auth
                <a href="{{ route('course-projects.create') }}" class="inline-flex items-center justify-center text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2.5 rounded-lg shadow-xs transition">
                    + Submit a Project
                </a>
            @endauth
        </div>
    </x-slot>

    <x-container size="wide">
        <!-- Gallery Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($projects as $project)
                <a
                    href="{{ route('course-projects.show', $project) }}"
                    class="group bg-white rounded-xl border border-stone-200/80 shadow-xs hover:shadow-md hover:border-stone-300 transition-all duration-200 flex flex-col overflow-hidden"
                >
                    <!-- Thumbnail Area -->
                    <div class="h-44 w-full bg-stone-100 border-b border-stone-100 overflow-hidden relative">
                        @if (!empty($project->screenshot_paths) && count($project->screenshot_paths) > 0)
                            <img
                                src="{{ asset('storage/' . $project->screenshot_paths[0]) }}"
                                alt="{{ $project->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            >
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-stone-50 to-stone-100/80 text-stone-400 group-hover:text-stone-500 transition">
                                <svg class="w-10 h-10 mb-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-[11px] font-medium tracking-wide uppercase">No preview image</span>
                            </div>
                        @endif

                        <!-- Continuation Status Pill -->
                        @if ($project->is_open_for_continuation)
                            <div class="absolute top-3 right-3">
                                <span class="bg-emerald-600/90 backdrop-blur-xs text-white text-[10px] font-semibold px-2 py-0.5 rounded shadow-xs">
                                    Open for Continuation
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="text-[11px] font-medium text-stone-400 uppercase tracking-wider">
                                {{ $project->term }} {{ $project->year }} &middot; {{ $project->course_name }}
                            </div>

                            <h3 class="font-heading font-bold text-lg text-stone-900 group-hover:text-blue-700 transition-colors line-clamp-1 mt-1">
                                {{ $project->title }}
                            </h3>

                            <p class="text-xs text-stone-600 line-clamp-2 mt-1.5 leading-relaxed">
                                {{ $project->description }}
                            </p>
                        </div>

                        <!-- Card Footer / Tags -->
                        <div class="mt-4 pt-3 border-t border-stone-100 flex flex-wrap gap-1.5 items-center">
                            @foreach (array_slice($project->tech_stack ?? [], 0, 3) as $tech)
                                <x-badge variant="neutral" size="sm">{{ $tech }}</x-badge>
                            @endforeach

                            @if (count($project->tech_stack ?? []) > 3)
                                <span class="text-[10px] font-medium text-stone-400">
                                    +{{ count($project->tech_stack) - 3 }} more
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full">
                    <x-card class="text-center py-12">
                        <div class="w-12 h-12 rounded-full bg-stone-100 mx-auto flex items-center justify-center text-stone-400 mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <h4 class="font-heading font-semibold text-stone-800 text-base">No projects submitted yet</h4>
                        <p class="text-xs text-stone-500 mt-1 max-w-sm mx-auto">Be the first to submit a completed course project to the department showcase.</p>
                        @auth
                            <a href="{{ route('course-projects.create') }}" class="inline-block mt-4 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg transition">
                                Submit First Project
                            </a>
                        @endauth
                    </x-card>
                </div>
            @endforelse
        </div>
    </x-container>
</x-app-layout>
