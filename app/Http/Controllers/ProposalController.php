<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProposalRequest;
use App\Models\Proposal;
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

        $validated = $request->validated();
        $validated['research_tags'] = !empty($validated['research_tags'])
            ? array_values(array_filter(array_map('trim', explode(',', $validated['research_tags']))))
            : [];

        $proposal = Proposal::create([
            ...$validated,
            'thesis_group_id' => $group->id,
        ]);

        return redirect()->route('proposals.show', $proposal)
            ->with('success', 'Proposal submitted successfully.');
    }


    public function show(Proposal $proposal): View
    {
        $proposal->load('thesisGroup.students.user', 'thesisGroup.supervisor');

        $currentStudent = auth()->user()->student;
        $isMember = $currentStudent && $proposal->thesisGroup->students->contains('id', $currentStudent->id);

        if (!$isMember) {
            abort(403);
        }

        return view('proposals.show', ['proposal' => $proposal]);
    }
}
