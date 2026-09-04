<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h2 class="font-heading font-bold text-2xl sm:text-3xl text-stone-900 leading-tight">
                    Dashboard
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">
                    Academic supervision &amp; project showcase portal &middot; {{ now()->format('l, F j') }}
                </p>
            </div>
        </div>
    </x-slot>

    <x-container size="wide">
        @if (!$student && !$supervisor)
            <!-- First-Time Profile Selection Card -->
            <x-card class="text-center py-12 max-w-2xl mx-auto my-6">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-700 mx-auto flex items-center justify-center font-heading font-bold text-2xl shadow-xs mb-4">
                    TB
                </div>
                <h3 class="font-heading font-bold text-2xl text-stone-900">Welcome to ThesisBridge</h3>
                <p class="text-sm text-stone-600 mt-2 mb-8 max-w-md mx-auto leading-relaxed">
                    To connect you with thesis groups, proposal evaluations, and course project showcases, please establish your platform profile.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-3.5 max-w-sm mx-auto">
                    <a href="{{ route('students.create') }}" class="inline-flex items-center justify-center text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 px-5 py-3 rounded-lg shadow-xs transition">
                        I am a Student &rarr;
                    </a>
                    <a href="{{ route('supervisors.create') }}" class="inline-flex items-center justify-center text-xs font-semibold text-stone-800 bg-white hover:bg-stone-50 border border-stone-200 px-5 py-3 rounded-lg shadow-xs transition">
                        I am a Faculty Supervisor &rarr;
                    </a>
                </div>
            </x-card>

        @elseif ($student)
            <!-- Student Dashboard -->
            <div class="space-y-6">
                <!-- Welcome Hero Card -->
                <x-card class="mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-full bg-blue-50 border border-blue-200/60 flex items-center justify-center text-blue-700 font-heading font-bold text-lg shrink-0">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-heading font-bold text-xl text-stone-900">
                                        Welcome back, {{ auth()->user()->name }}
                                    </h3>
                                    <x-badge variant="brand" size="sm">Student</x-badge>
                                </div>
                                <p class="text-xs text-stone-500 mt-0.5">
                                    {{ $student->department ?? 'Department not set' }} &middot; Batch {{ $student->batch ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <a href="{{ route('students.show', $student) }}" class="inline-flex items-center text-xs font-medium text-stone-700 bg-white hover:bg-stone-50 border border-stone-200 px-3.5 py-2 rounded-lg shadow-2xs transition shrink-0">
                            View My Profile &rarr;
                        </a>
                    </div>
                </x-card>

                <!-- Student Quick Actions Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4.5">
                    @if ($thesisGroup)
                        <!-- My Thesis Group Tile -->
                        <a
                            href="{{ route('thesis-groups.show', $thesisGroup) }}"
                            class="group bg-white rounded-xl border border-stone-200/80 shadow-xs hover:shadow-md hover:border-stone-300 p-5 transition-all duration-200 flex items-start gap-4"
                        >
                            <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-700 border border-blue-200/60 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-heading font-bold text-base text-stone-900 group-hover:text-blue-700 transition">
                                        My Thesis Group
                                    </h4>
                                    <span class="text-stone-400 group-hover:text-blue-600 transition">&rarr;</span>
                                </div>
                                <p class="text-xs text-stone-500 mt-1">
                                    {{ $thesisGroup->group_name }} &middot; View milestones &amp; proposal
                                </p>
                            </div>
                        </a>
                    @else
                        <!-- Form Group Tile -->
                        <a
                            href="{{ route('thesis-groups.create') }}"
                            class="group bg-white rounded-xl border border-dashed border-stone-300 shadow-xs hover:shadow-md hover:border-blue-400 p-5 transition-all duration-200 flex items-start gap-4"
                        >
                            <div class="w-10 h-10 rounded-lg bg-stone-100 text-stone-600 flex items-center justify-center shrink-0 mt-0.5 group-hover:bg-blue-50 group-hover:text-blue-700 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-heading font-bold text-base text-stone-900 group-hover:text-blue-700 transition">
                                        Form a Thesis Group
                                    </h4>
                                    <span class="text-stone-400 group-hover:text-blue-600 transition">&rarr;</span>
                                </div>
                                <p class="text-xs text-stone-500 mt-1">
                                    You're not in a group yet — invite members and start here.
                                </p>
                            </div>
                        </a>
                    @endif

                    <!-- Submit Project Tile -->
                    <a
                        href="{{ route('course-projects.create') }}"
                        class="group bg-white rounded-xl border border-stone-200/80 shadow-xs hover:shadow-md hover:border-stone-300 p-5 transition-all duration-200 flex items-start gap-4"
                    >
                        <div class="w-10 h-10 rounded-lg bg-stone-100 text-stone-700 flex items-center justify-center shrink-0 mt-0.5 group-hover:bg-blue-50 group-hover:text-blue-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h4 class="font-heading font-bold text-base text-stone-900 group-hover:text-blue-700 transition">
                                    Submit a Course Project
                                </h4>
                                <span class="text-stone-400 group-hover:text-blue-600 transition">&rarr;</span>
                            </div>
                            <p class="text-xs text-stone-500 mt-1">
                                Add a completed course deliverable to the public showcase.
                            </p>
                        </div>
                    </a>

                    <!-- Browse Showcase Tile -->
                    <a
                        href="{{ route('course-projects.index') }}"
                        class="group bg-white rounded-xl border border-stone-200/80 shadow-xs hover:shadow-md hover:border-stone-300 p-5 transition-all duration-200 flex items-start gap-4"
                    >
                        <div class="w-10 h-10 rounded-lg bg-stone-100 text-stone-700 flex items-center justify-center shrink-0 mt-0.5 group-hover:bg-blue-50 group-hover:text-blue-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h4 class="font-heading font-bold text-base text-stone-900 group-hover:text-blue-700 transition">
                                    Browse Course Projects
                                </h4>
                                <span class="text-stone-400 group-hover:text-blue-600 transition">&rarr;</span>
                            </div>
                            <p class="text-xs text-stone-500 mt-1">
                                Explore student prototypes, repos, and open continuations.
                            </p>
                        </div>
                    </a>

                    <!-- Research Thread Map Tile -->
                    <a
                        href="{{ route('research-thread-map') }}"
                        class="group bg-white rounded-xl border border-stone-200/80 shadow-xs hover:shadow-md hover:border-stone-300 p-5 transition-all duration-200 flex items-start gap-4"
                    >
                        <div class="w-10 h-10 rounded-lg bg-stone-100 text-stone-700 flex items-center justify-center shrink-0 mt-0.5 group-hover:bg-blue-50 group-hover:text-blue-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h4 class="font-heading font-bold text-base text-stone-900 group-hover:text-blue-700 transition">
                                    Research Thread Map
                                </h4>
                                <span class="text-stone-400 group-hover:text-blue-600 transition">&rarr;</span>
                            </div>
                            <p class="text-xs text-stone-500 mt-1">
                                Connect academic output across topics and timelines.
                            </p>
                        </div>
                    </a>
                </div>
            </div>

        @else
            <!-- Supervisor Dashboard -->
            <div class="space-y-6">
                <!-- Welcome Hero Card -->
                <x-card class="mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-full bg-blue-50 border border-blue-200/60 flex items-center justify-center text-blue-700 font-heading font-bold text-lg shrink-0">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-heading font-bold text-xl text-stone-900">
                                        Welcome back, {{ auth()->user()->name }}
                                    </h3>
                                    <x-badge variant="accent" size="sm">Faculty Supervisor</x-badge>
                                </div>
                                <p class="text-xs text-stone-500 mt-0.5">
                                    {{ $supervisor->designation }} &middot; Load: {{ $supervisor->currentLoad() }} / {{ $supervisor->max_capacity }} groups
                                </p>
                            </div>
                        </div>

                        <a href="{{ route('supervisors.show', $supervisor) }}" class="inline-flex items-center text-xs font-medium text-stone-700 bg-white hover:bg-stone-50 border border-stone-200 px-3.5 py-2 rounded-lg shadow-2xs transition shrink-0">
                            View Faculty Profile &rarr;
                        </a>
                    </div>
                </x-card>

                <!-- Ghost Groups Alert Card (Accent Crimson) -->
                @if ($ghostGroups->isNotEmpty())
                    <div class="bg-rose-50/80 border border-rose-200 rounded-xl p-5 shadow-2xs">
                        <div class="flex items-start gap-3.5">
                            <div class="p-2 bg-rose-100 text-rose-800 rounded-full shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between gap-2">
                                    <h4 class="font-heading font-bold text-base text-rose-900">
                                        Attention Required: {{ $ghostGroups->count() }} Silent Group{{ $ghostGroups->count() === 1 ? '' : 's' }}
                                    </h4>
                                    <x-badge variant="danger" size="sm">Ghost Alert</x-badge>
                                </div>
                                <p class="text-xs text-rose-700 mt-0.5 leading-relaxed">
                                    No student-initiated document uploads or meeting logs detected within the configured threshold.
                                </p>

                                <div class="mt-3 space-y-2">
                                    @foreach ($ghostGroups as $group)
                                        <div class="bg-white/80 rounded-lg p-3 border border-rose-200/80 flex items-center justify-between gap-3 text-xs">
                                            <a href="{{ route('thesis-groups.show', $group) }}" class="font-medium text-stone-900 hover:text-rose-700 underline">
                                                {{ $group->group_name }}
                                            </a>
                                            <span class="text-rose-700 font-medium bg-rose-100/80 px-2.5 py-1 rounded">
                                                No activity in {{ $group->daysSinceLastActivity() }} days
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Supervisor Quick Actions Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4.5">
                    <!-- My Supervised Groups -->
                    <a
                        href="{{ route('thesis-groups.supervised') }}"
                        class="group bg-white rounded-xl border border-stone-200/80 shadow-xs hover:shadow-md hover:border-stone-300 p-5 transition-all duration-200 flex items-start gap-4"
                    >
                        <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-700 border border-blue-200/60 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h4 class="font-heading font-bold text-base text-stone-900 group-hover:text-blue-700 transition">
                                    My Supervised Groups
                                </h4>
                                <span class="text-stone-400 group-hover:text-blue-600 transition">&rarr;</span>
                            </div>
                            <p class="text-xs text-stone-500 mt-1">
                                View active thesis groups, checkpoints, and silence flags.
                            </p>
                        </div>
                    </a>

                    <!-- Browse All Groups -->
                    <a
                        href="{{ route('thesis-groups.index') }}"
                        class="group bg-white rounded-xl border border-stone-200/80 shadow-xs hover:shadow-md hover:border-stone-300 p-5 transition-all duration-200 flex items-start gap-4"
                    >
                        <div class="w-10 h-10 rounded-lg bg-stone-100 text-stone-700 flex items-center justify-center shrink-0 mt-0.5 group-hover:bg-blue-50 group-hover:text-blue-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h4 class="font-heading font-bold text-base text-stone-900 group-hover:text-blue-700 transition">
                                    Browse All Thesis Groups
                                </h4>
                                <span class="text-stone-400 group-hover:text-blue-600 transition">&rarr;</span>
                            </div>
                            <p class="text-xs text-stone-500 mt-1">
                                Department directory of all registered thesis teams.
                            </p>
                        </div>
                    </a>

                    <!-- Browse Course Projects -->
                    <a
                        href="{{ route('course-projects.index') }}"
                        class="group bg-white rounded-xl border border-stone-200/80 shadow-xs hover:shadow-md hover:border-stone-300 p-5 transition-all duration-200 flex items-start gap-4"
                    >
                        <div class="w-10 h-10 rounded-lg bg-stone-100 text-stone-700 flex items-center justify-center shrink-0 mt-0.5 group-hover:bg-blue-50 group-hover:text-blue-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h4 class="font-heading font-bold text-base text-stone-900 group-hover:text-blue-700 transition">
                                    Browse Course Projects
                                </h4>
                                <span class="text-stone-400 group-hover:text-blue-600 transition">&rarr;</span>
                            </div>
                            <p class="text-xs text-stone-500 mt-1">
                                Review student portfolio submissions and code repositories.
                            </p>
                        </div>
                    </a>

                    <!-- Research Thread Map -->
                    <a
                        href="{{ route('research-thread-map') }}"
                        class="group bg-white rounded-xl border border-stone-200/80 shadow-xs hover:shadow-md hover:border-stone-300 p-5 transition-all duration-200 flex items-start gap-4"
                    >
                        <div class="w-10 h-10 rounded-lg bg-stone-100 text-stone-700 flex items-center justify-center shrink-0 mt-0.5 group-hover:bg-blue-50 group-hover:text-blue-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h4 class="font-heading font-bold text-base text-stone-900 group-hover:text-blue-700 transition">
                                    Research Thread Map
                                </h4>
                                <span class="text-stone-400 group-hover:text-blue-600 transition">&rarr;</span>
                            </div>
                            <p class="text-xs text-stone-500 mt-1">
                                Department academic threads grouped by research area.
                            </p>
                        </div>
                    </a>
                </div>
            </div>
        @endif
    </x-container>
</x-app-layout>
