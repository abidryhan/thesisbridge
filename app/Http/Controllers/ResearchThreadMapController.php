<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Illuminate\View\View;

class ResearchThreadMapController extends Controller
{
    public function index(): View
    {
        return view('research-thread-map.index', [
            'groupedEntries' => Proposal::researchThreadMap(),
        ]);
    }
}
