<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Asistencia;
use App\Models\SolicitudVacaciones;
use App\Models\SolicitudAlta;
use App\Models\SolicitudBajas;
use App\Models\Punto;
use App\Models\Subpunto;
use App\Models\Deducciones;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NominasController extends Controller
{
    public function antiguedades(){
        return view('nominas.antiguedades');
    }

    public function verBajas(){
        $bajas = SolicitudBajas::where('estatus', 'Aceptada')
            ->where('por', 'Renuncia')
            ->whereDate('created_at', '>=', Carbon::today('America/Mexico_City')->subDays(5))
            ->paginate(10);
        return view('nominas.verBajas', compact('bajas'));
    }

    public function nuevasAltas(){
        $solicitudes = SolicitudAlta::where('status', 'Aceptada')
            ->whereDate('fecha_ingreso', '>=', Carbon::today('America/Mexico_City')->subDays(5))//si se requiere respetar a toda la quincena
            ->get();
        $users = User::where('estatus', 'Activo')
            ->where('fecha_ingreso', '>=', Carbon::today('America/Mexico_City')->subDays(5))
            ->get();
        return view('nominas.nuevasAltas', compact('solicitudes', 'users'));
    }

    public function guardarCalculoFiniquito(Request $request)
    {
        Log::info('Solicitud recibida para guardar imagen de finiquito.', [
            'solicitud_id' => $request->input('solicitud_id')
        ]);

        try {
            $request->validate([
                'imagen' => 'required|string',
                'solicitud_id' => 'required|integer|exists:solicitud_bajas,id',
            ]);

            $imagenBase64 = $request->input('imagen');
            $solicitudId = $request->input('solicitud_id');

            $solicitud = SolicitudBajas::find($solicitudId);
            if (!$solicitud) {
                Log::error("Solicitud con ID {$solicitudId} no encontrada.");
                return response()->json(['success' => false, 'error' => 'Solicitud no encontrada.']);
            }

            if ($solicitud->calculo_finiquito && Storage::disk('public')->exists($solicitud->calculo_finiquito)) {
                Storage::disk('public')->delete($solicitud->calculo_finiquito);
                Log::info("Archivo anterior eliminado: {$solicitud->calculo_finiquito}");
            }

            $imagen = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imagenBase64));
            if (!$imagen) {
                Log::error('No se pudo decodificar la imagen.');
                return response()->json(['success' => false, 'error' => 'No se pudo decodificar la imagen.']);
            }

            $carpeta = "solicitudesBajas/{$solicitudId}";
            Storage::disk('public')->makeDirectory($carpeta);

            $nombreArchivo = 'finiquito_' . now()->format('Ymd_His') . '.png';
            $rutaCompleta = "{$carpeta}/{$nombreArchivo}";

            Storage::disk('public')->put($rutaCompleta, $imagen);
            Log::info("Imagen guardada correctamente en: {$rutaCompleta}");

            $solicitud->calculo_finiquito = $rutaCompleta;
            $solicitud->observaciones = "Finiquito enviado a RH.";
            $solicitud->save();

            Log::info('Ruta del finiquito actualizada en la base de datos.');

            return response()->json(['success' => true, 'ruta' => $rutaCompleta]);

        } catch (\Exception $e) {
            Log::error('Error al guardar imagen de finiquito: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Error interno.']);
        }
    }

    public function asistenciasNominas(){
        return view('nominas.asistencias');
    }

    public function vacacionesNominas(){
        return view('nominas.vacaciones');
    }

    public function vacacionesIndex(Request $request)
    {
        $query = SolicitudVacaciones::query()->where('estatus', 'Aceptada');
        if ($request->filled('fecha_inicio')) {
            $query->where('fecha_inicio', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->where('fecha_fin', '<=', $request->fecha_fin);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('punto')) {
            $userIdsDirectos = User::where('punto', $request->punto)->pluck('id');

            if ($userIdsDirectos->isNotEmpty()) {
                $query->whereIn('user_id', $userIdsDirectos);
            } else {
                $punto = Punto::where('nombre', $request->punto)->first();
                if ($punto) {
                    $subpuntos = Subpunto::where('punto_id', $punto->id)->pluck('nombre');
                    $userIds = User::whereIn('punto', $subpuntos)->pluck('id');
                    $query->whereIn('user_id', $userIds);
                }
            }
        }
        $vacaciones = $query->with('user')->get();

        return view('nominas.vacaciones', compact('vacaciones'));
    }

    public function vistaNominas(){
        return view('nominas.vistaNominas');
    }

    public function calculosNominas(Request $request)
    {
        $query = User::query()->where('estatus', 'Activo');

        if ($request->filled('punto')) {
            $punto = Punto::where('nombre', $request->punto)->first();

            if ($punto) {
                $subpuntos = Subpunto::where('punto_id', $punto->id)->pluck('nombre');
                $query->whereIn('punto', $subpuntos);
            } else {
                $query->where('punto', $request->punto);
            }
        }

        $usuarios = $query->get();
        $fechaInicio = $request->fecha_inicio ? Carbon::parse($request->fecha_inicio) : null;
    $fechaFin = $request->fecha_fin ? Carbon::parse($request->fecha_fin) : null;

    foreach ($usuarios as $user) {
        $asistencias = Asistencia::query()
            ->when($fechaInicio && $fechaFin, function ($q) use ($fechaInicio, $fechaFin) {
                return $q->whereBetween('fecha', [$fechaInicio, $fechaFin]);
            })
            ->where('punto', $user->punto)
            ->get();

        $asistencias_count = 0;
        $descansos_count = 0;
        $faltas_count = 0;

        foreach ($asistencias as $registro) {
            $enlistados = json_decode($registro->elementos_enlistados, true) ?? [];
            $descansos = json_decode($registro->descansos, true) ?? [];
            $faltas = json_decode($registro->faltas, true) ?? [];

            if (in_array($user->id, $enlistados)) $asistencias_count++;
            if (in_array($user->id, $descansos)) $descansos_count++;
            if (in_array($user->id, $faltas)) $faltas_count++;
        }

        $user->asistencias_count = $asistencias_count;
        $user->descansos_count = $descansos_count;
        $user->faltas_count = $faltas_count;
    }

        return view('nominas.vistaNominas', compact('usuarios'));
    }

    public function graficas(){
        return view('nominas.graficas');
    }

    public function deduccionesIndex(){
        $deducciones = Deducciones::where('status', 'Pendiente')
            ->paginate(10);
        return view('nominas.deducciones', compact('deducciones'));
    }

    public function nuevaDeduccionForm(){
        return view('nominas.deduccionForm');
    }

    public function guardarDeduccion(Request $request){
        try{
            $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'concepto' => 'required|string',
                'fecha_inicio' => 'required|date',
                'monto' => 'required|numeric',
                'num_quincenas' => 'required|integer',
            ]);


            $deduccion = new Deducciones();
            $deduccion->user_id = $request->user_id;
            $deduccion->concepto = $request->concepto;
            $deduccion->fecha_inicio = $request->fecha_inicio;
            $deduccion->monto = $request->monto;
            $deduccion->num_quincenas = $request->num_quincenas;
            $deduccion->monto_pendiente = $request->monto;
            $deduccion->status = 'Pendiente';
            $deduccion->save();

            return redirect()->route('nominas.deducciones')
                ->with('success', 'Deducción guardada correctamente');
        }catch(\Exception $e){
            Log::error('Error al guardar deduccion: '. $e->getMessage());
            return redirect()->route('nominas.deducciones')
                ->with('error', 'Error al guardar la deducción: '. $e->getMessage())
                ->withInput();
        }
    }

}
