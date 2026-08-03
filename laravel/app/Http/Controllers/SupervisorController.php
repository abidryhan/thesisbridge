<?php

namespace App\Http\Controllers;

use App\Models\Supervisor;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SupervisorController extends Controller
{
    public function create(): View
    {
        $existing = Supervisor::where('user_id', auth()->id())->first();

        if ($existing) {
            return redirect()->route('supervisors.show', $existing)
                ->with('error', 'You already have a supervisor profile.');
        }

        return view('supervisors.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'designation' => 'required|string|max:255',
            'research_areas' => 'required|string',
            'max_capacity' => 'required|integer|min:1|max:10',
        ]);

        $validated['user_id'] = auth()->id();

        $supervisor = Supervisor::create($validated);

        return redirect()->route('supervisors.show', $supervisor)
            ->with('success', 'Profile created successfully.');
    }

    public function show(Supervisor $supervisor): View
    {
        return view('supervisors.show', ['supervisor' => $supervisor]);
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
