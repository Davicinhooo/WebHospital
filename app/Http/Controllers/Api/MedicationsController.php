<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Medications;
use App\Models\Treatments; 
use App\Http\Requests\StoreMedicationsRequest;
use App\Http\Requests\UpdateMedicationsRequest;
use Illuminate\Http\Request;

class MedicationsController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        // 1. Traemos medicaciones y su tratamiento asociado
        $medicaciones = Medications::with(['treatment'])
            ->when($buscar, function ($query, $buscar) {
                // Buscamos por el nombre del medicamento
                $query->where('name', 'LIKE', "%$buscar%")
                // O buscamos en la tabla de Tratamientos conectada
                ->orWhereHas('treatment', function ($q) use ($buscar) {
                    $q->where('name', 'LIKE', "%$buscar%");
                });
            })->get();

        // 2. Traemos los tratamientos para llenar los <select> de los modales
        $tratamientos = Treatments::all();

        // 3. Enviamos a la vista
        return view('medicaciones.index', compact('medicaciones', 'tratamientos', 'buscar'));
    }

    public function store(StoreMedicationsRequest $request)
    {
        Medications::create($request->validated());
        return redirect()->route('medicaciones.index')->with('success', 'Medicación registrada exitosamente.');
    }

    public function show(string $id)
    {
        $medicacion = Medications::findOrFail($id);
        return response()->json($medicacion);
    }

    public function update(UpdateMedicationsRequest $request, string $id)
    {
        $medicacion = Medications::findOrFail($id);
        $medicacion->update($request->validated());
        
        return redirect()->route('medicaciones.index')->with('success', 'Medicación actualizada exitosamente.');
    }

    public function destroy(string $id)
    {
        $medicacion = Medications::findOrFail($id);
        $medicacion->delete();
        
        return redirect()->route('medicaciones.index')->with('success', 'Medicación eliminada del sistema.');
    }
}