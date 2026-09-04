<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $student = $user->student;
        $supervisor = $user->supervisor;

        $thesisGroup = $student ? $student->thesisGroups()->first() : null;

        $ghostGroups = collect();
        if ($supervisor) {
            $ghostGroups = $supervisor->thesisGroups()
                ->get()
                ->filter(fn ($group) => $group->isGhost());
        }

        return view('dashboard', [
            'student' => $student,
            'supervisor' => $supervisor,
            'thesisGroup' => $thesisGroup,
            'ghostGroups' => $ghostGroups,
        ]);
    }
}
