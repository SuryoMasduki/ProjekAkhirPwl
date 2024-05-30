<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Http\Resources\ClassesResource;
use App\Http\Requests\StoreClassRequest;
use App\Http\Requests\UpdateClassRequest;
use Illuminate\Database\Eloquent\Builder;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classQuery = Classes::query();
        $classQuery = $this->applySearch($classQuery, request('search'));
        $paginateClass = ClassesResource::collection(
            $classQuery->paginate(5)
        );
        return inertia('Class/Index', [
            'classes' => $paginateClass,
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
        return inertia('Class/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClassRequest $request)
    {
        Classes::create($request->validated());

        return redirect()->route('classes.index');
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
    public function edit(Classes $class)
    {
        return inertia('Class/Edit', [
            'class' => ClassesResource::make($class)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClassRequest $request, Classes $class)
    {
        $class->update($request->validated());

        return redirect()->route('classes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classes $class)
    {
        $class->delete();

        return redirect()->route('classes.index');
    }
}
