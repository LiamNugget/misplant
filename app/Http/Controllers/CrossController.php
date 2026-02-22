<?php

namespace App\Http\Controllers;

use App\Models\CactusClone;
use App\Models\Cross;
use App\Models\Tag;
use Illuminate\Http\Request;

class CrossController extends Controller
{
    public function index(Request $request)
    {
        $query = Cross::with(['mother.primaryImage', 'father.primaryImage']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('mother', fn ($mq) => $mq->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('father', fn ($fq) => $fq->where('name', 'like', "%{$search}%"))
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($species = $request->input('species')) {
            $query->where(function ($q) use ($species) {
                $q->whereHas('mother', fn ($mq) => $mq->where('species', $species))
                    ->orWhereHas('father', fn ($fq) => $fq->where('species', $species));
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($tag = $request->input('tag')) {
            $query->where(function ($q) use ($tag) {
                $q->whereHas('mother.tags', fn ($tq) => $tq->where('slug', $tag))
                    ->orWhereHas('father.tags', fn ($tq) => $tq->where('slug', $tag));
            });
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        $sort = $request->input('sort', 'code');
        $direction = $request->input('direction', 'asc');
        $allowedSorts = ['code', 'price', 'seed_count', 'created_at'];

        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');
        }

        $speciesList = CactusClone::distinct()->pluck('species')->sort()->values();

        $tags = Tag::has('clones')->orderBy('name')->get()->each(function ($tag) {
            $tag->crosses_count = Cross::where(function ($q) use ($tag) {
                $q->whereHas('mother.tags', fn ($tq) => $tq->where('tags.id', $tag->id))
                    ->orWhereHas('father.tags', fn ($tq) => $tq->where('tags.id', $tag->id));
            })->count();
        })->filter(fn ($tag) => $tag->crosses_count > 0)->values();

        return view('crosses.index', [
            'crosses' => $query->paginate(24)->withQueryString(),
            'speciesList' => $speciesList,
            'tags' => $tags,
            'filters' => $request->only(['search', 'species', 'status', 'tag', 'min_price', 'max_price', 'sort', 'direction']),
        ]);
    }

    public function show(Cross $cross)
    {
        $cross->load(['mother.images', 'father.images']);

        return view('crosses.show', [
            'cross' => $cross,
            'relatedCrosses' => $cross->relatedCrosses(6)->get(),
        ]);
    }
}
