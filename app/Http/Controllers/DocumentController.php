<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Milestone;
use App\Models\ThesisGroup;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(ThesisGroup $thesis_group, Milestone $milestone): View
    {
        $this->authorizeAccess($thesis_group, $milestone);

        $documents = $milestone->documents()
            ->with('user.student', 'user.supervisor')
            ->orderByDesc('version_number')
            ->get();

        return view('documents.index', [
            'group' => $thesis_group,
            'milestone' => $milestone,
            'documents' => $documents,
        ]);
    }

    public function create(ThesisGroup $thesis_group, Milestone $milestone): View
    {
        $this->authorizeAccess($thesis_group, $milestone);

        return view('documents.create', [
            'group' => $thesis_group,
            'milestone' => $milestone,
        ]);
    }

    public function store(Request $request, ThesisGroup $thesis_group, Milestone $milestone): RedirectResponse
    {
        $this->authorizeAccess($thesis_group, $milestone);

        $validated = $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,txt,md,jpg,jpeg,png|max:10240',
        ]);

        $file = $validated['document'];
        $nextVersion = $milestone->documents()->count() + 1;

        $milestone->documents()->create([
            'user_id' => auth()->id(),
            'file_path' => $file->store('documents', 'public'),
            'original_filename' => $file->getClientOriginalName(),
            'version_number' => $nextVersion,
        ]);

        return redirect()->route('thesis-groups.milestones.documents.index', [$thesis_group, $milestone])
            ->with('success', 'Document uploaded as version ' . $nextVersion . '.');
    }

    protected function authorizeAccess(ThesisGroup $thesis_group, Milestone $milestone): void
    {
        if ($milestone->thesis_group_id !== $thesis_group->id) {
            abort(404);
        }

        $user = auth()->user();
        $supervisor = $user->supervisor;
        $student = $user->student;

        $isSupervisor = $supervisor && $thesis_group->isSupervisedBy($supervisor);
        $isMember = $student && $thesis_group->students->contains('id', $student->id);

        if (!$isSupervisor && !$isMember) {
            abort(403);
        }
    }
}
