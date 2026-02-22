<?php

namespace App\Http\Controllers;

use App\Models\CactusClone;
use App\Models\Cross;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('home', [
            'hotCrosses' => Cross::hotCrosses()->with(['mother.primaryImage', 'father.primaryImage'])->limit(6)->get(),
            'totalCrosses' => Cross::count(),
            'availableCrosses' => Cross::available()->count(),
            'totalClones' => CactusClone::active()->count(),
            'speciesCount' => CactusClone::distinct()->count('species'),
            'recentCrosses' => Cross::available()->with(['mother.primaryImage', 'father.primaryImage'])->latest()->limit(6)->get(),
        ]);
    }
}
