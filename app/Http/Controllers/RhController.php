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
        $solicitud->fecha_baja = Carbon::now();
        $solicitud->save();

        $userId = $solicitud->user_id;
        $user = User::find($userId);
        $user->estatus = 'Inactivo';
        $user->save();

        return redirect()->route('rh.historialSolicitudesBajas')->with('success', 'Solicitud respondida correctamente.');
    }

    public function generarNuevaAltaForm(){
        return view('rh.generarAlta');
    }
    public function guardarAlta(Request $request){
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'apellido_paterno' => 'required|string|max:255',
                'apellido_materno' => 'required|string|max:255',
                'fecha_nacimiento' => 'required|date',
                'curp' => 'required|string|max:255',
                'nss' => 'required|string|max:255',
                'edo_civil' => 'required|string',
                'rfc' => 'required|string|max:255',
                'telefono' => 'required|string|max:255',
                'calle' => 'required|string|max:255',
                'num_ext' => 'required|integer',
                'colonia' => 'required|string|max:255',
                'ciudad' => 'required|string|max:255',
                'estado' => 'required|string|max:255',
                'rol' => 'required|string|max:255',
                'punto' => 'required|string|max:255',
                'empresa' => 'required|string',
                'email' => 'required|email|unique:solicitud_altas,email',
            ]);

            $solicitud = new SolicitudAlta();
            $solicitud->solicitante = auth()->user()->name;
            $solicitud->nombre = $request->name;
            $solicitud->apellido_paterno = $request->apellido_paterno;
            $solicitud->apellido_materno = $request->apellido_materno;
            $solicitud->fecha_nacimiento = $request->fecha_nacimiento;
            $solicitud->curp = $request->curp;
            $solicitud->nss = $request->nss;
            $solicitud->estado_civil = $request->edo_civil;
            $solicitud->rfc = $request->rfc;
            $solicitud->telefono = $request->telefono;
            $solicitud->domicilio_calle = $request->calle;
            $solicitud->domicilio_numero = $request->num_ext;
            $solicitud->domicilio_colonia = $request->colonia;
            $solicitud->domicilio_ciudad = $request->ciudad;
            $solicitud->domicilio_estado = $request->estado;
            $solicitud->rol = $request->rol;
            $solicitud->punto = $request->punto;
            $solicitud->empresa = $request->empresa;
            $solicitud->email = $request->email;
            $solicitud->estatura = '0.0';
            $solicitud->peso = '0.0';
            $solicitud->status = 'En Proceso';
            $solicitud->observaciones = 'Solicitud en revisión';

            $solicitud->save();

            return redirect()->route('rh.subirArchivosAltaForm', ['id' => $solicitud->id]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al guardar la solicitud, intente nuevamente.');
        }
    }
    public function subirArchivosAltaForm($id){
        $solicitud = SolicitudAlta::find($id);
        return view('rh.subirArchivosAlta', compact('solicitud'));
    }

    public function guardarArchivosAlta(Request $request, $id){
        $request->validate([
            'arch_acta_nacimiento' => 'required|file',
            'arch_curp' => 'required|file',
            'arch_ine' => 'required|file',
            'arch_comprobante_domicilio' => 'required|file',
            'arch_rfc' => 'required|file',
            'arch_comprobante_estudios' => 'required|file',
            'arch_foto' => 'required|file',
        ]);
        $solicitud = SolicitudAlta::find($id);
        $solicitudId = $id;
        $documentacion = DocumentacionAltas::firstOrNew(['solicitud_id' => $solicitudId]);

        $carpeta = 'solicitudesAltas/' . $solicitudId;

        $archivos = [
            'arch_acta_nacimiento',
            'arch_curp',
            'arch_ine',
            'arch_comprobante_domicilio',
            'arch_rfc',
            'arch_comprobante_estudios',
            'arch_carta_rec_laboral',
            'arch_carta_rec_personal',
            'arch_cartilla_militar',
            'arch_infonavit',
            'arch_fonacot',
            'arch_licencia_conducir',
            'arch_carta_no_penales',
            'arch_foto',
            'visa',
            'pasaporte',
        ];

        foreach ($archivos as $campo) {
            if ($request->hasFile($campo)) {
                $archivo = $request->file($campo);
                $nombreArchivo = $campo . '.' . $archivo->getClientOriginalExtension();
                $ruta = $archivo->storeAs($carpeta, $nombreArchivo, 'public');

                $documentacion->$campo = 'storage/' . $ruta;
            }
        }

        $documentacion->solicitud_id = $solicitudId;
        $documentacion->save();

        $solicitud->status = 'Aceptada';
        $solicitud->observaciones = 'Alta Aprobada';
        $solicitud->save();

        $idDocs = $documentacion->id;

        $user = new User();
        $user->sol_alta_id = $solicitudId;
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


        return redirect()->route('rh.generarNuevaAltaForm')->with('success', 'Documentación subida correctamente');
    }

    public function generarNuevaBajaForm(Request $request){
        $busqueda = $request->input('busqueda');

        $usuarios = User::when($busqueda, function ($query, $busqueda) {
            return $query->where('name', 'like', "%{$busqueda}%");
        })->orderBy('name')->paginate(10);

        return view('rh.generarBaja', compact('usuarios'));
    }

    public function llenarBaja($id){
        $user = User::find($id);
        $solicitud = SolicitudAlta::find($user->sol_alta_id);
        $solicitudpendiente = SolicitudBajas::where('user_id', $user->id)->where('estatus', 'En Proceso')->first();

        return view('rh.llenarBaja', compact('user','solicitud','solicitudpendiente'));
    }

    public function almacenarBaja(Request $request, $id){
        $request->validate([
            'fecha_hoy' => 'required|date',
            'incapacidad' => 'nullable|string|max:255',
            'por' => 'required|in:Ausentismo,Separación Voluntaria,Renuncia',
            'ultima_asistencia' => 'nullable|date',
            'motivo' => 'nullable|string',
        ]);

            $user = User::findOrFail($id);

            $solicitud = new SolicitudBajas();
            $solicitud->user_id = $user->id;
            $solicitud->fecha_solicitud = $request->fecha_hoy;
            $solicitud->motivo = $request->motivo;
            $solicitud->incapacidad = $request->incapacidad;
            $solicitud->por = $request->por;
            $solicitud->ultima_asistencia = $request->ultima_asistencia;
            if ($request->por == 'Renuncia') {
            $solicitud->estatus = 'Aceptada';
            $solicitud->observaciones = 'Baja de elemento Aprobada.';
            $solicitud->fecha_baja = Carbon::now();
            $user->estatus = 'Inactivo';
            $user->save();
            } else {
                $solicitud->estatus = 'En Proceso';
                $solicitud->observaciones = 'Solicitud en revisión';
            }

            try {
                $solicitud->save();
            } catch (\Exception $e) {
                return redirect()->route('dashboard')->with('error', 'Error al enviar la solicitud.');
            }

            return redirect()->route('dashboard')->with('success', 'Baja de usuario realizada correctamente.');
    }
}
