<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Misiones;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
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
            'nivel_amenaza' => 'nullable|string|max:255',
            'tipo_servicio' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'cliente' => 'nullable|string|max:255',
            'nombre_clave' => 'nullable|string|max:255',
            'pasajeros' => 'nullable|string|max:255',
            'tipo_operacion' => 'nullable|string|max:255',
            'num_vehiculos' => 'nullable|integer|min:0',
            'tipo_vehiculos' => 'nullable|array',
            'tipo_vehiculos.*' => 'string|max:255',
            'armados' => 'nullable|string|in:armado,desarmado',

            'hotel.nombre' => 'nullable|string|max:255',
            'hotel.direccion' => 'nullable|string|max:255',
            'hotel.telefono' => 'nullable|string|max:100',

            'aeropuerto.nombre' => 'nullable|string|max:255',
            'aeropuerto.direccion' => 'nullable|string|max:255',
            'aeropuerto.telefono' => 'nullable|string|max:100',

            'vuelo.fecha' => 'nullable|date',
            'vuelo.hora' => 'nullable',
            'vuelo.evento' => 'nullable|string|max:255',
            'vuelo.aeropuerto' => 'nullable|string|max:255',
            'vuelo.flight' => 'nullable|string|max:255',
            'vuelo.pax' => 'nullable|string|max:255',

            'hospital.nombre' => 'nullable|string|max:255',
            'hospital.direccion' => 'nullable|string|max:255',
            'hospital.telefono' => 'nullable|string|max:100',

            'embajada.nombre' => 'nullable|string|max:255',
            'embajada.direccion' => 'nullable|string|max:255',
            'embajada.telefono' => 'nullable|string|max:100',
        ]);
        $mision = Misiones::create([
            'agentes_id' => json_encode($request->agentes_id),
            'nivel_amenaza' => $request->nivel_amenaza,
            'tipo_servicio' => $request->tipo_servicio,
            'ubicacion' => $request->ubicacion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'cliente' => $request->cliente,
            'nombre_clave' => $request->nombre_clave,
            'pasajeros' => $request->pasajeros,
            'tipo_operacion' => $request->tipo_operacion,
            'num_vehiculos' => $request->num_vehiculos,
            'tipo_vehiculos' => json_encode($request->tipo_vehiculos),
            'armados' => $request->armados,
            'datos_hotel' => json_encode($request->input('hotel', [])),
            'datos_aeropuerto' => json_encode($request->input('aeropuerto', [])),
            'datos_vuelo' => json_encode($request->input('vuelo', [])),
            'datos_hospital' => json_encode($request->input('hospital', [])),
            'datos_embajada' => json_encode($request->input('embajada', [])),
            'estatus' => 'Pendiente',
        ]);

        $agentes = User::whereIn('id', $request->agentes_id)->get();

        $pdf = Pdf::loadView('pdf.mision', [
            'mision' => $mision,
            'agentes' => $agentes,
        ])
        ->setPaper('a4', 'landscape');

        $rutaRelativa = "misiones/{$mision->id}/archivo_mision.pdf";
        Storage::makeDirectory("misiones/{$mision->id}");
        Storage::put($rutaRelativa, $pdf->output());

        $mision->arch_mision = $rutaRelativa;
        $mision->save();

        return redirect()->route('dashboard')->with('success', 'Misión registrada exitosamente.');
    }

    public function historialMisiones(){
        $misiones = Misiones::paginate(10);
        return view('custodios.historialMisiones', compact('misiones'));
    }

    public function misionesTerminadas(){
        $misiones = Misiones::where('estatus', 'Terminada')
            ->where('fecha_fin', '<', Carbon::now())
            ->paginate(10);
        return view('custodios.misionesTerminadas', compact('misiones'));
    }

}
