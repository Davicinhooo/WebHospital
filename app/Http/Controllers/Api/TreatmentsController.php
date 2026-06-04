<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TreatmentsResource;
use App\Models\Treatments;
use Illuminate\Http\Request;

class TreatmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $treatments = Treatments::all();
        return TreatmentsResource::collection($treatments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $treatments = Treatments::create($request->validated());
        return new TreatmentsResource($treatments);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $treatments = Treatments::findOrFail($id);
        return new TreatmentsResource($treatments);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $treatments = Treatments::findOrFail($id);
        $treatments->update($request->validated());
        return new TreatmentsResource($treatments);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $treatments = Treatments::findOrFail($id);
        $treatments->delete();
        return response()->json(null, 204);
    }
}
