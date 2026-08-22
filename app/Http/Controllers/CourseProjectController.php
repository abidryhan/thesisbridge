<?php

namespace App\Http\Controllers;

use App\Models\CourseProject;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseProjectController extends Controller
{
    private function validated(Request $request): array
{
    return $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'tech_stack' => 'required|string',
        'team_members' => 'nullable|string',
        'student_ids' => 'nullable|array',
        'student_ids.*' => 'exists:students,id',
        'research_tags' => 'nullable|string',
        'term' => 'required|in:Spring,Summer,Fall',
        'year' => 'required|integer|min:2000|max:2100',
        'course_name' => 'required|string|max:255',
        'github_link' => 'nullable|url|max:255',
        'demo_link' => 'nullable|url|max:255',
        'screenshots' => 'nullable|array',
        'screenshots.*' => 'image|max:10240',
        'continued_from_id' => 'nullable|exists:course_projects,id',
    ]);
}


    public function index(): View
    {
        $projects = CourseProject::latest()->get();

        return view('course-projects.index', ['projects' => $projects]);
    }

    public function create(): View
    {
        return view('course-projects.create', [
            'availableStudents' => Student::with('user')->get(),
            'originalProject' => null,
        ]);
    }

    public function claim(CourseProject $course_project): View|RedirectResponse
    {
        if (!$course_project->is_open_for_continuation) {
            return redirect()->route('course-projects.show', $course_project)
                ->with('error', 'This project is not open for continuation.');
        }

        return view('course-projects.create', [
            'availableStudents' => Student::with('user')->get(),
            'originalProject' => $course_project,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['research_tags'] = !empty($validated['research_tags'])
    ? array_values(array_filter(array_map('trim', explode(',', $validated['research_tags']))))
    : [];


        $validated['user_id'] = auth()->id();
        $validated['tech_stack'] = array_map('trim', explode(',', $validated['tech_stack']));
        $validated['team_members'] = !empty($validated['team_members'])
            ? array_values(array_filter(array_map('trim', explode(',', $validated['team_members']))))
            : [];
        unset($validated['screenshots'], $validated['student_ids']);

        if ($request->hasFile('screenshots')) {
            $validated['screenshot_paths'] = collect($request->file('screenshots'))
                ->map(fn ($file) => $file->store('screenshots', 'public'))
                ->all();
        }

        $project = CourseProject::create($validated);

        $currentStudent = auth()->user()->student;
        $memberIds = array_unique(array_merge(
            $request->input('student_ids', []),
            $currentStudent ? [$currentStudent->id] : []
        ));
        $project->students()->sync($memberIds);

        return redirect()->route('course-projects.show', $project)
            ->with('success', 'Project submitted successfully.');
    }

    public function show(CourseProject $course_project): View
    {
        $course_project->load(['students.user', 'continuedFrom', 'continuations']);

        $isOwner = auth()->check() && auth()->id() === $course_project->user_id;

        $currentStudent = auth()->check() ? auth()->user()->student : null;
        $isTeamMember = $currentStudent && $course_project->students->contains('id', $currentStudent->id);

        return view('course-projects.show', [
            'project' => $course_project,
            'isOwner' => $isOwner,
            'canToggle' => $isOwner || $isTeamMember,
        ]);
    }

    public function edit(CourseProject $course_project): View
    {
        $course_project->load('students');

        return view('course-projects.edit', [
            'project' => $course_project,
            'availableStudents' => Student::with('user')->get(),
        ]);
    }

    public function update(Request $request, CourseProject $course_project): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['research_tags'] = !empty($validated['research_tags'])
    ? array_values(array_filter(array_map('trim', explode(',', $validated['research_tags']))))
    : [];

        $validated['tech_stack'] = array_map('trim', explode(',', $validated['tech_stack']));
        $validated['team_members'] = !empty($validated['team_members'])
            ? array_values(array_filter(array_map('trim', explode(',', $validated['team_members']))))
            : [];
        unset($validated['screenshots'], $validated['student_ids']);

        if ($request->hasFile('screenshots')) {
            $newPaths = collect($request->file('screenshots'))
                ->map(fn ($file) => $file->store('screenshots', 'public'))
                ->all();

            $validated['screenshot_paths'] = array_merge($course_project->screenshot_paths ?? [], $newPaths);
        }

        $course_project->update($validated);
        $course_project->students()->sync($request->input('student_ids', []));

        return redirect()->route('course-projects.show', $course_project)
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(CourseProject $course_project): RedirectResponse
    {
        $course_project->delete();

        return redirect()->route('course-projects.index')
            ->with('success', 'Project deleted.');
    }

    public function toggleContinuation(CourseProject $course_project): RedirectResponse
    {
        $this->authorizeTeamMember($course_project);

        $course_project->update([
            'is_open_for_continuation' => !$course_project->is_open_for_continuation,
        ]);

        return redirect()->route('course-projects.show', $course_project)
            ->with('success', $course_project->is_open_for_continuation
                ? 'Project marked open for continuation.'
                : 'Project no longer open for continuation.');
    }

    protected function authorizeTeamMember(CourseProject $course_project): void
    {
        $student = auth()->user()->student;
        $isTeamMember = $student && $course_project->students->contains('id', $student->id);
        $isOriginalSubmitter = auth()->id() === $course_project->user_id;

        if (!$isTeamMember && !$isOriginalSubmitter) {
            abort(403);
        }
    }
}
