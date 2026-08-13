<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\ThesisGroup;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    public function index(ThesisGroup $thesis_group, Milestone $milestone): View
    {
        $this->checkNesting($thesis_group, $milestone);

        if (!$thesis_group->isAccessibleBy(auth()->user())) {
            abort(403);
        }

        $feedbackEntries = $milestone->feedback()
            ->with('document', 'supervisor.user')
            ->orderByDesc('created_at')
            ->get();

        return view('feedback.index', [
            'group' => $thesis_group,
            'milestone' => $milestone,
            'feedbackEntries' => $feedbackEntries,
        ]);
    }

    public function create(ThesisGroup $thesis_group, Milestone $milestone): View
    {
        $this->checkNesting($thesis_group, $milestone);
        $this->authorizeSupervisor($thesis_group);

        $documents = $milestone->documents()->orderByDesc('version_number')->get();

        return view('feedback.create', [
            'group' => $thesis_group,
            'milestone' => $milestone,
            'documents' => $documents,
        ]);
    }

    public function store(Request $request, ThesisGroup $thesis_group, Milestone $milestone): RedirectResponse
    {
        $this->checkNesting($thesis_group, $milestone);
        $this->authorizeSupervisor($thesis_group);

        $validated = $request->validate([
            'document_id' => 'nullable|exists:documents,id',
            'content' => 'required|string',
            'severity' => 'required|in:minor,needs-revision,blocking',
        ]);

        if (!empty($validated['document_id'])) {
            $belongsToMilestone = $milestone->documents()->where('id', $validated['document_id'])->exists();

            if (!$belongsToMilestone) {
                return back()
                    ->withErrors(['document_id' => 'Selected document does not belong to this milestone.'])
                    ->withInput();
            }
        }

        $milestone->feedback()->create([
            ...$validated,
            'supervisor_id' => auth()->user()->supervisor->id,
        ]);

        return redirect()->route('thesis-groups.milestones.feedback.index', [$thesis_group, $milestone])
            ->with('success', 'Feedback submitted successfully.');
    }

    protected function checkNesting(ThesisGroup $thesis_group, Milestone $milestone): void
    {
        if ($milestone->thesis_group_id !== $thesis_group->id) {
            abort(404);
        }
    }

    protected function authorizeSupervisor(ThesisGroup $thesis_group): void
    {
        $supervisor = auth()->user()->supervisor;

        if (!$supervisor || !$thesis_group->isSupervisedBy($supervisor)) {
            abort(403);
        }
    }
}
