<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SolicitudAlta;
use App\Models\SolicitudBajas;
use App\Models\DocumentacionAltas;
use App\Models\User;
use App\Models\BuzonQueja;
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

    public function bajaUsuario($id){
        $user = User::find($id);
        $user->estatus = 'Inactivo';
        $user->save();

        $solicitud = new SolicitudBajas();
        $solicitud->user_id = $id;
        $solicitud->fecha_solicitud = Carbon::today();
        $solicitud->motivo = 'Desconocido';
        $solicitud->por = 'Desconocido';
        $solicitud->incapacidad = '';
        $solicitud->fecha_baja = Carbon::today();
        $solicitud->observaciones = 'Baja realizada por Administrador.';
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

    public function darReingreso($id){
        $user = User::find($id);
        $user->estatus = 'Activo';
        $fechaHoy = \Carbon\Carbon::today('America/Mexico_City')->toDateString();

        $reingresoTexto = $user->solicitudAlta->reingreso;

        if (is_null($reingresoTexto) || trim($reingresoTexto) === '' || $reingresoTexto === 'NO') {
            $user->solicitudAlta->reingreso = "Reingreso 1: $fechaHoy";
        } else {
            preg_match_all('/Reingreso \d+:/', $reingresoTexto, $coincidencias);
            $reingresosHechos = count($coincidencias[0]);

            $nuevoNumero = $reingresosHechos + 1;
            $user->solicitudAlta->reingreso .= " Reingreso $nuevoNumero: $fechaHoy";
        }
        $user->solicitudAlta->save();
        $user->save();

        return redirect()->back()->with('success', 'El usuario ha sido dado de alta correctamente.');
    }
}
