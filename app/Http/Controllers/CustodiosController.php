<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Misiones;
use Carbon\Carbon;

class CustodiosController extends Controller
{
    public function misionesIndex(){
        $hoy = Carbon::now();
        $misiones = Misiones::where('fecha_inicio', '<=', $hoy)
                    ->where('fecha_fin', '>=', $hoy)
                    ->get();

        return view('custodios.misionesActuales', compact('misiones'));
    }

    public function custodiosIndex(){
        $agentes = User::where('estatus', 'Activo')
            ->whereRaw("LOWER(rol) LIKE ?", ['%escolta%'])
            ->get();

        return view('custodios.listaCustodios', compact('agentes'));
    }

    public function nuevaMisionForm(){
        return view('custodios.nuevaMisionForm');
    }

    public function obtenerAgentesDisponibles(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $misiones = Misiones::whereBetween('fecha_fin', [$fechaInicio, $fechaFin])->get();

        $ocupados = collect();
        foreach ($misiones as $mision) {
            $ids = json_decode($mision->agentes_id, true) ?? [];
            $ocupados = $ocupados->merge($ids);
        }

        $ocupados = $ocupados->unique();
        $agentes = User::where('estatus', 'Activo')
            ->whereRaw("LOWER(rol) LIKE ?", ['%escolta%'])
            ->get();

        $agentesDisponibles = $agentes->map(function ($agente) use ($ocupados) {
            return [
                'id' => $agente->id,
                'name' => $agente->name,
                'ocupado' => $ocupados->contains($agente->id)
            ];
        });

        return response()->json($agentesDisponibles);
    }

    public function guardarMision(Request $request){
        $request->validate([
            'agentes_id' => 'required|array',
            'agentes_id.*' => 'exists:users,id',
            'tipo_servicio' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'cliente' => 'nullable|string|max:255',
            'pasajeros' => 'nullable|string|max:255',
            'tipo_operacion' => 'nullable|string|max:255',
            'num_vehiculos' => 'nullable|integer|min:0',
            'tipo_vehiculos' => 'nullable|array',
            'tipo_vehiculos.*' => 'string|max:255',
        ]);

        Misiones::create([
            'agentes_id' => json_encode($request->agentes_id),
            'tipo_servicio' => $request->tipo_servicio,
            'ubicacion' => $request->ubicacion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'cliente' => $request->cliente,
            'pasajeros' => $request->pasajeros,
            'tipo_operacion' => $request->tipo_operacion,
            'num_vehiculos' => $request->num_vehiculos,
            'tipo_vehiculos' => json_encode($request->tipo_vehiculos),
            'estatus' => 'Pendiente',
        ]);

        return redirect()->route('dashboard')->with('success', 'Misión registrada exitosamente.');
    }

    public function historialMisiones(){
        $misiones = Misiones::paginate(10);
        return view('custodios.historialMisiones', compact('misiones'));
    }

}
