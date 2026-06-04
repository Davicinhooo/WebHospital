<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MedicationsResource;
use App\Models\Medications;
use App\Http\Requests\StoreMedicationsRequest;
use App\Http\Requests\UpdateMedicationsRequest;
use Illuminate\Http\Request;

class MedicationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medications = Medications::all();
        return MedicationsResource::collection($medications);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMedicationsRequest $request)
    {
        $medications = Medications::create($request->validated());
        return new MedicationsResource($medications);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $medications = Medications::findOrFail($id);
        return new MedicationsResource($medications);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMedicationsRequest $request, string $id)
    {
        $medications = Medications::findOrFail($id);
        $medications->update($request->validated());
        return new MedicationsResource($medications);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $medications = Medications::findOrFail($id);
        $medications->delete();
        return response()->json(null, 204);
    }
}
