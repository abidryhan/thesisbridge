<?php

namespace App\Http\Controllers;

use App\Models\Supervisor;
use App\Models\ThesisGroup;
use Illuminate\View\View;

class SupervisorMatchController extends Controller
{
    public function index(ThesisGroup $thesis_group): View
    {
        if (!$thesis_group->isAccessibleBy(auth()->user())) {
            abort(403);
        }

        $proposal = $thesis_group->proposal;

        if (!$proposal || empty($proposal->research_tags)) {
            return view('supervisor-matches.index', [
                'group' => $thesis_group,
                'noTags' => true,
                'supervisors' => collect(),
            ]);
        }

        $supervisors = Supervisor::with('user')
            ->get()
            ->reject(fn (Supervisor $supervisor) => $supervisor->currentLoad() >= $supervisor->max_capacity)
            ->map(function (Supervisor $supervisor) use ($thesis_group) {
                $supervisor->score = $supervisor->compatibilityScoreWith($thesis_group);
                return $supervisor;
            })
            ->sortByDesc('score')
            ->values();

        return view('supervisor-matches.index', [
            'group' => $thesis_group,
            'noTags' => false,
            'supervisors' => $supervisors,
        ]);
    }
}
