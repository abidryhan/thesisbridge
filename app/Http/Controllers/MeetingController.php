<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\ThesisGroup;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MeetingController extends Controller
{
    public function index(ThesisGroup $thesis_group): View
    {
        $this->authorizeAccess($thesis_group);

        $meetings = $thesis_group->meetings()
            ->with('loggedBy', 'attendees', 'milestone')
            ->orderByDesc('meeting_date')
            ->get();

        return view('meetings.index', [
            'group' => $thesis_group,
            'meetings' => $meetings,
        ]);
    }

    public function create(ThesisGroup $thesis_group): View
    {
        $this->authorizeAccess($thesis_group);

        return view('meetings.create', [
            'group' => $thesis_group,
            'eligibleAttendees' => $this->eligibleAttendees($thesis_group),
        ]);
    }

    public function store(Request $request, ThesisGroup $thesis_group): RedirectResponse
    {
        $this->authorizeAccess($thesis_group);

        $eligibleIds = $this->eligibleAttendees($thesis_group)->pluck('id');

        $validated = $request->validate([
            'milestone_id' => 'nullable|exists:milestones,id',
            'meeting_date' => 'required|date',
            'agenda' => 'required|string',
            'outcomes' => 'required|string',
            'next_action_items' => 'required|string',
            'attendee_ids' => 'required|array|min:1',
            'attendee_ids.*' => 'exists:users,id',
        ]);

        $invalidAttendees = collect($validated['attendee_ids'])->diff($eligibleIds);

        if ($invalidAttendees->isNotEmpty()) {
            return back()
                ->withErrors(['attendee_ids' => 'One or more selected attendees are not part of this group.'])
                ->withInput();
        }

        $meeting = $thesis_group->meetings()->create([
            ...$validated,
            'logged_by_user_id' => auth()->id(),
        ]);

        $meeting->attendees()->attach($validated['attendee_ids']);

        return redirect()->route('thesis-groups.meetings.index', $thesis_group)
            ->with('success', 'Meeting logged successfully.');
    }

    protected function authorizeAccess(ThesisGroup $thesis_group): void
    {
        if (!$thesis_group->isAccessibleBy(auth()->user())) {
            abort(403);
        }
    }

    protected function eligibleAttendees(ThesisGroup $thesis_group)
    {
        $studentUsers = $thesis_group->students->pluck('user')->filter();
        $supervisorUser = $thesis_group->supervisor?->user;

        return $studentUsers
            ->when($supervisorUser, fn ($collection) => $collection->push($supervisorUser))
            ->values();
    }
}