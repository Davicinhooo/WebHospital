<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuotesResource;
use App\Models\Quotes;
use Illuminate\Http\Request;
use League\CommonMark\Extension\SmartPunct\Quote;

class QuotesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quotes = Quotes::all();
        return QuotesResource::collection($quotes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $quotes = Quotes::create($request->validated());
        return new QuotesResource($quotes);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $quotes = Quotes::findOrFail($id);
        return new QuotesResource($quotes);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $quotes = Quotes::findOrFail($id);
        $quotes->update($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $quotes = Quotes::findOrFail($id);
        $quotes->delete();
        return response()->json(null, 204);
    }
}
