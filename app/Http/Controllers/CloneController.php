<?php

namespace App\Http\Controllers;

use App\Models\CactusClone;
use App\Models\Tag;
use Illuminate\Http\Request;

class CloneController extends Controller
{
    public function index(Request $request)
    {
        $query = CactusClone::active()
            ->with('primaryImage')
            ->withCount(['crossesAsMother', 'crossesAsFather']);

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($species = $request->input('species')) {
            $query->where('species', $species);
        }

        if ($tag = $request->input('tag')) {
            $query->withTag($tag);
        }

        $sort = $request->input('sort', 'name');
        $direction = $request->input('direction', 'asc');
        $allowedSorts = ['name', 'species', 'crosses_as_mother_count'];

        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');
        }

        $speciesList = CactusClone::distinct()->pluck('species')->sort()->values();

        $tags = Tag::whereHas('clones', fn ($q) => $q->where('is_active', true))
            ->withCount(['clones' => fn ($q) => $q->where('is_active', true)])
            ->orderBy('name')->get();

        return view('clones.index', [
            'clones' => $query->paginate(24)->withQueryString(),
            'speciesList' => $speciesList,
            'tags' => $tags,
            'filters' => $request->only(['search', 'species', 'tag', 'sort', 'direction']),
        ]);
    }

    public function show(CactusClone $cactusClone)
    {
        $cactusClone->load(['images', 'primaryImage', 'tags']);

        $crosses = $cactusClone->allCrosses()
            ->orderBy('status')
            ->orderBy('price')
            ->get();

        return view('clones.show', [
            'clone' => $cactusClone,
            'crosses' => $crosses,
        ]);
    }
}
