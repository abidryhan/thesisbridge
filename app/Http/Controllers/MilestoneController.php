<?php

namespace App\Http\Controllers;

use App\Models\ThesisGroup;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MilestoneController extends Controller
{
    public function create(ThesisGroup $thesis_group): View
    {
        $this->authorizeSupervisor($thesis_group);

        return view('milestones.create', ['group' => $thesis_group]);
    }

    public function store(Request $request, ThesisGroup $thesis_group): RedirectResponse
    {
        $this->authorizeSupervisor($thesis_group);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date',
            'deliverable_type' => 'required|in:Document,Presentation,Code Repository',
        ]);

        $thesis_group->milestones()->create($validated);

        return redirect()->route('thesis-groups.show', $thesis_group)
            ->with('success', 'Milestone created successfully.');
    }

    protected function authorizeSupervisor(ThesisGroup $thesis_group): void
    {
        $supervisor = auth()->user()->supervisor;

        if (!$supervisor || !$thesis_group->isSupervisedBy($supervisor)) {
            abort(403);
        }
    }
}
