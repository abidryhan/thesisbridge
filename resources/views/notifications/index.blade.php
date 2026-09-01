<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Notifications
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        @forelse ($notifications as $notification)
            <div class="border rounded p-4 mb-3 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}">
                <p class="text-sm">{{ $notification->data['message'] }}</p>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                    @if (!$notification->read_at)
                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-blue-600 text-xs underline">Mark as read &amp; view</button>
                        </form>
                    @else
                        <a href="{{ route('thesis-groups.show', $notification->data['thesis_group_id']) }}" class="text-blue-600 text-xs underline">
                            View
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-gray-500">No notifications yet.</p>
        @endforelse
    </div>
</x-app-layout>
