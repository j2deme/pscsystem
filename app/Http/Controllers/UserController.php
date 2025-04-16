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
            'estatus' => 'activo',
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
        Log::info('Entrando al método solicitarBaja', $request->all());
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
}
