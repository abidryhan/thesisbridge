<?php

namespace App\Http\Controllers;

use App\Models\ThesisGroup;
use App\Models\Student;
use App\Models\Supervisor;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ThesisGroupController extends Controller
{
    public function index(): View
    {
        $groups = ThesisGroup::with(['students', 'supervisor'])->latest()->get();

        return view('thesis-groups.index', ['groups' => $groups]);
    }

    public function create(): RedirectResponse|View
    {
        $currentStudent = auth()->user()->student;

        if (!$currentStudent) {
            return redirect()->route('students.create')
                ->with('error', 'Create your student profile before forming a thesis group.');
        }

        if ($currentStudent->thesisGroups()->exists()) {
            $existingGroup = $currentStudent->thesisGroups()->first();
            return redirect()->route('thesis-groups.show', $existingGroup)
                ->with('error', 'You are already in a thesis group.');
        }

        $availableStudents = Student::availableForGroup()
            ->where('id', '!=', $currentStudent->id)
            ->get();

        $supervisors = Supervisor::all();

        return view('thesis-groups.create', [
            'availableStudents' => $availableStudents,
            'supervisors' => $supervisors,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $currentStudent = auth()->user()->student;

        if (!$currentStudent || $currentStudent->thesisGroups()->exists()) {
            return redirect()->route('thesis-groups.index')
                ->with('error', 'You must have a student profile and not already be in a group.');
        }

        $validated = $request->validate([
            'group_name' => 'required|string|max:255',
            'supervisor_id' => 'nullable|exists:supervisors,id',
            'student_ids' => 'required|array|min:1|max:3',
            'student_ids.*' => 'exists:students,id',
        ]);

        $memberIds = array_unique(array_merge($validated['student_ids'], [$currentStudent->id]));

        if (count($memberIds) < 2 || count($memberIds) > 4) {
            return back()->withErrors(['student_ids' => 'A group must have between 2 and 4 members total.'])->withInput();
        }

        $group = ThesisGroup::create([
            'group_name' => $validated['group_name'],
            'supervisor_id' => $validated['supervisor_id'] ?? null,
        ]);

        $group->students()->attach($memberIds);

        return redirect()->route('thesis-groups.show', $group)
            ->with('success', 'Thesis group created successfully.');
    }

    public function show(ThesisGroup $thesis_group): View
{
    $thesis_group->load(['students.user', 'supervisor', 'milestones' => function ($query) {
        $query->withCount(['documents', 'feedback']);
}]);


    $currentSupervisor = auth()->user()->supervisor;
    $isSupervisor = $currentSupervisor && $thesis_group->isSupervisedBy($currentSupervisor);

    return view('thesis-groups.show', [
        'group' => $thesis_group,
        'isSupervisor' => $isSupervisor,
    ]);
}


    public function edit(ThesisGroup $thesis_group): View
    {
        $currentMemberIds = $thesis_group->students->pluck('id')->all();

        $selectableStudents = Student::where(function ($query) use ($currentMemberIds) {
            $query->whereDoesntHave('thesisGroups')->orWhereIn('id', $currentMemberIds);
        })->get();

        $supervisors = Supervisor::all();

        return view('thesis-groups.edit', [
            'group' => $thesis_group,
            'selectableStudents' => $selectableStudents,
            'currentMemberIds' => $currentMemberIds,
            'supervisors' => $supervisors,
        ]);
    }

    public function update(Request $request, ThesisGroup $thesis_group): RedirectResponse
    {
        $validated = $request->validate([
            'group_name' => 'required|string|max:255',
            'supervisor_id' => 'nullable|exists:supervisors,id',
            'student_ids' => 'required|array|min:2|max:4',
            'student_ids.*' => 'exists:students,id',
        ]);

        $thesis_group->update([
            'group_name' => $validated['group_name'],
            'supervisor_id' => $validated['supervisor_id'] ?? null,
        ]);

        $thesis_group->students()->sync($validated['student_ids']);

        return redirect()->route('thesis-groups.show', $thesis_group)
            ->with('success', 'Thesis group updated successfully.');
    }

    public function destroy(ThesisGroup $thesis_group): RedirectResponse
    {
        $thesis_group->delete();

        return redirect()->route('thesis-groups.index')
            ->with('success', 'Thesis group deleted.');
    }
}
