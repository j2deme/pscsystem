<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SolicitudVacaciones;
use App\Models\SolicitudAlta;
use App\Models\SolicitudBajas;
use App\Models\DocumentacionAltas;
use App\Models\User;
use App\Models\Asistencia;
use App\Models\BuzonQueja;
use App\Models\Nomina;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function verUsuarios(){
        $users = User::all();
        return view('admi.verUsuarios', compact('users'));
    }

    public function tableroSupervisores(){
        return view('admi.tableroSupervisores');
    }

    public function verSolicitudesAltas(){
        $solicitudes = SolicitudAlta::where('status', 'Aceptada')
            ->whereDate('updated_at', Carbon::today('America/Mexico_City'))
            ->get();
        return view('admi.verSolicitudesAltas', compact('solicitudes'));
    }

    public function editarUsuarioForm($id){
        $user = User::find($id);
        return view('admi.editarUsuarioForm', compact('user'));
    }

    public function bajaUsuario($id, Request $request) {
        $user = User::find($id);
        $user->estatus = 'Inactivo';
        $user->save();

        $fechaBaja = $request->query('fecha')
            ? Carbon::parse($request->query('fecha'))->format('Y-m-d')
            : Carbon::today()->format('Y-m-d');

        $motivo = $request->query('motivo');

        if (!$fechaBaja || !$motivo) {
            return redirect()->back()->with('error', 'Faltan datos para dar de baja.');
        }

        $solicitud = new SolicitudBajas();
        $solicitud->user_id = $id;
        $solicitud->fecha_solicitud = Carbon::today();
        $solicitud->motivo = 'Desconocido';
        $solicitud->por = $motivo;
        $solicitud->incapacidad = '';
        $solicitud->fecha_baja = $fechaBaja;
        $solicitud->observaciones = 'Baja aceptada.';
        $solicitud->autoriza = Auth::user()->name;
        $solicitud->estatus = 'Aceptada';
        $solicitud->save();

        return redirect()->back()->with('success', 'El usuario ha sido dado de baja correctamente.');
    }

    public function editarUsuario($id){
        $user = User::find($id);
        $solicitudId = $user->sol_alta_id;
        $solicitud = SolicitudAlta::find($solicitudId);
        $docsId = $solicitud->sol_docs_id;
        $documentacion = DocumentacionAltas::find($docsId);

        return view('admi.admiEditarUsuarioForm', compact('user','solicitud', 'documentacion'));
    }

    public function verBuzon(){
        $quejas = BuzonQueja::orderBy('created_at', 'desc')
                ->paginate(10);

        return view ('admi.verBuzon', compact('quejas'));
    }

    public function darReingreso(Request $request, $id){
        $user = User::find($id);
        $user->estatus = 'Activo';
        $fechaReingreso = Carbon::parse($request->query('fecha'))->format('d-m-Y');

        $reingresoTexto = $user->solicitudAlta->reingreso;

        if (is_null($reingresoTexto) || trim($reingresoTexto) === '' || $reingresoTexto === 'NO') {
            $user->solicitudAlta->reingreso = "Reingreso 1: $fechaReingreso";
        } else {
            preg_match_all('/Reingreso \d+:/', $reingresoTexto, $coincidencias);
            $reingresosHechos = count($coincidencias[0]);

            $nuevoNumero = $reingresosHechos + 1;
            $user->solicitudAlta->reingreso .= " Reingreso $nuevoNumero: $fechaReingreso";
        }
        $user->solicitudAlta->save();
        $user->save();

        return redirect()->back()->with('success', 'El usuario ha sido dado de alta correctamente.');
    }

    public function tableroNominas(){
        return view('admi.tableroNominas');
    }
    public function tableroImss(){
        return view('admi.tableroImss');
    }
    public function tableroRh(){
        return view('admi.tableroRh');
    }
    public function tableroJuridico(){
        return view ('admi.tableroJuridico');
    }

    public function solicitudesVacaciones(){
        $vacaciones = SolicitudVacaciones::where('estatus', 'En Proceso')
        ->where('observaciones', '!=', 'Solicitud aceptada, falta subir archivo de solicitud.')
        ->whereHas('user', function ($query) {
            $query->where('empresa', 'Montana');
        })
        ->with('user')
        ->get();

        return view('admi.solicitudesVacaciones', compact('vacaciones'));
    }

    public function registrarNominas()
    {
        $hoy = now();
        $anio = $hoy->year;
        $mes = $hoy->month;

        // Determinar el último periodo completo
        if ($hoy->day <= 10) {
            // Ya estamos en el siguiente mes, último periodo fue del 11 al 25 del mes anterior
            $periodoInicio = Carbon::create($anio, $mes, 1)->subMonth()->setDay(11)->startOfDay();
            $periodoFin = Carbon::create($anio, $mes, 1)->subMonth()->setDay(25)->endOfDay();
            $quincena = '2°';
            $nombreMes = ucfirst($periodoFin->locale('es')->monthName);
            $anioPeriodo = $periodoFin->year;
        } elseif ($hoy->day <= 25) {
            // Último periodo fue del 26 del mes anterior al 10 del actual
            $mesAnterior = $mes - 1;
            $anioAnterior = $anio;
            if ($mesAnterior < 1) {
                $mesAnterior = 12;
                $anioAnterior--;
            }
            $periodoInicio = Carbon::create($anioAnterior, $mesAnterior, 26)->startOfDay();
            $periodoFin = Carbon::create($anio, $mes, 10)->endOfDay();
            $quincena = '1°';
            $nombreMes = ucfirst(Carbon::create($anio, $mes)->locale('es')->monthName);
            $anioPeriodo = $anio;
        } else {
            // Último periodo fue del 11 al 25 del mes actual
            $periodoInicio = Carbon::create($anio, $mes, 11)->startOfDay();
            $periodoFin = Carbon::create($anio, $mes, 25)->endOfDay();
            $quincena = '2°';
            $nombreMes = ucfirst(Carbon::create($anio, $mes)->locale('es')->monthName);
            $anioPeriodo = $anio;
        }

        $usuarios = User::where('estatus', 'Activo')->get();

        foreach ($usuarios as $user) {
            $asistencias = Asistencia::whereBetween('fecha', [$periodoInicio, $periodoFin])
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

            $sd = $user->solicitudAlta->sd ?? 0;
            $sdi = $user->solicitudAlta->sdi ?? 0;
            $diasTrabajados = $asistencias_count + $descansos_count;
            $percepciones = $sd * $diasTrabajados;

            if ($faltas_count === 0) {
                $percepciones *= 1.2;
            }

            $sueldo = $sdi * $diasTrabajados;
            $imss = ($sueldo * 0.00625) + ($sueldo * 0.01125) + ($sdi * 0.05);

            $sueldoBase = $sd * $diasTrabajados;
            if ($faltas_count === 0) $sueldoBase *= 1.2;

            $isr = 0;
            $tablaISR = [
                ['limInf' => 0.01, 'limSup' => 368.10, 'cuotaFija' => 0.00, 'porcentaje' => 1.92],
                ['limInf' => 368.11, 'limSup' => 3124.35, 'cuotaFija' => 7.05, 'porcentaje' => 6.4],
                ['limInf' => 3124.36, 'limSup' => 5437.91, 'cuotaFija' => 183.56, 'porcentaje' => 10.88],
                ['limInf' => 5437.92, 'limSup' => 7567.38, 'cuotaFija' => 544.68, 'porcentaje' => 16],
                ['limInf' => 7567.39, 'limSup' => INF, 'cuotaFija' => 913.63, 'porcentaje' => 17.92],
            ];
            foreach ($tablaISR as $r) {
                if ($sueldoBase >= $r['limInf'] && $sueldoBase <= $r['limSup']) {
                    $isr = $r['cuotaFija'] + (($sueldoBase - $r['limInf']) * ($r['porcentaje'] / 100));
                    break;
                }
            }

            $neto = $percepciones - ($imss + $isr);
            $periodoStr = "{$quincena} {$nombreMes} {$anioPeriodo}";

            Nomina::updateOrCreate(
                ['user_id' => $user->id, 'periodo' => $periodoStr],
                ['monto' => round(max(0, $neto), 2)]
            );
        }

        return redirect()->route('dashboard')->with('success', 'Nóminas del último periodo generadas correctamente.');
    }
}
