<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Treatments;
use App\Models\Diagnostics; 
use App\Models\Doctors; 
use App\Http\Requests\StoreTreatmentsRequest;
use App\Http\Requests\UpdateTreatmentsRequest;
use Illuminate\Http\Request;

class TreatmentsController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        // 1. Traemos tratamientos y sus relaciones (Diagnóstico y Médico)
        $tratamientos = Treatments::with(['diagnostic', 'doctor'])
            ->when($buscar, function ($query, $buscar) {
                // Buscamos por el nombre del tratamiento
                $query->where('name', 'LIKE', "%$buscar%")
                // O buscamos en la tabla de Diagnósticos conectada
                ->orWhereHas('diagnostic', function ($q) use ($buscar) {
                    $q->where('type_diagnosis', 'LIKE', "%$buscar%");
                })
                // O buscamos en la tabla de Médicos conectada
                ->orWhereHas('doctor', function ($q) use ($buscar) {
                    $q->where('first_name', 'LIKE', "%$buscar%")
                      ->orWhere('last_name', 'LIKE', "%$buscar%");
                });
            })->get();

        // 2. Traemos registros para llenar los <select> de los modales
        $diagnosticos = Diagnostics::all();
        $medicos = Doctors::all();

        // 3. Enviamos a la vista
        return view('tratamientos.index', compact('tratamientos', 'diagnosticos', 'medicos', 'buscar'));
    }

    public function store(StoreTreatmentsRequest $request)
    {
        Treatments::create($request->validated());
        return redirect()->route('tratamientos.index')->with('success', 'Tratamiento registrado exitosamente.');
    }

    public function show(string $id)
    {
        $tratamiento = Treatments::findOrFail($id);
        return response()->json($tratamiento);
    }

    public function update(UpdateTreatmentsRequest $request, string $id)
    {
        $tratamiento = Treatments::findOrFail($id);
        $tratamiento->update($request->validated());
        
        return redirect()->route('tratamientos.index')->with('success', 'Tratamiento actualizado exitosamente.');
    }

    public function destroy(string $id)
    {
        $tratamiento = Treatments::findOrFail($id);
        $tratamiento->delete();
        
        return redirect()->route('tratamientos.index')->with('success', 'Tratamiento eliminado del sistema.');
    }
}