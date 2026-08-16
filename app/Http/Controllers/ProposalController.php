<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProposalRequest;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProposalController extends Controller
{
    public function create(): RedirectResponse|View
    {
        $student = auth()->user()->student;
        $group = $student?->thesisGroups->first();

        if (!$group) {
            return redirect()->route('thesis-groups.create')
                ->with('error', 'You need to be in a thesis group before submitting a proposal.');
        }

        if ($group->proposal()->exists()) {
            return redirect()->route('proposals.show', $group->proposal)
                ->with('error', 'Your group has already submitted a proposal.');
        }

        return view('proposals.create');
    }

    public function store(StoreProposalRequest $request): RedirectResponse
    {
        $group = auth()->user()->student->thesisGroups->first();

        $proposal = Proposal::create([
            ...$request->validated(),
            'thesis_group_id' => $group->id,
        ]);

        return redirect()->route('proposals.show', $proposal)
            ->with('success', 'Proposal submitted successfully.');
    }

    public function show(Proposal $proposal): View
    {
        $proposal->load(
            'thesisGroup.students.user',
            'thesisGroup.supervisor.user',
            'statusHistory.changedBy'
        );

        $currentStudent = auth()->user()->student;
        $currentSupervisor = auth()->user()->supervisor;

        $isMember = $currentStudent
            && $proposal->thesisGroup->students->contains('id', $currentStudent->id);

        $isAssignedSupervisor = $currentSupervisor
            && $proposal->thesisGroup->isSupervisedBy($currentSupervisor);

        if (!$isMember && !$isAssignedSupervisor) {
            abort(403);
        }

        return view('proposals.show', [
            'proposal' => $proposal,
            'isMember' => $isMember,
            'isAssignedSupervisor' => $isAssignedSupervisor,
        ]);
    }

    public function edit(Proposal $proposal): View
    {
        $this->authorizeGroupMember($proposal);
        $this->authorizeEditable($proposal);

        return view('proposals.edit', ['proposal' => $proposal]);
    }

    public function update(Request $request, Proposal $proposal): RedirectResponse
    {
        $this->authorizeGroupMember($proposal);
        $this->authorizeEditable($proposal);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'abstract' => 'required|string',
            'objectives' => 'required|string',
            'methodology' => 'required|string',
        ]);

        $proposal->update($validated);

        return redirect()->route('proposals.show', $proposal)
            ->with('success', 'Changes saved. Click "Resubmit for Review" when ready.');
    }

    public function resubmit(Proposal $proposal): RedirectResponse
    {
        $this->authorizeGroupMember($proposal);
        $this->authorizeEditable($proposal);

        try {
            $proposal->transitionTo('submitted', null, auth()->user());
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('proposals.show', $proposal)
            ->with('success', 'Proposal resubmitted for review.');
    }

    public function startReview(Proposal $proposal): RedirectResponse
    {
        $this->authorizeSupervisor($proposal);

        try {
            $proposal->transitionTo(
                'under_review',
                request('reason'),
                auth()->user()
            );
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('proposals.show', $proposal)
            ->with('success', 'Proposal moved to under review.');
    }

    public function approve(Proposal $proposal): RedirectResponse
    {
        $this->authorizeSupervisor($proposal);

        try {
            $proposal->transitionTo(
                'approved',
                request('reason'),
                auth()->user()
            );
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('proposals.show', $proposal)
            ->with('success', 'Proposal approved.');
    }

    public function requestRevision(
        Request $request,
        Proposal $proposal
    ): RedirectResponse {
        $this->authorizeSupervisor($proposal);

        $validated = $request->validate([
            'reason' => 'required|string',
        ]);

        try {
            $proposal->transitionTo(
                'revision_required',
                $validated['reason'],
                auth()->user()
            );
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('proposals.show', $proposal)
            ->with('success', 'Revision requested.');
    }

    public function reject(
        Request $request,
        Proposal $proposal
    ): RedirectResponse {
        $this->authorizeSupervisor($proposal);

        $validated = $request->validate([
            'reason' => 'required|string',
        ]);

        try {
            $proposal->transitionTo(
                'rejected',
                $validated['reason'],
                auth()->user()
            );
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('proposals.show', $proposal)
            ->with('success', 'Proposal rejected.');
    }

    protected function authorizeGroupMember(Proposal $proposal): void
    {
        $student = auth()->user()->student;

        $inGroup = $student
            && $proposal->thesisGroup->students->contains('id', $student->id);

        if (!$inGroup) {
            abort(403);
        }
    }

    protected function authorizeEditable(Proposal $proposal): void
    {
        if (!in_array(
            $proposal->status,
            ['revision_required', 'rejected'],
            true
        )) {
            abort(403);
        }
    }

    protected function authorizeSupervisor(Proposal $proposal): void
    {
        $supervisor = auth()->user()->supervisor;

        if (
            !$supervisor
            || !$proposal->thesisGroup->isSupervisedBy($supervisor)
        ) {
            abort(403);
        }
    }
}