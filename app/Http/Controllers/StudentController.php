<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function create(): View|RedirectResponse
    {
        $existing = Student::where('user_id', auth()->id())->first();

        if ($existing) {
            return redirect()->route('students.show', $existing)
                ->with('error', 'You already have a student profile.');
        }

        return view('students.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'department' => 'nullable|string|max:255',
            'batch' => 'nullable|string|max:255',
            'academic_year' => 'nullable|integer|min:2000|max:2100',
            'research_interests' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        $student = Student::create($validated);

        return redirect()->route('students.show', $student)
            ->with('success', 'Profile created successfully.');
    }

    public function show(Student $student): View
    {
        return view('students.show', ['student' => $student]);
    }

    public function edit(Student $student): View
    {
        return view('students.edit', ['student' => $student]);
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'department' => 'nullable|string|max:255',
            'batch' => 'nullable|string|max:255',
            'academic_year' => 'nullable|integer|min:2000|max:2100',
            'research_interests' => 'nullable|string',
        ]);

        $student->update($validated);

        return redirect()->route('students.show', $student)
            ->with('success', 'Profile updated successfully.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        return redirect()->route('students.create')
            ->with('success', 'Profile deleted.');
    }
}
