<?php

namespace App\Http\Controllers;

use App\Models\CourseProject;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContributionController extends Controller
{
    public function create(CourseProject $course_project): View
    {
        $this->authorizeMember($course_project);

        if ($course_project->contributions()->where('student_id', auth()->user()->student->id)->exists()) {
            abort(403, 'You have already logged your contribution for this project.');
        }

        return view('contributions.create', ['project' => $course_project]);
    }

    public function store(Request $request, CourseProject $course_project): RedirectResponse
    {
        $this->authorizeMember($course_project);

        $validated = $request->validate([
            'description' => 'required|string',
            'percentage' => 'nullable|integer|min:0|max:100',
        ]);

        $course_project->contributions()->create([
            ...$validated,
            'student_id' => auth()->user()->student->id,
        ]);

        return redirect()->route('course-projects.show', $course_project)
            ->with('success', 'Contribution logged successfully.');
    }

    protected function authorizeMember(CourseProject $course_project): void
    {
        $student = auth()->user()->student;
        $isMember = $student && $course_project->students->contains('id', $student->id);

        if (!$isMember) {
            abort(403);
        }
    }
}

