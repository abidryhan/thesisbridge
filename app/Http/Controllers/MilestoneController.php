<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\ThesisGroup;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MilestoneController extends Controller
{
    /**
     * Show the milestone creation form.
     */
    public function create(ThesisGroup $thesis_group): View
    {
        $this->authorizeSupervisor($thesis_group);

        return view('milestones.create', [
            'group' => $thesis_group,
        ]);
    }

    /**
     * Store a new milestone.
     */
    public function store(
        Request $request,
        ThesisGroup $thesis_group
    ): RedirectResponse {
        $this->authorizeSupervisor($thesis_group);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date',
            'deliverable_type' => 'required|in:Document,Presentation,Code Repository',
        ]);

        $thesis_group->milestones()->create($validated);

        return redirect()
            ->route('thesis-groups.show', $thesis_group)
            ->with('success', 'Milestone created successfully.');
    }

    /**
     * Mark a milestone complete/incomplete.
     */
    public function toggleComplete(
        ThesisGroup $thesis_group,
        Milestone $milestone
    ): RedirectResponse {
        $this->checkNesting($thesis_group, $milestone);
        $this->authorizeSupervisor($thesis_group);

        $milestone->update([
            'completed_at' => $milestone->completed_at
                ? null
                : now(),
        ]);

        return redirect()
            ->route('thesis-groups.show', $thesis_group)
            ->with(
                'success',
                $milestone->completed_at
                    ? 'Milestone marked complete.'
                    : 'Milestone marked incomplete.'
            );
    }

    /**
     * Make sure the milestone belongs to this thesis group.
     */
    protected function checkNesting(
        ThesisGroup $thesis_group,
        Milestone $milestone
    ): void {
        if ($milestone->thesis_group_id !== $thesis_group->id) {
            abort(404);
        }
    }

    /**
     * Only the supervisor of the group can manage milestones.
     */
    protected function authorizeSupervisor(
        ThesisGroup $thesis_group
    ): void {
        $supervisor = auth()->user()->supervisor;

        if (
            !$supervisor ||
            !$thesis_group->isSupervisedBy($supervisor)
        ) {
            abort(403);
        }
    }
}