<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DiagnosticsResource;
use App\Models\Diagnostics;
use App\Http\Requests\StoreDiagnosticsRequest;
use App\Http\Requests\UpdateDiagnosticsRequest;
use Illuminate\Http\Request;

class DiagnosticsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $diagnostics = Diagnostics::all();
        return DiagnosticsResource::collection($diagnostics);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDiagnosticsRequest $request)
    {
        $diagnostics = Diagnostics::create($request->validated());
        return new DiagnosticsResource($diagnostics);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $diagnostics = Diagnostics::findOrFail($id);
        return new DiagnosticsResource($diagnostics);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDiagnosticsRequest $request, string $id)
    {
        $diagnostics = Diagnostics::findOrFail($id);
        $diagnostics->update($request->validated());
        return new DiagnosticsResource($diagnostics);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $diagnostics = Diagnostics::findOrFail($id);
        $diagnostics->delete();
        return response()->json(null, 204);
    }
}
