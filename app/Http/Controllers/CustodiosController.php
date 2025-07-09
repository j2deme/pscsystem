<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Misiones;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Geocoder\Laravel\Facades\Geocoder;

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

    public function guardarMision(Request $request)
{
    $request->validate([
        'agentes_id' => 'required|array',
        'agentes_id.*' => 'exists:users,id',
        'nivel_amenaza' => 'nullable|string|max:255',
        'tipo_servicio' => 'required|string|max:255',

        'ubicaciones' => 'required|array|min:1',
        'ubicaciones.*.direccion' => 'nullable|string',
        'ubicaciones.*.latitud' => 'nullable|numeric',
        'ubicaciones.*.longitud' => 'nullable|numeric',

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

    $ubicacionesProcesadas = [];

    foreach ($request->ubicaciones as $index => $ubicacion) {
        $direccion = $ubicacion['direccion'];
        $lat = $ubicacion['latitud'] ?? null;
        $lng = $ubicacion['longitud'] ?? null;

        if (!$lat || !$lng) {
            Log::info("Geocodificando dirección #$index", ['direccion' => $direccion]);

            try {
                $geo = Geocoder::geocode($direccion)->get()->first();

                if ($geo && $geo->getCoordinates()) {
                    $lat = $geo->getCoordinates()->getLatitude();
                    $lng = $geo->getCoordinates()->getLongitude();
                    Log::info("Coordenadas #$index obtenidas", ['lat' => $lat, 'lng' => $lng]);
                } else {
                    Log::warning("No se pudo geocodificar dirección #$index", ['direccion' => $direccion]);
                    return back()->withInput()->with('error', "No se pudo obtener coordenadas para la dirección: $direccion");
                }
            } catch (\Exception $e) {
                Log::error("Error geocodificando dirección #$index", [
                    'direccion' => $direccion,
                    'message' => $e->getMessage(),
                ]);
                return back()->withInput()->with('error', "Error geocodificando la dirección: $direccion");
            }
        }

        $ubicacionesProcesadas[] = [
            'direccion' => $direccion,
            'latitud' => $lat,
            'longitud' => $lng,
        ];
    }

    try {
        $mision = Misiones::create([
            'agentes_id' => json_encode($request->agentes_id),
            'nivel_amenaza' => $request->nivel_amenaza,
            'tipo_servicio' => $request->tipo_servicio,
            'ubicacion' => $ubicacionesProcesadas, // ya es array, Laravel lo serializa como JSON
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
    } catch (\Exception $e) {
        Log::error('Error al guardar misión:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'ubicaciones' => $ubicacionesProcesadas,
        ]);
        return back()->withInput()->with('error', 'Ocurrió un error al guardar la misión.');
    }

    $agentes = User::whereIn('id', $request->agentes_id)->get();

    $pdf = Pdf::loadView('pdf.mision', [
        'mision' => $mision,
        'agentes' => $agentes,
    ])->setPaper('a4', 'landscape');

    $rutaRelativa = "misiones/{$mision->id}/archivo_mision.pdf";
    Storage::makeDirectory("misiones/{$mision->id}");
    Storage::put($rutaRelativa, $pdf->output());

    $mision->arch_mision = $rutaRelativa;
    $mision->save();

    Log::info('Misión registrada exitosamente', ['id' => $mision->id]);

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

    public function mensajesIndex(){
        return view('custodios.mensajes');
    }

}
