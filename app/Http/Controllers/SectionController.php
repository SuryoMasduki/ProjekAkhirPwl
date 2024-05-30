<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Section;
use App\Http\Resources\ClassesResource;
use App\Http\Resources\ClassResource;
use App\Http\Resources\SectionsResource;
use App\Http\Resources\SectionResource;
use App\Http\Requests\StoreSectionRequest;
use App\Http\Requests\UpdateSectionRequest;
use Illuminate\Database\Eloquent\Builder;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sectionQuery = Section::query();
        $sectionQuery = $this->applySearch($sectionQuery, request('search'));
        $paginateClass = SectionsResource::collection(
            $sectionQuery->paginate(5)
        );
        return inertia('Section/Index', [
            'sections' => $paginateClass,
            'search' => request('search') ?? ''
        ]);
    }

    protected function applySearch(Builder $query, $search)
    {
        return $query->when($search, function ($query, $search) {
            $query->where('name', 'like', '%' . $search . '%');
        });
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes = ClassResource::collection(Classes::all());

        return inertia('Section/Create', [
            'classes' => $classes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSectionRequest $request)
    {
        Section::create($request->validated());

        return redirect()->route('sections.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Section $section)
    {
        $classes = ClassResource::collection(Classes::all());

        return inertia('Section/Edit', [
            'section' => SectionResource::make($section),
            'classes' => $classes
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSectionRequest $request, Section $section)
    {
        $section->update($request->validated());

        return redirect()->route('sections.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Section $section)
    {
        $section->delete();

        return redirect()->route('sections.index');
    }
}
