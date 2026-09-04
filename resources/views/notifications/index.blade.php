<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-heading font-bold text-2xl sm:text-3xl text-stone-900 leading-tight">
                    Notifications
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">
                    Automated deadline reminders, supervisor escalations, and weekly digests
                </p>
            </div>

            @php
                $unreadTotal = auth()->user()->unreadNotifications->count();
            @endphp

            @if ($unreadTotal > 0)
                <x-badge variant="danger" size="md">
                    {{ $unreadTotal }} Unread
                </x-badge>
            @else
                <x-badge variant="neutral" size="sm">
                    All caught up
                </x-badge>
            @endif
        </div>
    </x-slot>

    <x-container size="narrow">
        <div class="space-y-4">
            @forelse ($notifications as $notification)
                @php
                    $isUnread = is_null($notification->read_at);
                    $isDigest = ($notification->data['type'] ?? '') === 'weekly_supervisor_digest';
                    $isEscalation = ($notification->data['type'] ?? '') === 'milestone_overdue_escalation';
                @endphp

                <div class="rounded-xl border transition-all duration-150 p-5 {{ $isUnread ? 'bg-white border-stone-200/80 shadow-xs border-l-4 border-l-blue-600' : 'bg-stone-50/60 border-stone-200/60 text-stone-600' }}">

                    @if ($isDigest)
                        <!-- Feature 20: Weekly Supervisor Digest Card -->
                        <div>
                            <!-- Digest Header -->
                            <div class="flex items-start justify-between gap-3 mb-3 pb-3 border-b border-stone-100">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-heading font-bold text-base text-stone-900">
                                            Weekly Supervisor Digest
                                        </h4>
                                        <span class="text-[11px] text-stone-400 font-mono">
                                            {{ $notification->created_at->format('l, M d, Y') }}
                                        </span>
                                    </div>
                                </div>

                                <x-badge variant="brand" size="sm">Monday Digest</x-badge>
                            </div>

                            <!-- Digest Body -->
                            @if (!empty($notification->data['is_all_clear']))
                                <div class="p-3.5 rounded-lg bg-emerald-50/80 border border-emerald-200 text-emerald-900 text-xs flex items-center gap-2.5">
                                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>All <strong>{{ $notification->data['active_group_count'] }}</strong> active thesis group(s) look healthy with no overdue tasks this week.</span>
                                </div>
                            @else
                                <p class="text-xs text-stone-600 mb-3">
                                    Workload summary across <strong>{{ $notification->data['active_group_count'] }}</strong> active group(s):
                                </p>

                                <div class="space-y-2.5 text-xs">
                                    <!-- Overdue Milestones Sub-card -->
                                    @if (!empty($notification->data['overdue_milestones']))
                                        <div class="p-3 rounded-lg bg-rose-50/80 border border-rose-200 text-rose-900">
                                            <span class="font-semibold uppercase tracking-wider text-[10px] text-rose-800 block mb-1">
                                                ⚠ Overdue Milestones
                                            </span>
                                            <ul class="space-y-1 pl-1">
                                                @foreach ($notification->data['overdue_milestones'] as $item)
                                                    <li class="flex items-baseline justify-between gap-2">
                                                        <span><strong>{{ $item['group_name'] }}</strong> &mdash; {{ $item['milestone_title'] }}</span>
                                                        <span class="font-mono text-[11px] text-rose-700 shrink-0">due {{ $item['deadline'] }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <!-- Upcoming Milestones (7 Days) -->
                                    @if (!empty($notification->data['upcoming_milestones']))
                                        <div class="p-3 rounded-lg bg-amber-50/80 border border-amber-200 text-amber-900">
                                            <span class="font-semibold uppercase tracking-wider text-[10px] text-amber-800 block mb-1">
                                                Due within 7 days
                                            </span>
                                            <ul class="space-y-1 pl-1">
                                                @foreach ($notification->data['upcoming_milestones'] as $item)
                                                    <li class="flex items-baseline justify-between gap-2">
                                                        <span><strong>{{ $item['group_name'] }}</strong> &mdash; {{ $item['milestone_title'] }}</span>
                                                        <span class="font-mono text-[11px] text-amber-800 shrink-0">due {{ $item['deadline'] }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <!-- Ghost Flagged Groups -->
                                    @if (!empty($notification->data['ghost_groups']))
                                        <div class="p-3 rounded-lg bg-stone-100 border border-stone-200 text-stone-800">
                                            <span class="font-semibold uppercase tracking-wider text-[10px] text-stone-600 block mb-1">
                                                Silent Groups Detected
                                            </span>
                                            <ul class="space-y-1 pl-1">
                                                @foreach ($notification->data['ghost_groups'] as $item)
                                                    <li class="flex items-baseline justify-between gap-2">
                                                        <span><strong>{{ $item['group_name'] }}</strong></span>
                                                        <span class="text-rose-700 font-medium text-[11px]">no student activity in {{ $item['days_since_activity'] }} days</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Digest Footer Actions -->
                            <div class="flex items-center justify-between gap-2 mt-4 pt-3 border-t border-stone-100 text-xs">
                                <span class="text-stone-400 text-[11px]">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>

                                @if ($isUnread)
                                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center text-xs font-semibold text-blue-700 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg border border-blue-200 transition">
                                            Mark as Read
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                    @else
                        <!-- Feature 19: Single Milestone Notification (Reminder or Escalation) -->
                        <div class="flex items-start gap-3.5">
                            <!-- Type Icon -->
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 mt-0.5 {{ $isEscalation ? 'bg-rose-50 text-rose-700 border border-rose-200/60' : 'bg-amber-50 text-amber-700 border border-amber-200/60' }}">
                                @if ($isEscalation)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="flex-1">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="text-[11px] font-semibold uppercase tracking-wider {{ $isEscalation ? 'text-rose-700' : 'text-amber-800' }}">
                                        {{ $isEscalation ? 'Overdue Escalation' : 'Deadline Reminder' }}
                                    </span>
                                    <span class="text-stone-400 text-[11px] font-mono">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <p class="text-sm text-stone-800 mt-1 leading-relaxed font-medium">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>

                                <div class="flex items-center justify-end gap-3 mt-3 pt-2.5 border-t border-stone-100">
                                    @if ($isUnread)
                                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                type="submit"
                                                class="inline-flex items-center text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 px-3.5 py-1.5 rounded-lg shadow-xs transition"
                                            >
                                                Mark as read &amp; view &rarr;
                                            </button>
                                        </form>
                                    @elseif (!empty($notification->data['thesis_group_id']))
                                        <a
                                            href="{{ route('thesis-groups.show', $notification->data['thesis_group_id']) }}"
                                            class="inline-flex items-center text-xs font-semibold text-blue-700 hover:text-blue-900"
                                        >
                                            View Group &rarr;
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            @empty
                <x-card class="text-center py-12">
                    <div class="w-12 h-12 rounded-full bg-stone-100 mx-auto flex items-center justify-center text-stone-400 mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <h4 class="font-heading font-semibold text-stone-800 text-base">All caught up</h4>
                    <p class="text-xs text-stone-500 mt-1">You have no active notifications or deadline alerts in your inbox.</p>
                </x-card>
            @endforelse
        </div>
    </x-container>
</x-app-layout>
