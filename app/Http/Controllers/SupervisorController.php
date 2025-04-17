<?php

namespace App\Http\Controllers;

use App\Models\SolicitudAlta;
use App\Models\DocumentacionAltas;
use App\Models\SolicitudBajas;
use App\Models\User;
use App\Models\Asistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class SupervisorController extends Controller
{
    public function nuevoUsuarioForm(){
        return view('supervisor.nuevoUsuarioForm');
    }

    public function guardarInfo(Request $request)
    {
        //dd($request->all());
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



            //session(['user_id' => $solicitud->id]);

            return redirect()->route('sup.subirArchivosForm', ['id' => $solicitud->id]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al guardar la solicitud: ' . $e->getMessage());
        }
    }

    public function subirArchivosForm($id)
    {
        $solicitud = SolicitudAlta::findOrFail($id);
        return view('supervisor.subirArchivosForm', compact('solicitud'));
    }

    public function guardarArchivos(Request $request, $id)
    {
        $request->validate([
            'arch_acta_nacimiento' => 'required|file',
            'arch_curp' => 'required|file',
            'arch_ine' => 'required|file',
            'arch_comprobante_domicilio' => 'required|file',
            'arch_rfc' => 'required|file',
            'arch_comprobante_estudios' => 'required|file',
            'arch_foto' => 'required|file',
        ]);

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

        return redirect()->route('sup.nuevoUsuarioForm')->with('success', 'Documentación subida correctamente');
    }

    public function historialSolicitudes()
    {
        $usuario = auth()->user()->name;
        $solicitudes = SolicitudAlta::where('solicitante', $usuario)->orderBy('created_at', 'desc')->get();

        return view('supervisor.historialSolicitudes', compact('solicitudes'));
    }

    public function detalleSolicitud($id){
        $solicitud = SolicitudAlta::find($id);
        $documentacion = DocumentacionAltas::where('solicitud_id', $id)->first();
        return view('supervisor.detalleSolicitud', compact('solicitud', 'documentacion'));
    }

    public function editarSolicitudForm($id){
        $solicitud = SolicitudAlta::find($id);
        $documentacion = DocumentacionAltas::where('solicitud_id', $id)->first();
        return view('supervisor.editarSolicitudForm', compact('solicitud', 'documentacion'));
    }

    public function editarInformacionSolicitud(Request $request, $id){
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
                'email' => 'required|email|unique:solicitud_altas,email,' . $id . ',id',

            ]);

            $solicitud = SolicitudAlta::findOrFail($id);
            $id=$solicitud->id;
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
            $solicitud->observaciones = 'Cambios realizados, en espera de revisión.';

            $solicitud->save();
            $documentacion = DocumentacionAltas::where('solicitud_id', $id)->first();

            return view('supervisor.editarArchivosForm', compact('solicitud','id' ,'documentacion'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar la solicitud: ' . $e->getMessage());
        }
    }

    public function subirArchivosEditados(Request $request, $id)
    {
        $solicitudId = $id;
        $sol = SolicitudAlta::find($solicitudId);
        $documentacion = DocumentacionAltas::firstOrNew(['solicitud_id' => $solicitudId]);
        $carpeta = 'solicitudesAltas/' . $solicitudId;

        $camposArchivos = [
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

        foreach ($camposArchivos as $campo) {
            if ($request->hasFile($campo)) {
                $nuevoArchivo = $request->file($campo);
                $nombreArchivo = $campo . '.' . $nuevoArchivo->getClientOriginalExtension();
                $rutaCompleta = $carpeta . '/' . $nombreArchivo;

                if (!empty($documentacion->$campo)) {
                    $rutaAnterior = str_replace('storage/', '', $documentacion->$campo);
                    if (Storage::disk('public')->exists($rutaAnterior)) {
                        Storage::disk('public')->delete($rutaAnterior);
                    }
                }

                $nuevoArchivo->storeAs($carpeta, $nombreArchivo, 'public');
                $documentacion->$campo = 'storage/' . $rutaCompleta;
            }
        }

        $documentacion->solicitud_id = $solicitudId;
        $documentacion->save();

        $sol->observaciones = 'Documentación actualizada, en espera de revisión.';
        $sol->save();

        return redirect()->route('sup.solicitud.detalle', $solicitudId)->with('success', 'Documentos actualizados correctamente.');
    }

    public function solicitarBajaForm()
    {
        $usuario = auth()->user();

        $elementos = User::where('punto', $usuario->punto)
            ->where('empresa', $usuario->empresa)
            ->get();

        return view('supervisor.solicitarBajaForm', compact('elementos'));
    }

    public function solicitarBajaVista($id){
        $user = User::find($id);
        $solicitud = SolicitudAlta::where('id', $user->sol_alta_id)->first();
        $solicitudpendiente = SolicitudBajas::where('user_id', $user->id)->where('estatus', 'En Proceso')->first();
        return view('supervisor.vistaSolicitarBaja', compact('user','solicitud','solicitudpendiente'));
    }

    public function guardarBajaNueva(Request $request, $id){
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
        $solicitud->estatus = 'En Proceso';
        $solicitud->observaciones = 'Solicitud de baja en proceso';

        try {
            $solicitud->save();
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Error al enviar la solicitud: ' . $e->getMessage());
        }

        return redirect()->route('dashboard')->with('success', 'Solicitud de baja enviada correctamente');
    }

    public function historialBajas(){
        $authUser = Auth::user();

    $solicitudes = SolicitudBajas::whereHas('user', function ($query) use ($authUser) {
        $query->where('empresa', $authUser->empresa)
            ->where('punto', $authUser->punto);
            })->with('user')->get();

        return view('supervisor.historialBajas', compact('solicitudes'));
    }

    public function listaAsistencia(){
        $user = Auth::user();
        $asistencia_hoy = Asistencia::where('fecha', Carbon::now()->toDateString());
        $elementos = User::where('punto', $user->punto)
            ->where('empresa', $user->empresa)
            ->where('estatus', 'Activo')
            ->where('rol', '!=', 'Supervisor')
            ->with('solicitudAlta.documentacion')
            ->get();
        return view('supervisor.listaAsistencia', compact('elementos', 'asistencia_hoy'));
    }

    public function guardarAsistencias(Request $request){
        $user = Auth::user();
        $now = Carbon::now('America/Mexico_City');

        $asistencia = new Asistencia();
        $asistencia->user_id = $user->id;
        $asistencia->fecha = $now->toDateString();
        $asistencia->hora_asistencia = $now->toTimeString();
        $asistencia->elementos_enlistados =  json_encode($request->input('asistencias', []));
        $asistencia->observaciones = $request->input('observaciones') ?: 'Ninguna';
        $asistencia->punto = $user->punto;
        $asistencia->empresa = $user->empresa;
        $asistencia->save();

        return redirect()->route('dashboard')->with('success', 'Asistencia registrada correctamente');
    }

    public function verAsistencias(){
        $user = Auth::user();
        $asistencias = Asistencia::where('user_id', $user->id)
            ->orderBy('fecha', 'desc')
            ->get();

            $asistenciasElementos = $asistencias->map(function ($asistencia) {
                $ids = json_decode($asistencia->elementos_enlistados, true);

                $usuarios = User::whereIn('id', $ids ?: [])->with('solicitudAlta.documentacion')->get();
                $asistencia->usuarios_enlistados = $usuarios;
                return $asistencia;
            });

        return view('supervisor.verAsistencias', compact('asistencias', 'asistenciasElementos'));
    }

    public function verFechaAsistencias(Request $request)
    {
        $user = Auth::user();
        $fechaSeleccionada = $request->input('fecha');

        $query = Asistencia::where('user_id', $user->id);

        if ($fechaSeleccionada) {
            $query->whereDate('fecha', $fechaSeleccionada);
        }

        $asistencias = $query->orderBy('fecha', 'desc')->get();

        $asistenciasElementos = $asistencias->map(function ($asistencia) {
            $ids = json_decode($asistencia->elementos_enlistados, true);
            $usuarios = User::whereIn('id', $ids ?: [])->with('solicitudAlta.documentacion')->get();
            $asistencia->usuarios_enlistados = $usuarios;
            return $asistencia;
        });

        return view('supervisor.verAsistencias', compact('asistenciasElementos', 'fechaSeleccionada'));
    }


}
