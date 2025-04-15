<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudAlta;
use App\Models\DocumentacionAltas;
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
        $solicitud->status = 'Alta Aprobada';
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
}
