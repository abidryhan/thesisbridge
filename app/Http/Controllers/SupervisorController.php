<?php

namespace App\Http\Controllers;

use App\Models\Supervisor;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SupervisorController extends Controller
{
    public function create(): View|RedirectResponse
    {
        $existing = Supervisor::where('user_id', auth()->id())->first();

        if ($existing) {
            return redirect()->route('supervisors.show', $existing)
                ->with('error', 'You already have a supervisor profile.');
        }

        if ($currentStudent = auth()->user()->student) {
            return redirect()->route('students.show', $currentStudent)
                ->with('error', 'Your account is already registered as a Student. An account can only be a Student or a Supervisor, not both.');
        }

        return view('supervisors.create');


    }

    public function store(Request $request): RedirectResponse
    {

        if ($currentStudent = auth()->user()->student) {
                    return redirect()->route('students.show', $currentStudent)
                        ->with('error', 'Your account is already registered as a Student. An account can only be a Student or a Supervisor, not both.');
        }
        $validated = $request->validate([
            'designation' => 'required|string|max:255',
            'research_areas' => 'required|string',
            'max_capacity' => 'required|integer|min:1|max:10',
        ]);

        $validated['research_areas'] = array_values(array_filter(
            array_map('trim', explode(',', $validated['research_areas']))
        ));
        $validated['user_id'] = auth()->id();

        $supervisor = Supervisor::create($validated);

        return redirect()->route('supervisors.show', $supervisor)
            ->with('success', 'Profile created successfully.');
    }

    public function show(Supervisor $supervisor): View
    {
        return view('supervisors.show', [
            'supervisor' => $supervisor,
            'averageCompletionTime' => $supervisor->averageCompletionTimeInDays(),
            'adherenceRate' => $supervisor->milestoneAdherenceRate(),
            'totalSupervised' => $supervisor->totalSupervised(),
            'researchAreasCovered' => $supervisor->researchAreasCovered(),
        ]);
    }

    public function edit(Supervisor $supervisor): View
    {
        return view('supervisors.edit', ['supervisor' => $supervisor]);
    }

    public function update(Request $request, Supervisor $supervisor): RedirectResponse
    {
        $validated = $request->validate([
            'designation' => 'required|string|max:255',
            'research_areas' => 'required|string',
            'max_capacity' => 'required|integer|min:1|max:10',
        ]);

        $validated['research_areas'] = array_values(array_filter(
            array_map('trim', explode(',', $validated['research_areas']))
        ));

        $supervisor->update($validated);

        return redirect()->route('supervisors.show', $supervisor)
            ->with('success', 'Profile updated successfully.');
    }

    public function destroy(Supervisor $supervisor): RedirectResponse
    {
        $supervisor->delete();

        return redirect()->route('supervisors.create')
            ->with('success', 'Profile deleted.');
    }
}
