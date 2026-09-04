<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold text-stone-400 uppercase tracking-wider">
                        Thesis Group &middot; Formed {{ $group->created_at->format('M Y') }}
                    </span>
                    @if ($group->isCompleted())
                        <x-badge variant="success" size="sm">Thesis Completed</x-badge>
                    @endif
                </div>
                <h2 class="font-heading font-bold text-2xl sm:text-3xl text-stone-900 leading-tight mt-0.5">
                    {{ $group->group_name }}
                </h2>
            </div>

            <!-- Top Header Actions -->
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('thesis-groups.index') }}" class="inline-flex items-center text-xs font-medium text-stone-500 hover:text-stone-700 bg-white border border-stone-200 px-3 py-2 rounded-lg shadow-xs transition">
                    &larr; Back to Groups
                </a>

                @if ($isMember)
                    <a href="{{ route('thesis-groups.edit', $group) }}" class="inline-flex items-center text-xs font-medium text-stone-700 bg-white hover:bg-stone-50 border border-stone-200 px-3.5 py-2 rounded-lg shadow-xs transition">
                        Edit Group
                    </a>
                    <x-delete-modal
                        :action="route('thesis-groups.destroy', $group)"
                        title="Delete Thesis Group"
                        message="Are you sure you want to delete this thesis group? All milestones, proposal records, and meeting logs will be permanently deleted."
                        buttonText="Delete"
                    />
                @endif
            </div>
        </div>
    </x-slot>

    <x-container size="wide">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            <!-- Left Main Column (Milestones Workflow) -->
            <div class="lg:col-span-2 space-y-6">
                <x-card>
                    <div class="flex items-center justify-between gap-4 mb-5 pb-3 border-b border-stone-100">
                        <div>
                            <h3 class="font-heading font-bold text-xl text-stone-900">
                                Project Milestones
                            </h3>
                            <p class="text-xs text-stone-500 mt-0.5">
                                Supervised checkpoint stages &middot; {{ $group->milestones->count() }} total
                            </p>
                        </div>

                        @if ($isSupervisor && $group->proposal && $group->proposal->status === 'approved')
                            <a
                                href="{{ route('thesis-groups.milestones.create', $group) }}"
                                class="inline-flex items-center text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 px-3.5 py-2 rounded-lg shadow-xs transition shrink-0"
                            >
                                + Add Milestone
                            </a>
                        @endif
                    </div>

                    <div class="space-y-4">
                        @forelse ($group->milestones as $index => $milestone)
                            @php
                                $isDone = (bool) $milestone->completed_at;
                                $isOverdue = !$isDone && $milestone->deadline->isPast();

                                $cardBorder = match (true) {
                                    $isDone => 'border-l-4 border-l-emerald-500 bg-emerald-50/15',
                                    $isOverdue => 'border-l-4 border-l-rose-500 bg-rose-50/15',
                                    default => 'border-l-4 border-l-stone-300 bg-white',
                                };
                            @endphp

                            <div class="rounded-xl border border-stone-200/80 shadow-xs p-5 transition hover:shadow-sm {{ $cardBorder }}">
                                <!-- Top Row: Sequence, Title, Badges -->
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-start gap-3">
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-stone-100 text-stone-600 font-mono text-xs font-bold shrink-0 mt-0.5">
                                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <div>
                                            <h4 class="font-heading font-bold text-base sm:text-lg text-stone-900 leading-snug">
                                                {{ $milestone->title }}
                                            </h4>

                                            <div class="flex items-center gap-2 mt-1 text-xs">
                                                <span class="flex items-center gap-1.5 {{ $isOverdue ? 'text-rose-700 font-semibold' : 'text-stone-500' }}">
                                                    <svg class="w-3.5 h-3.5 text-stone-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $isOverdue ? 'Overdue: ' : 'Due ' }}{{ $milestone->deadline->format('M d, Y') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Status & Deliverable Type Badges -->
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <x-badge variant="neutral" size="sm">
                                            {{ $milestone->deliverable_type }}
                                        </x-badge>

                                        @if ($isDone)
                                            <x-badge variant="success" size="sm">✓ Completed</x-badge>
                                        @elseif ($isOverdue)
                                            <x-badge variant="danger" size="sm">Overdue</x-badge>
                                        @else
                                            <x-badge variant="brand" size="sm">In Progress</x-badge>
                                        @endif
                                    </div>
                                </div>

                                <!-- Description -->
                                <p class="text-sm text-stone-600 mt-3 pl-9 leading-relaxed">
                                    {{ $milestone->description }}
                                </p>

                                <!-- Bottom Action & Deliverables Bar -->
                                <div class="flex flex-wrap items-center justify-between gap-3 mt-4 pt-3.5 border-t border-stone-100 pl-9 text-xs">
                                    <div class="flex items-center gap-2">
                                        <!-- Documents Chip -->
                                        <a
                                            href="{{ route('thesis-groups.milestones.documents.index', [$group, $milestone]) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border text-xs font-medium transition {{ $milestone->documents_count > 0 ? 'bg-white text-stone-800 border-stone-200 hover:border-stone-300 shadow-2xs' : 'bg-stone-50 text-stone-500 border-stone-200/60 hover:bg-stone-100' }}"
                                        >
                                            <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Documents
                                            <span class="font-bold text-[11px] {{ $milestone->documents_count > 0 ? 'text-blue-700' : 'text-stone-400' }}">
                                                ({{ $milestone->documents_count }})
                                            </span>
                                        </a>

                                        <!-- Feedback Chip -->
                                        <a
                                            href="{{ route('thesis-groups.milestones.feedback.index', [$group, $milestone]) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border text-xs font-medium transition {{ $milestone->feedback_count > 0 ? 'bg-amber-50 text-amber-900 border-amber-200 hover:bg-amber-100' : 'bg-stone-50 text-stone-500 border-stone-200/60 hover:bg-stone-100' }}"
                                        >
                                            <svg class="w-3.5 h-3.5 {{ $milestone->feedback_count > 0 ? 'text-amber-700' : 'text-stone-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                            </svg>
                                            Feedback
                                            <span class="font-bold text-[11px] {{ $milestone->feedback_count > 0 ? 'text-amber-800' : 'text-stone-400' }}">
                                                ({{ $milestone->feedback_count }})
                                            </span>
                                        </a>
                                    </div>

                                    <!-- Right: Supervisor Toggle & Completion Date -->
                                    <div class="flex items-center gap-3">
                                        @if ($milestone->completed_at)
                                            <span class="text-[11px] font-medium text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                                Completed {{ $milestone->completed_at->format('M d, Y') }}
                                            </span>
                                        @endif

                                        @if ($isSupervisor)
                                            <form method="POST" action="{{ route('thesis-groups.milestones.toggle-complete', [$group, $milestone]) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button
                                                    type="submit"
                                                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition shadow-2xs {{ $isDone ? 'bg-white hover:bg-stone-50 text-stone-700 border border-stone-200' : 'bg-emerald-700 hover:bg-emerald-800 text-white' }}"
                                                >
                                                    {{ $isDone ? 'Mark Incomplete' : 'Mark Complete' }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="w-12 h-12 rounded-full bg-stone-100 mx-auto flex items-center justify-center text-stone-400 mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <h4 class="font-heading font-semibold text-stone-800 text-base">No milestones recorded yet</h4>
                                <p class="text-xs text-stone-500 mt-1 max-w-sm mx-auto">
                                    @if ($group->proposal && $group->proposal->status === 'approved')
                                        The supervisor can now set checkpoint dates and deliverable expectations.
                                    @else
                                        Milestones can be created once the thesis proposal is approved by the assigned supervisor.
                                    @endif
                                </p>
                            </div>
                        @endforelse
                    </div>
                </x-card>
            </div>

            <!-- Right Sidebar Column (Hub, Heatmap, Roster) -->
            <div class="space-y-6">

                <!-- Quick-Links Hub Card -->
                <x-card title="Thesis Hub" subtitle="Supervision controls &amp; records">
                    <div class="divide-y divide-stone-100 text-xs">
                        <!-- Proposal Row -->
                        <div class="py-3 first:pt-0 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-stone-100 text-stone-600 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-medium text-stone-900 block">Proposal</span>
                                    <span class="text-[11px] text-stone-400">
                                        {{ $group->proposal ? ucfirst(str_replace('_', ' ', $group->proposal->status)) : 'Not submitted' }}
                                    </span>
                                </div>
                            </div>
                            @if ($group->proposal)
                                <a href="{{ route('proposals.show', $group->proposal) }}" class="inline-flex items-center text-xs font-semibold text-blue-700 hover:text-blue-900">
                                    View &rarr;
                                </a>
                            @else
                                <a href="{{ route('proposals.create') }}" class="inline-flex items-center text-xs font-semibold text-blue-700 hover:text-blue-900">
                                    Submit &rarr;
                                </a>
                            @endif
                        </div>

                        <!-- Meeting Log Row -->
                        <div class="py-3 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-stone-100 text-stone-600 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-medium text-stone-900 block">Meeting Log</span>
                                    <span class="text-[11px] text-stone-400">Sessions &amp; action items</span>
                                </div>
                            </div>
                            <a href="{{ route('thesis-groups.meetings.index', $group) }}" class="inline-flex items-center text-xs font-semibold text-blue-700 hover:text-blue-900">
                                View &rarr;
                            </a>
                        </div>

                        <!-- Matching Engine Row -->
                        <div class="py-3 last:pb-0 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-stone-100 text-stone-600 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-medium text-stone-900 block">Supervisor Matching</span>
                                    <span class="text-[11px] text-stone-400">Compatibility engine</span>
                                </div>
                            </div>
                            <a href="{{ route('thesis-groups.supervisor-matches', $group) }}" class="inline-flex items-center text-xs font-semibold text-blue-700 hover:text-blue-900">
                                Browse &rarr;
                            </a>
                        </div>
                    </div>
                </x-card>

                <!-- Feature 17 & Item 9: Rebuilt Thesis Progress Heatmap (with Live Alpine Inspector) -->
                <x-card title="Activity Heatmap" subtitle="Weekly supervision activity density">
                    @if (empty($activityHeatmap))
                        <div class="py-6 text-center">
                            <p class="text-stone-400 text-xs leading-relaxed">
                                The heatmap will start tracking once the thesis proposal is officially approved.
                            </p>
                        </div>
                    @else
                        <div
                            x-data="{
                                activeWeek: null,
                                defaultMessage: 'Hover over any week cell to view activity breakdown'
                            }"
                            class="pt-1"
                        >
                            <!-- Heatmap Cell Grid -->
                            <div class="flex flex-wrap gap-1.5 p-2 bg-stone-50/80 rounded-lg border border-stone-100">
                                @foreach ($activityHeatmap as $week)
                                    @php
                                        $shade = match (true) {
                                            $week['total'] === 0 => 'bg-white border border-stone-200/80',
                                            $week['total'] === 1 => 'bg-emerald-200 border border-emerald-300',
                                            $week['total'] <= 3 => 'bg-emerald-400 border border-emerald-500',
                                            default => 'bg-emerald-600 border border-emerald-700',
                                        };

                                        $detailJson = json_encode([
                                            'date' => $week['week_start']->format('M d, Y'),
                                            'total' => $week['total'],
                                            'documents' => $week['documents'],
                                            'meetings' => $week['meetings'],
                                            'milestones' => $week['milestones'],
                                        ]);
                                    @endphp

                                    <div
                                        class="w-5 h-5 rounded-md cursor-pointer transition-transform duration-150 hover:scale-125 hover:z-10 {{ $shade }}"
                                        @mouseenter="activeWeek = {{ $detailJson }}"
                                        @mouseleave="activeWeek = null"
                                    ></div>
                                @endforeach
                            </div>

                            <!-- Live Dynamic Inspector (Underneath Grid) -->
                            <div class="mt-3 p-2.5 rounded-lg border border-stone-100 bg-stone-50/60 min-h-[44px] flex items-center justify-between text-xs">
                                <template x-if="!activeWeek">
                                    <span class="text-stone-400 text-[11px] italic" x-text="defaultMessage"></span>
                                </template>

                                <template x-if="activeWeek">
                                    <div class="w-full flex items-center justify-between text-[11px]">
                                        <span class="font-semibold text-stone-800" x-text="activeWeek.date"></span>
                                        <div class="flex items-center gap-2 text-stone-600">
                                            <span><strong class="text-stone-900" x-text="activeWeek.documents"></strong> docs</span>
                                            <span>&middot;</span>
                                            <span><strong class="text-stone-900" x-text="activeWeek.meetings"></strong> mtgs</span>
                                            <span>&middot;</span>
                                            <span><strong class="text-stone-900" x-text="activeWeek.milestones"></strong> done</span>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Density Legend -->
                            <div class="flex items-center justify-between mt-3 text-[10px] text-stone-400 px-1">
                                <span>Less Activity</span>
                                <div class="flex items-center gap-1">
                                    <div class="w-3 h-3 rounded-xs bg-white border border-stone-200"></div>
                                    <div class="w-3 h-3 rounded-xs bg-emerald-200"></div>
                                    <div class="w-3 h-3 rounded-xs bg-emerald-400"></div>
                                    <div class="w-3 h-3 rounded-xs bg-emerald-600"></div>
                                </div>
                                <span>More</span>
                            </div>
                        </div>
                    @endif
                </x-card>

                <!-- Group Roster Card -->
                <x-card title="Roster & Faculty">
                    <!-- Assigned Supervisor -->
                    <div class="mb-4 pb-3 border-b border-stone-100">
                        <span class="text-[11px] font-semibold text-stone-400 uppercase tracking-wider block mb-1.5">Supervisor</span>
                        @if ($group->supervisor)
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-blue-50 text-blue-700 text-xs font-bold flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($group->supervisor->user->name ?? 'F', 0, 1)) }}
                                </div>
                                <div>
                                    <span class="text-xs font-semibold text-stone-900 block">{{ $group->supervisor->user->name }}</span>
                                    <span class="text-[11px] text-stone-400">{{ $group->supervisor->designation }}</span>
                                </div>
                            </div>
                        @else
                            <span class="text-xs text-stone-400 italic">Not assigned yet</span>
                        @endif
                    </div>

                    <!-- Member Students -->
                    <div>
                        <span class="text-[11px] font-semibold text-stone-400 uppercase tracking-wider block mb-2">
                            Students ({{ $group->students->count() }})
                        </span>
                        <ul class="space-y-2">
                            @foreach ($group->students as $student)
                                <li class="flex items-center gap-2.5 text-xs">
                                    <div class="w-6 h-6 rounded-full bg-stone-100 text-stone-600 text-[10px] font-bold flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($student->user->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div class="truncate">
                                        <span class="font-medium text-stone-900 block truncate">{{ $student->user->name ?? 'Student' }}</span>
                                        <span class="text-[10px] text-stone-400">{{ $student->department ?? 'CS' }}, Batch {{ $student->batch ?? 'N/A' }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </x-card>

            </div>
        </div>
    </x-container>
</x-app-layout>
