<?php

namespace App\Http\Controllers;

use App\Models\ThesisGroup;
use App\Models\Supervisor;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SupervisorMatchController extends Controller
{
    public function index(ThesisGroup $thesis_group): View|RedirectResponse
    {
        $proposal = $thesis_group->proposal;

        if (!$proposal) {
            return redirect()
                ->route('thesis-groups.show', $thesis_group)
                ->with('error', 'Submit a proposal first to see supervisor matches.');
        }

        $supervisors = Supervisor::all();

        $rankedSupervisors = $supervisors
            ->map(function ($supervisor) use ($proposal) {
                $proposalTags = $proposal->research_tags ?? [];
                $supervisorAreas = $supervisor->research_areas ?? [];

                $matchCount = count(array_intersect($proposalTags, $supervisorAreas));

                return [
                    'supervisor' => $supervisor,
                    'matchCount' => $matchCount,
                ];
            })
            ->sortByDesc('matchCount');

        return view('supervisor-matches.index', [
            'group' => $thesis_group,
            'proposal' => $proposal,
            'matches' => $rankedSupervisors,
        ]);
    }
}