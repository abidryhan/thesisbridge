<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Log a Meeting — {{ $group->group_name }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4">
        <form method="POST" action="{{ route('thesis-groups.meetings.store', $group) }}">
            @csrf

            <div class="mb-4">
                <label for="meeting_date" class="block font-medium mb-1">Meeting Date</label>
                <input type="date" name="meeting_date" id="meeting_date" value="{{ old('meeting_date') }}"
                    class="w-full border rounded px-3 py-2">
                @error('meeting_date')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            @if ($group->milestones->isNotEmpty())
                <div class="mb-4">
                    <label for="milestone_id" class="block font-medium mb-1">Related Milestone (optional)</label>
                    <select name="milestone_id" id="milestone_id" class="w-full border rounded px-3 py-2">
                        <option value="">General check-in — not tied to a milestone</option>
                        @foreach ($group->milestones as $milestone)
                            <option value="{{ $milestone->id }}" @selected(old('milestone_id') == $milestone->id)>
                                {{ $milestone->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('milestone_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            @endif

            <div class="mb-4">
                <span class="block font-medium mb-1">Attendees</span>
                @error('attendee_ids')<p class="text-red-600 text-sm mt-1 mb-2">{{ $message }}</p>@enderror
                <div class="border rounded p-3 space-y-2">
                    @foreach ($eligibleAttendees as $attendee)
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="attendee_ids[]" value="{{ $attendee->id }}"
                                @checked(collect(old('attendee_ids'))->contains($attendee->id))>
                            {{ $attendee->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label for="agenda" class="block font-medium mb-1">Agenda</label>
                <textarea name="agenda" id="agenda" rows="3"
                    class="w-full border rounded px-3 py-2">{{ old('agenda') }}</textarea>
                @error('agenda')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label for="outcomes" class="block font-medium mb-1">Outcomes</label>
                <textarea name="outcomes" id="outcomes" rows="3"
                    class="w-full border rounded px-3 py-2">{{ old('outcomes') }}</textarea>
                @error('outcomes')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label for="next_action_items" class="block font-medium mb-1">Next Action Items</label>
                <textarea name="next_action_items" id="next_action_items" rows="3"
                    class="w-full border rounded px-3 py-2">{{ old('next_action_items') }}</textarea>
                @error('next_action_items')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Log Meeting</button>
                <a href="{{ route('thesis-groups.meetings.index', $group) }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>