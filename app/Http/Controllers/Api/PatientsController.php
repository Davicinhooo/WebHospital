<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientsResource;
use App\Http\Requests\StorePatientsRequest;
use App\Http\Requests\UpdatePatientsRequest;
use App\Models\Patients;
use Illuminate\Http\Request;

class PatientsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        // Si el usuario escribió algo en el buscador, filtramos. Si no, traemos todos.
        $pacientes = Patients::when($buscar, function ($query, $buscar) {
            return $query->where('first_name', 'LIKE', "%$buscar%")
                         ->orWhere('last_name', 'LIKE', "%$buscar%");
        })->get();

        return view('pacientes.index', compact('pacientes', 'buscar'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePatientsRequest $request)
    {
        Patients::create($request->validated());
        return redirect()->route('pacientes.index')->with('success', 'Paciente agregado exitosamente.');
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
    public function update(UpdatePatientsRequest $request, string $id)
    {
        $paciente = Patients::findOrFail($id);
        $paciente->update($request->validated());
        return redirect()->route('pacientes.index')->with('success', 'Paciente actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $paciente = Patients::findOrFail($id);
        $paciente->delete();

        return redirect()->route('pacientes.index')->with('success', 'Paciente eliminado correctamente.');
    }
}
