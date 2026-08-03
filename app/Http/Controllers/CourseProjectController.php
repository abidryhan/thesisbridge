<?php

namespace App\Http\Controllers;

use App\Models\CourseProject;
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
            'team_members' => 'required|string',
            'term' => 'required|in:Spring,Summer,Fall',
            'year' => 'required|integer|min:2000|max:2100',
            'course_name' => 'required|string|max:255',
            'github_link' => 'nullable|url|max:255',
            'demo_link' => 'nullable|url|max:255',
            'screenshots' => 'nullable|array',
            'screenshots.*' => 'image|max:10240',
        ]);
    }

    public function index(): View
    {
        $projects = CourseProject::latest()->get();

        return view('course-projects.index', ['projects' => $projects]);
    }

    public function create(): View
    {
        return view('course-projects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $validated['user_id'] = auth()->id();
        $validated['tech_stack'] = array_map('trim', explode(',', $validated['tech_stack']));
        $validated['team_members'] = array_map('trim', explode(',', $validated['team_members']));
        unset($validated['screenshots']);

        if ($request->hasFile('screenshots')) {
            $validated['screenshot_paths'] = collect($request->file('screenshots'))
                ->map(fn ($file) => $file->store('screenshots', 'public'))
                ->all();
        }

        $project = CourseProject::create($validated);

        return redirect()->route('course-projects.show', $project)
            ->with('success', 'Project submitted successfully.');
    }

    public function show(CourseProject $course_project): View
    {
        $isOwner = auth()->check() && auth()->id() === $course_project->user_id;

        return view('course-projects.show', [
            'project' => $course_project,
            'isOwner' => $isOwner,
        ]);
    }

    public function edit(CourseProject $course_project): View
    {
        return view('course-projects.edit', ['project' => $course_project]);
    }

    public function update(Request $request, CourseProject $course_project): RedirectResponse
    {
        $validated = $this->validated($request);

        $validated['tech_stack'] = array_map('trim', explode(',', $validated['tech_stack']));
        $validated['team_members'] = array_map('trim', explode(',', $validated['team_members']));
        unset($validated['screenshots']);

        if ($request->hasFile('screenshots')) {
            $newPaths = collect($request->file('screenshots'))
                ->map(fn ($file) => $file->store('screenshots', 'public'))
                ->all();

            $validated['screenshot_paths'] = array_merge($course_project->screenshot_paths ?? [], $newPaths);
        }

        $course_project->update($validated);

        return redirect()->route('course-projects.show', $course_project)
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(CourseProject $course_project): RedirectResponse
    {
        $course_project->delete();

        return redirect()->route('course-projects.index')
            ->with('success', 'Project deleted.');
    }
}
