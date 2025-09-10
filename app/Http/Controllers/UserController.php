<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\SolicitudAlta;
use App\Models\SolicitudBajas;
use App\Models\SolicitudVacaciones;
use App\Models\DocumentacionAltas;
use App\Models\BuzonQueja;
use Carbon\Carbon;

class UserController extends Controller
{
    public function crearUsuario(){
        return view('admi.crearUsuario');
    }

    public function registrarUsuario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'punto' => $request->punto,
            'empresa' => $request->empresa,
            'estatus' => 'Activo',
            'fecha_ingreso' => date('Y-m-d'),
        ]);

        return redirect()->route('admin.verUsuarios')->with('success', '¡Usuario creado exitosamente!');
    }

    public function solicitarBajaForm(){
        $user = User::find(Auth::user()->id);
        $solicitud = SolicitudAlta::where('id', $user->sol_alta_id)->first();
        $solicitudpendiente = SolicitudBajas::where('user_id', $user->id)->where('estatus', 'En Proceso')->first();
        return view('users.solicitarBajaForm', compact('user','solicitud','solicitudpendiente'));
    }

    public function solicitarBaja(Request $request, $id){
        $request->validate([
            'fecha_hoy' => 'required|date',
            'incapacidad' => 'nullable|string|max:255',
            'por' => 'required|in:Ausentismo,Separación Voluntaria, Renuncia',
            'ultima_asistencia' => 'nullable|date',
            'motivo' => 'nullable|string',
        ]);

        $user = User::findorFail($id);

        $solicitud = new SolicitudBajas();
        $solicitud->user_id = $user->id;
        $solicitud->fecha_solicitud = $request->fecha_hoy;
        $solicitud->motivo = $request->motivo;
        $solicitud->incapacidad = $request->incapacidad;
        $solicitud->por = $request->por;
        $solicitud->ultima_asistencia = $request->ultima_asistencia;
        $solicitud->estatus = 'En Proceso';
        $solicitud->observaciones = 'Solicitud de baja en proceso';
        try{
            $solicitud->save();
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Error al enviar la solicitud');
        }

        return redirect()->route('dashboard')->with('success', 'Solicitud de baja enviada correctamente');
    }
    public function solicitarVacacionesForm(){
        $user = User::find(Auth::user()->id);
        $antiguedad = (int) floor(Carbon::parse($user->fecha_ingreso)->floatDiffInYears(now('America/Mexico_City')));
        $fechaIngreso = Carbon::parse($user->fecha_ingreso);
        $fechaActual = Carbon::now('America/Mexico_City');

        if ($antiguedad === 0) {
            $mesesLaborados = $fechaIngreso->diffInMonths($fechaActual);
        } else {
            $mesesLaborados = 0;
        }

        if($antiguedad <2){
            $dias=12;
        }elseif($antiguedad ==2){
            $dias=14;
        }elseif($antiguedad ==3){
            $dias=16;
        }elseif($antiguedad ==4){
            $dias=18;
        }elseif($antiguedad ==5){
            $dias=20;
        }elseif($antiguedad>5 && $antiguedad<=10){
            $dias=22;
        }elseif($antiguedad>10 && $antiguedad<=15){
            $dias=24;
        }elseif($antiguedad>15 && $antiguedad<=20){
            $dias=26;
        }elseif($antiguedad>20 && $antiguedad<=25){
            $dias=28;
        }elseif($antiguedad>25 && $antiguedad<=30){
            $dias=30;
        }else{
            $dias=32;
        }

        $diasDisponibles = $dias;
        $diasUtilizados = 0;
        $aniversario = Carbon::createFromDate(
            now()->year,
            $fechaIngreso->month,
            $fechaIngreso->day
        );

        if ($aniversario->isFuture()) {
            $aniversario->subYear();
        }
        $vacacionesTomadas = SolicitudVacaciones::where('user_id', $user->id)
            ->whereIn('estatus', ['Aceptada', 'En Proceso'])
            ->where('created_at', '>=', $aniversario)
            ->get();

        foreach ($vacacionesTomadas as $vacacion) {
            $diasDisponibles -= $vacacion->dias_solicitados;
            $diasUtilizados += $vacacion->dias_solicitados;
        }

        $solicitud = SolicitudAlta::where('id', $user->sol_alta_id)->first();
        $documentacion = DocumentacionAltas::where('solicitud_id', $user->sol_alta_id)->first();

        return view('users.solicitarVacacionesForm', compact('user','solicitud', 'documentacion', 'antiguedad','dias', 'diasDisponibles', 'diasUtilizados', 'mesesLaborados'));
    }

    public function solicitarVacaciones(Request $request, $id){
        $request->validate([
            'tipo' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
            'dias_solicitados' => 'required|integer|min:1|max:30',
            'dias_utilizados' => 'required|integer|min:0|max:36',
            'dias_disponibles' => 'required|integer|min:0|max:36',
            'dias_por_derecho' => 'required|integer|min:0|max:36',
        ]);

        $user = User::findorFail($id);
        if(Auth::user()->rol == 'Supervisor' || Auth::user()->rol == 'SUPERVISOR'){
            $supervisor = User::where('rol', 'admin')->get();
        }
        $supervisores = User::where('rol', 'admin')->pluck('id')->toArray();
        $solicitud = new SolicitudVacaciones();
        $solicitud->user_id = $user->id;
        $solicitud->tipo = $request->tipo;
        $solicitud->fecha_inicio = $request->fecha_inicio;
            //$solicitud->supervisor_id = $supervisor->id;
        $solicitud->supervisores_ids = json_encode($supervisores);
        $solicitud->fecha_fin = $request->fecha_fin;
        $solicitud->dias_solicitados = $request->dias_solicitados;
        $solicitud->dias_ya_utlizados = $request->dias_utilizados;
        $solicitud->dias_disponibles = $request->dias_disponibles;
        $solicitud->dias_por_derecho = $request->dias_por_derecho;
        $solicitud->monto = 0.0;

        if(Auth::user()->rol == 'Supervisor' || Auth::user()->rol == 'admin' || Auth::user()->rol == 'SUPERVISOR' || Auth::user()->solicitudAlta->departamento == 'Recursos Humanos' || Auth::user()->solicitudAlta->rol == 'AUXILIAR RECURSOS HUMANOS' || Auth::user()->solicitudAlta->rol == 'AUXILIAR RH' || Auth::user()->solicitudAlta->rol == 'AUX RH' || Auth::user()->solicitudAlta->rol == 'Auxiliar RH' || Auth::user()->solicitudAlta->rol == 'Auxiliar Recursos Humanos' || Auth::user()->solicitudAlta->rol == 'Aux RH' || Auth::user()->rol == 'AUXILIAR RECURSOS HUMANOS' || Auth::user()->rol == 'Auxiliar recursos humanos')
            $solicitud->observaciones = 'Solicitud aceptada, falta subir archivo de solicitud.';
        else
            $solicitud->observaciones = 'Solicitud de vacaciones en proceso';
        $solicitud->estatus = 'En Proceso';

        $solicitud->save();
        if(Auth::user()->rol == 'admin' || Auth::user()->solicitudAlta->departamento == 'Recursos Humanos' || Auth::user()->solicitudAlta->rol == 'AUXILIAR RECURSOS HUMANOS' || Auth::user()->solicitudAlta->rol == 'AUXILIAR RH' || Auth::user()->solicitudAlta->rol == 'AUX RH' || Auth::user()->solicitudAlta->rol == 'Auxiliar RH' || Auth::user()->solicitudAlta->rol == 'Auxiliar Recursos Humanos' || Auth::user()->solicitudAlta->rol == 'Aux RH' || Auth::user()->rol == 'AUXILIAR RECURSOS HUMANOS' || Auth::user()->rol == 'Auxiliar recursos humanos')
            return redirect()->route('dashboard')->with('success', 'Solicitud de vacaciones enviada correctamente');
        else
            return redirect()->route('dashboard')->with('success', 'Solicitud de vacaciones enviada correctamente');
    }

    public function historialVacaciones(){
        $user = User::find(Auth::user()->id);
        $vacaciones = SolicitudVacaciones::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('users.historialVacaciones', compact('vacaciones'));
    }

    public function verFicha($id){
        $user = User::findorFail($id);
        $solicitud = SolicitudAlta::where('id', $user->sol_alta_id)->first();
        $documentacion = DocumentacionAltas::where('solicitud_id', $user->sol_alta_id)->first();

        return view('admi.verFichaUser', compact('user','solicitud', 'documentacion'));
    }

    public function buzon(){
        return view('users.buzon');
    }

    public function enviarSugerencia(Request $request, $id){
        $user = User::findorFail($id);
        $request->validate([
            'fecha' => 'required|date',
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string|max:1024'
        ]);

        $mensaje = new BuzonQueja();
        $mensaje->user_id = $user->id;
        $mensaje->fecha = Carbon::parse($request->fecha);
        $mensaje->asunto = $request->asunto;
        $mensaje->mensaje = $request->mensaje;
        $mensaje->save();

        return redirect()->route('user.buzon')->with('success', 'Mensaje enviado correctamente');
    }
}
