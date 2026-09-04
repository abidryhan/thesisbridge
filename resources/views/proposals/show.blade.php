<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold text-stone-400 uppercase tracking-wider">
                        Thesis Proposal &middot; {{ $proposal->thesisGroup->group_name }}
                    </span>
                </div>
                <h2 class="font-heading font-bold text-2xl sm:text-3xl text-stone-900 leading-tight mt-0.5">
                    {{ $proposal->title }}
                </h2>
            </div>

            <!-- Header Action Links -->
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('thesis-groups.show', $proposal->thesisGroup) }}" class="inline-flex items-center text-xs font-medium text-stone-600 hover:text-stone-900 bg-white border border-stone-200 px-3.5 py-2 rounded-lg shadow-xs transition">
                    &larr; Back to Thesis Group
                </a>
            </div>
        </div>
    </x-slot>

    <x-container size="wide">
        <!-- Dynamic Semantic Status Hero Banner -->
        @php
            $statusMeta = match ($proposal->status) {
                'approved' => [
                    'bg' => 'bg-emerald-50/80 border-emerald-200 text-emerald-900',
                    'badge' => 'success',
                    'icon' => 'M5 13l4 4L19 7',
                    'title' => 'Proposal Approved',
                    'desc' => 'Thesis work is officially authorized. The supervisor can now create project milestones.',
                ],
                'under_review' => [
                    'bg' => 'bg-blue-50/80 border-blue-200 text-blue-900',
                    'badge' => 'brand',
                    'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                    'title' => 'Under Supervisor Review',
                    'desc' => 'The assigned faculty supervisor is actively reviewing this proposal and its methodology.',
                ],
                'revision_required' => [
                    'bg' => 'bg-amber-50/80 border-amber-200 text-amber-900',
                    'badge' => 'warning',
                    'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                    'title' => 'Revisions Required',
                    'desc' => 'Supervisor has requested modifications before this proposal can be approved. See feedback below.',
                ],
                'rejected' => [
                    'bg' => 'bg-rose-50/80 border-rose-200 text-rose-900',
                    'badge' => 'danger',
                    'icon' => 'M6 18L18 6M6 6l12 12',
                    'title' => 'Proposal Rejected',
                    'desc' => 'This proposal was rejected. Students must edit the proposal and resubmit for reconsideration.',
                ],
                default => [
                    'bg' => 'bg-stone-50 border-stone-200 text-stone-800',
                    'badge' => 'neutral',
                    'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                    'title' => 'Proposal Submitted',
                    'desc' => 'Awaiting initial evaluation by the assigned thesis supervisor.',
                ],
            };
        @endphp

        <div class="rounded-xl border p-4.5 mb-6 flex items-start gap-3.5 shadow-2xs {{ $statusMeta['bg'] }}">
            <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 mt-0.5 bg-white/80 shadow-2xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $statusMeta['icon'] }}" />
                </svg>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-2">
                    <h3 class="font-heading font-bold text-base">{{ $statusMeta['title'] }}</h3>
                    <x-badge :variant="$statusMeta['badge']" size="sm">
                        {{ ucfirst(str_replace('_', ' ', $proposal->status)) }}
                    </x-badge>
                </div>
                <p class="text-xs sm:text-sm mt-0.5 opacity-90 leading-relaxed">{{ $statusMeta['desc'] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            <!-- Left Main Column (Academic Manuscript Document) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Academic Manuscript Card -->
                <x-card title="Proposal Manuscript" subtitle="Official academic submission record">
                    <!-- Metadata Header -->
                    <div class="pb-4 mb-5 border-b border-stone-100 flex flex-wrap items-center justify-between gap-3 text-xs">
                        <div class="flex items-center gap-2 text-stone-600">
                            <span class="font-medium text-stone-900">{{ $proposal->thesisGroup->group_name }}</span>
                            <span>&middot;</span>
                            <span>Supervisor: <strong>{{ $proposal->thesisGroup->supervisor->user->name ?? 'Unassigned' }}</strong></span>
                        </div>

                        @if (!empty($proposal->research_tags))
                            <div class="flex flex-wrap gap-1.5">
                                @foreach ($proposal->research_tags as $tag)
                                    <x-badge variant="brand" size="sm">{{ $tag }}</x-badge>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Abstract Section -->
                    <div class="mb-6">
                        <h4 class="font-heading font-bold text-sm text-stone-900 uppercase tracking-wider mb-2">
                            Abstract
                        </h4>
                        <p class="text-stone-700 leading-relaxed text-sm bg-stone-50/50 p-4 rounded-xl border border-stone-100 whitespace-pre-line">
                            {{ $proposal->abstract }}
                        </p>
                    </div>

                    <!-- Objectives Section -->
                    <div class="mb-6">
                        <h4 class="font-heading font-bold text-sm text-stone-900 uppercase tracking-wider mb-2">
                            Objectives
                        </h4>
                        <p class="text-stone-700 leading-relaxed text-sm bg-stone-50/50 p-4 rounded-xl border border-stone-100 whitespace-pre-line">
                            {{ $proposal->objectives }}
                        </p>
                    </div>

                    <!-- Methodology Section -->
                    <div>
                        <h4 class="font-heading font-bold text-sm text-stone-900 uppercase tracking-wider mb-2">
                            Methodology &amp; Approach
                        </h4>
                        <p class="text-stone-700 leading-relaxed text-sm bg-stone-50/50 p-4 rounded-xl border border-stone-100 whitespace-pre-line">
                            {{ $proposal->methodology }}
                        </p>
                    </div>
                </x-card>

                <!-- Student Resubmission Card (Only visible when revision_required or rejected) -->
                @if ($isMember && in_array($proposal->status, ['revision_required', 'rejected']))
                    <x-card title="Revise and Resubmit Proposal" subtitle="Action required by group members">
                        <div class="bg-amber-50/60 border border-amber-200 rounded-xl p-4 mb-4">
                            <p class="text-xs text-amber-900 leading-relaxed">
                                <strong>Step 1:</strong> Click <em>Edit Proposal Draft</em> to revise the manuscript text according to supervisor feedback.<br>
                                <strong>Step 2:</strong> When ready, click <em>Resubmit for Review</em> to notify your supervisor.
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <a
                                href="{{ route('proposals.edit', $proposal) }}"
                                class="inline-flex items-center text-xs font-semibold text-stone-700 bg-white hover:bg-stone-50 border border-stone-200 px-4 py-2.5 rounded-lg shadow-xs transition"
                            >
                                Edit Proposal Draft
                            </a>

                            <form method="POST" action="{{ route('proposals.resubmit', $proposal) }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="inline-flex items-center text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2.5 rounded-lg shadow-xs transition"
                                >
                                    Resubmit for Review
                                </button>
                            </form>
                        </div>
                    </x-card>
                @endif
            </div>

            <!-- Right Sidebar Column (Review Hub & Audit Trail) -->
            <div class="space-y-6">

                <!-- Supervisor Decision Hub (Alpine Progressive Disclosure) -->
                @if ($isAssignedSupervisor)
                    <x-card title="Supervisor Review Hub" subtitle="Authorize or request changes">
                        <div x-data="{ activeAction: null }" class="space-y-3 pt-1">
                            <!-- Start Review (One-click action) -->
                            @if ($proposal->canTransitionTo('under_review'))
                                <form method="POST" action="{{ route('proposals.start-review', $proposal) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 py-2.5 px-4 rounded-lg shadow-xs transition flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Move to Under Review
                                    </button>
                                </form>
                            @endif

                            <!-- Approve Proposal (One-click action) -->
                            @if ($proposal->canTransitionTo('approved'))
                                <form method="POST" action="{{ route('proposals.approve', $proposal) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800 py-2.5 px-4 rounded-lg shadow-xs transition flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Approve Proposal
                                    </button>
                                </form>
                            @endif

                            <!-- Progressive Disclosure: Request Revision -->
                            @if ($proposal->canTransitionTo('revision_required'))
                                <div>
                                    <button
                                        type="button"
                                        x-show="activeAction !== 'revision'"
                                        @click="activeAction = 'revision'"
                                        class="w-full text-xs font-semibold text-amber-900 bg-amber-50 hover:bg-amber-100 border border-amber-300/80 py-2.5 px-4 rounded-lg shadow-2xs transition flex items-center justify-center gap-2"
                                    >
                                        <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Request Revision...
                                    </button>

                                    <!-- Collapsible Reason Form -->
                                    <div
                                        x-cloak
                                        x-show="activeAction === 'revision'"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 -translate-y-2"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        class="p-4 bg-amber-50/70 border border-amber-200 rounded-xl"
                                    >
                                        <form method="POST" action="{{ route('proposals.request-revision', $proposal) }}">
                                            @csrf
                                            @method('PATCH')
                                            <label class="block text-xs font-semibold text-amber-900 mb-1.5">
                                                Revision Instructions (Required)
                                            </label>
                                            <textarea
                                                name="reason"
                                                rows="3"
                                                required
                                                placeholder="Specify what needs revision before approval..."
                                                class="w-full text-xs border-amber-300 rounded-lg p-3 mb-3 bg-white focus:border-amber-600 focus:ring-amber-600 leading-relaxed"
                                            ></textarea>
                                            <div class="flex items-center justify-between gap-2">
                                                <button
                                                    type="button"
                                                    @click="activeAction = null"
                                                    class="text-xs text-stone-500 hover:text-stone-700 font-medium px-2 py-1"
                                                >
                                                    Cancel
                                                </button>
                                                <button
                                                    type="submit"
                                                    class="text-xs font-semibold text-white bg-amber-700 hover:bg-amber-800 px-4 py-2 rounded-lg shadow-xs transition"
                                                >
                                                    Submit Revision Request
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif

                            <!-- Progressive Disclosure: Reject -->
                            @if ($proposal->canTransitionTo('rejected'))
                                <div>
                                    <button
                                        type="button"
                                        x-show="activeAction !== 'reject'"
                                        @click="activeAction = 'reject'"
                                        class="w-full text-xs font-medium text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200/80 py-2 px-4 rounded-lg transition flex items-center justify-center gap-2"
                                    >
                                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Reject Proposal...
                                    </button>

                                    <!-- Collapsible Reason Form -->
                                    <div
                                        x-cloak
                                        x-show="activeAction === 'reject'"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 -translate-y-2"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        class="p-4 bg-rose-50/70 border border-rose-200 rounded-xl"
                                    >
                                        <form method="POST" action="{{ route('proposals.reject', $proposal) }}">
                                            @csrf
                                            @method('PATCH')
                                            <label class="block text-xs font-semibold text-rose-900 mb-1.5">
                                                Rejection Reason (Required)
                                            </label>
                                            <textarea
                                                name="reason"
                                                rows="3"
                                                required
                                                placeholder="State clearly why this topic is rejected..."
                                                class="w-full text-xs border-rose-300 rounded-lg p-3 mb-3 bg-white focus:border-rose-600 focus:ring-rose-600 leading-relaxed"
                                            ></textarea>
                                            <div class="flex items-center justify-between gap-2">
                                                <button
                                                    type="button"
                                                    @click="activeAction = null"
                                                    class="text-xs text-stone-500 hover:text-stone-700 font-medium px-2 py-1"
                                                >
                                                    Cancel
                                                </button>
                                                <button
                                                    type="submit"
                                                    class="text-xs font-semibold text-white bg-rose-700 hover:bg-rose-800 px-4 py-2 rounded-lg shadow-xs transition"
                                                >
                                                    Confirm Rejection
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </x-card>
                @endif

                <!-- Editorial Vertical Audit Timeline -->
                <x-card title="Status History" subtitle="Audited chronological timeline">
                    <div class="relative pl-6 space-y-6 before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-0.5 before:bg-stone-200 pt-1">
                        @forelse ($proposal->statusHistory as $entry)
                            @php
                                $nodeColor = match ($entry->status) {
                                    'approved' => 'bg-emerald-500 ring-4 ring-emerald-100',
                                    'under_review' => 'bg-blue-600 ring-4 ring-blue-100',
                                    'revision_required' => 'bg-amber-500 ring-4 ring-amber-100',
                                    'rejected' => 'bg-rose-500 ring-4 ring-rose-100',
                                    default => 'bg-stone-400 ring-4 ring-stone-100',
                                };
                                $badgeVariant = match ($entry->status) {
                                    'approved' => 'success',
                                    'under_review' => 'brand',
                                    'revision_required' => 'warning',
                                    'rejected' => 'danger',
                                    default => 'neutral',
                                };
                            @endphp

                            <div class="relative">
                                <!-- Timeline Node Bullet -->
                                <div class="absolute -left-[29px] top-1 w-3 h-3 rounded-full {{ $nodeColor }}"></div>

                                <!-- Header: Badge + Date -->
                                <div class="flex items-baseline justify-between gap-2">
                                    <x-badge :variant="$badgeVariant" size="sm">
                                        {{ ucfirst(str_replace('_', ' ', $entry->status)) }}
                                    </x-badge>
                                    <span class="text-[10px] font-mono text-stone-400">
                                        {{ $entry->created_at->format('M d, Y') }}
                                    </span>
                                </div>

                                <!-- Decision Reason Callout -->
                                @if ($entry->reason)
                                    <div class="mt-2 p-3 rounded-lg bg-stone-50 border border-stone-200/80 text-xs text-stone-700 leading-relaxed italic">
                                        &ldquo;{{ $entry->reason }}&rdquo;
                                    </div>
                                @endif

                                <!-- Actor & Timestamp -->
                                <p class="text-[11px] text-stone-400 mt-1.5 flex items-center gap-1.5">
                                    <span>By <strong>{{ $entry->changedBy->name ?? 'System' }}</strong></span>
                                    <span>&middot;</span>
                                    <span>{{ $entry->created_at->format('g:i A') }}</span>
                                </p>
                            </div>
                        @empty
                            <p class="text-stone-400 text-xs italic py-2">No status transitions logged yet.</p>
                        @endforelse
                    </div>
                </x-card>

            </div>
        </div>
    </x-container>
</x-app-layout>
