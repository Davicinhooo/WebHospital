<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorsResource;
use App\Models\Doctors;
use Illuminate\Http\Request;

class DoctorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctors::all();
        return DoctorsResource::collection($doctors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $doctors = Doctors::create($request->validated());
        return new DoctorsResource($doctors);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $doctors = Doctors::findOrFail($id);
        return new DoctorsResource($doctors);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $doctors = Doctors::findOrFail($id);
        $doctors->update($request->validated());
        return new DoctorsResource($doctors);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $doctors = Doctors::findOrFail($id);
        $doctors->delete();
        return response()->json(null, 204);
    }
}
