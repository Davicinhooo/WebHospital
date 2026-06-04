<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientsResource;
use App\Models\Patients;
use Illuminate\Http\Request;

class PatientsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patients::all();
        return PatientsResource::collection($patients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $patients = Patients::create($request->validated());
        return new PatientsResource($patients);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $patients = Patients::findOrFail($id);
        return new PatientsResource($patients);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $patients = Patients::findOrFail($id);
        $patients->update($request->validated());
        return new PatientsResource($patients);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $patients = Patients::findOrFail($id);
        $patients->delete();
        return response()->json(null, 204);
    }
}
