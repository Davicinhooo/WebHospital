<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorsResource;
use App\Http\Requests\StoreDoctorsRequest;
use App\Http\Requests\UpdateDoctorsRequest;
use App\Models\Doctors;
use Illuminate\Http\Request;

class DoctorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $medicos = Doctors::when($buscar, function ($query, $buscar) {
            return $query->where('first_name', 'LIKE', "%$buscar%")
                         ->orWhere('last_name', 'LIKE', "%$buscar%");
        })->get();

        return view('medicos.index', compact('medicos', 'buscar'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDoctorsRequest $request)
    {
        Doctors::create($request->validated());
        return redirect()->route('medicos.index')->with('success', 'Medico agregado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $doctors = Doctors::findOrFail($id);
        return new DoctorsResource($doctors);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDoctorsRequest $request, string $id)
    {
        $medico = Doctors::findOrFail($id);
        $medico->update($request->validated());
        return redirect()->route('medicos.index')->with('success', 'Medico actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $medico = Doctors::findOrFail($id);
        $medico->delete();

        return redirect()->route('medicos.index')->with('success', 'Medico eliminado correctamente.');
    }

    public function generarLicencia()
    {
    $licenciaUnica = false;
    $codigo = '';

    // El bucle do-while seguirá generando códigos hasta encontrar uno que NO exista en la base de datos
    do {
        // Genera un código tipo: MED-74892
        $codigo = 'MED-' . rand(10000, 99999);
        
        // Verifica en tu modelo si ya existe ese código
        $existe = Doctors::where('license', $codigo)->exists();
        
        if (!$existe) {
            $licenciaUnica = true; // Si no existe, rompemos el bucle
        }
    } while (!$licenciaUnica);

    // Devuelve el código libre al JavaScript
    return response()->json(['licencia' => $codigo]);
    }

}
