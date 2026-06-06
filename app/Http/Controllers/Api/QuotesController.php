<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quotes;
use App\Models\Doctors; 
use App\Models\Patients; 
use App\Http\Requests\StoreQuotesRequest;
use App\Http\Requests\UpdateQuotesRequest;
use Illuminate\Http\Request;

class QuotesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        // 1. Traemos las citas y hacemos "Eager Loading" (with) de los pacientes y doctores 
        // para ahorrar consultas a la base de datos.
        $citas = Quotes::with(['patient', 'doctor'])
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
        return view('citas.index', compact('citas', 'pacientes', 'medicos', 'buscar'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuotesRequest $request)
    {
        Quotes::create($request->validated());
        
        // Redirigimos a la web con un mensaje de éxito, igual que en Médicos
        return redirect()->route('citas.index')->with('success', 'Cita programada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // El show casi no se usa con modales, pero lo dejamos listo
        $cita = Quotes::findOrFail($id);
        return response()->json($cita);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuotesRequest $request, string $id)
    {
        $cita = Quotes::findOrFail($id);
        $cita->update($request->validated());
        
        return redirect()->route('citas.index')->with('success', 'Cita actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cita = Quotes::findOrFail($id);
        $cita->delete();
        
        return redirect()->route('citas.index')->with('success', 'Cita cancelada y eliminada.');
    }
}