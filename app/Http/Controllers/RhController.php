<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudAlta;
use App\Models\DocumentacionAltas;
use App\Models\SolicitudBajas;
use App\Models\User;
use Carbon\Carbon;
use Hash;

class RhController extends Controller
{
    public function solicitudesAltas(){
        $solicitudes = SolicitudAlta::where('status', 'En Proceso')->get();
        return view('rh.solicitudesAltas', compact('solicitudes'));
    }

    public function detalleSolicitud($id){
        $solicitud = SolicitudAlta::find($id);
        $documentacion = DocumentacionAltas::where('solicitud_id', $id)->first();
        return view('rh.detalleSolicitud', compact('solicitud', 'documentacion'));
    }

    public function aceptarSolicitud($id){
        $solicitud = SolicitudAlta::find($id);
        $solicitud->status = 'Aceptada';
        $solicitud->observaciones = 'Alta Aprobada';
        $solicitud->save();

        $docs = DocumentacionAltas::where('solicitud_id', $id)->first();

        $idDocs = $docs->id;
        $idSol= $solicitud->id;

        $user = new User();
        $user->sol_alta_id = $idSol;
        $user->sol_docs_id = $idDocs;
        $user->name = $solicitud->nombre . " " . $solicitud->apellido_paterno . " " . $solicitud->apellido_materno;
        $user->email = $solicitud->email;
        $user->password = Hash::make($solicitud->rfc);
        $user-> fecha_ingreso = Carbon::now();
        $user->punto = $solicitud->punto;
        $user->rol = $solicitud->rol;
        $user->estatus = 'Activo';
        $user->empresa = $solicitud->empresa;
        $user->save();


        return redirect()->route('rh.solicitudesAltas')->with('success', 'Solicitud respondida correctamente.');
    }

    public function enviarObservacion(Request $request, $id){
        $request->validate([
            'observacion' => 'required|string|max:1000'
        ]);

        $solicitud = SolicitudAlta::find($id);
        $solicitud->observaciones = $request->observacion;
        $solicitud->save();

        return redirect()->route('rh.detalleSolicitud', $id)->with('success', 'Observación enviada correctamente.');
    }

    public function rechazarSolicitud($id){
        $solicitud = SolicitudAlta::find($id);
        $solicitud->status = 'Rechazada';
        $solicitud->observaciones = 'Solicitud no aprobada.';
        $solicitud->save();

        return redirect()->route('rh.solicitudesAltas')->with('success', 'Solicitud rechazada correctamente.');
    }

    public function historialSolicitudesAltas(){
        $solicitudes = SolicitudAlta::all();
        return view('rh.historialSolicitudesAltas', compact('solicitudes'));
    }

    public function solicitudesBajas(){
        $solicitudes = SolicitudBajas::with('user.solicitudAlta')
        ->where('estatus', 'En Proceso')
        ->where('por', 'Renuncia')
        ->get();
        return view('rh.solicitudesBajas', compact('solicitudes'));
    }

    public function historialSolicitudesBajas(){
        $solicitudes = SolicitudBajas::with('user.solicitudAlta')
        ->where('por', 'Renuncia')
        ->orderBy('fecha_solicitud', 'desc')
        ->get();

        return view('rh.historialSolicitudesBajas', compact('solicitudes'));
    }

    public function detalleSolicitudBaja($id){
        $solicitud = SolicitudBajas::find($id);
        $userId = $solicitud->user_id;
        $user = User::find($userId);


        $solicitudAlta = SolicitudAlta::find($user->sol_alta_id);
        $documentacion = DocumentacionAltas::where('solicitud_id', $user->sol_alta_id)->first();
        return view('rh.detalleSolicitudBaja', compact('solicitud', 'user', 'documentacion','solicitudAlta'));
    }

    public function rechazarBaja($id){
        $solicitud = SolicitudBajas::find($id);
        $solicitud->estatus = 'Rechazada';
        $solicitud->observaciones = 'Solicitud no aprobada.';
        $solicitud->save();
        return redirect()->route('rh.historialSolicitudesBajas')->with('success', 'Solicitud rechazada correctamente.');
    }

    public function aceptarBaja($id){
        $solicitud = SolicitudBajas::find($id);
        $solicitud->estatus = 'Aceptada';
        $solicitud->observaciones = 'Baja de elemento Aprobada.';
        $solicitud->save();

        $userId = $solicitud->user_id;
        $user = User::find($userId);
        $user->estatus = 'Inactivo';
        $user->save();

        return redirect()->route('rh.historialSolicitudesBajas')->with('success', 'Solicitud respondida correctamente.');
    }
}
