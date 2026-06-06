<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Diagnostics;
use App\Models\Doctors; 
use App\Models\Patients; 
use App\Http\Requests\StoreDiagnosticsRequest;
use App\Http\Requests\UpdateDiagnosticsRequest;
use Illuminate\Http\Request;

class DiagnosticsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        // 1. Traemos las citas y hacemos "Eager Loading" (with) de los pacientes y doctores 
        // para ahorrar consultas a la base de datos.
        $diagnosticos = Diagnostics::with(['patient', 'doctor'])
            ->when($buscar, function ($query, $buscar) {
                // Buscamos en la tabla Pacientes conectada
                return $query->whereHas('patient', function ($q) use ($buscar) {
                    $q->where('first_name', 'LIKE', "%$buscar%")
                      ->orWhere('last_name', 'LIKE', "%$buscar%");
                })
                // O buscamos en la tabla Médicos conectada
                ->orWhereHas('doctor', function ($q) use ($buscar) {
                    $q->where('first_name', 'LIKE', "%$buscar%")
                      ->orWhere('last_name', 'LIKE', "%$buscar%");
                });
            })->get();

        // 2. Traemos todos los registros para llenar los <select> de los modales
        $pacientes = Patients::all();
        $medicos = Doctors::all();

        // 3. Enviamos todo a la vista
        return view('diagnosticos.index', compact('diagnosticos', 'pacientes', 'medicos', 'buscar'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDiagnosticsRequest $request)
    {
        Diagnostics::create($request->validated());
        
        // Redirigimos a la web con un mensaje de éxito, igual que en Médicos
        return redirect()->route('diagnosticos.index')->with('success', 'Registro creado.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // El show casi no se usa con modales, pero lo dejamos listo
        $diagnosticos = Diagnostics::findOrFail($id);
        return response()->json($cita);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDiagnosticsRequest $request, string $id)
    {
        $diagnosticos = Diagnostics::findOrFail($id);
        $diagnosticos->update($request->validated());
        
        return redirect()->route('diagnosticos.index')->with('success', 'Registro actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $diagnosticos = Diagnostics::findOrFail($id);
        $diagnosticos->delete();
        
        return redirect()->route('diagnosticos.index')->with('success', 'Registro eliminado exitosamente.');
    }
}