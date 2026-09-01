<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Notifications
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        @forelse ($notifications as $notification)
            <div class="border rounded p-4 mb-3 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}">

                @if ($notification->data['type'] === 'weekly_supervisor_digest')
                    <p class="text-sm font-medium mb-2">
                        Weekly Supervisor Digest
                    </p>

                    @if ($notification->data['is_all_clear'])
                        <p class="text-sm text-green-700">
                            ✓ All {{ $notification->data['active_group_count'] }} active group(s) look healthy this week.
                        </p>
                    @else
                        <p class="text-sm text-gray-600 mb-2">
                            Across {{ $notification->data['active_group_count'] }} active group(s):
                        </p>

                        @if (!empty($notification->data['overdue_milestones']))
                            <div class="mb-2">
                                <span class="text-xs font-medium text-red-700">
                                    Overdue:
                                </span>

                                <ul class="text-xs text-gray-600 list-disc list-inside">
                                    @foreach ($notification->data['overdue_milestones'] as $item)
                                        <li>
                                            {{ $item['group_name'] }} —
                                            {{ $item['milestone_title'] }}
                                            (was due {{ $item['deadline'] }})
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (!empty($notification->data['upcoming_milestones']))
                            <div class="mb-2">
                                <span class="text-xs font-medium text-yellow-700">
                                    Due within 7 days:
                                </span>

                                <ul class="text-xs text-gray-600 list-disc list-inside">
                                    @foreach ($notification->data['upcoming_milestones'] as $item)
                                        <li>
                                            {{ $item['group_name'] }} —
                                            {{ $item['milestone_title'] }}
                                            (due {{ $item['deadline'] }})
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (!empty($notification->data['ghost_groups']))
                            <div class="mb-2">
                                <span class="text-xs font-medium text-gray-700">
                                    Ghost-flagged:
                                </span>

                                <ul class="text-xs text-gray-600 list-disc list-inside">
                                    @foreach ($notification->data['ghost_groups'] as $item)
                                        <li>
                                            {{ $item['group_name'] }} —
                                            no student activity in
                                            {{ $item['days_since_activity'] }} days
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endif

                    <div class="flex justify-between items-center mt-2">
                        <span class="text-xs text-gray-400">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>

                        @if (!$notification->read_at)
                            <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                @csrf
                                @method('PATCH')

                                <button type="submit" class="text-blue-600 text-xs underline">
                                    Mark as read
                                </button>
                            </form>
                        @endif
                    </div>

                @else
                    <p class="text-sm">
                        {{ $notification->data['message'] }}
                    </p>

                    <div class="flex justify-between items-center mt-2">
                        <span class="text-xs text-gray-400">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>

                        @if (!$notification->read_at)
                            <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                @csrf
                                @method('PATCH')

                                <button type="submit" class="text-blue-600 text-xs underline">
                                    Mark as read &amp; view
                                </button>
                            </form>
                        @else
                            <a href="{{ route('thesis-groups.show', $notification->data['thesis_group_id']) }}"
                               class="text-blue-600 text-xs underline">
                                View
                            </a>
                        @endif
                    </div>
                @endif

            </div>
        @empty
            <p class="text-gray-500">
                No notifications yet.
            </p>
        @endforelse
    </div>
</x-app-layout>