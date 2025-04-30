<?php

namespace App\Http\Controllers;

use App\Models\SolicitudAlta;
use App\Models\DocumentacionAltas;
use App\Models\SolicitudBajas;
use App\Models\SolicitudVacaciones;
use App\Models\User;
use App\Models\Asistencia;
use App\Models\TiemposExtra;
use App\Models\CubrirTurno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SupervisorController extends Controller
{
    public function nuevoUsuarioForm(){
        return view('supervisor.nuevoUsuarioForm');
    }

    public function guardarInfo(Request $request)
    {
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

            return redirect()->route('sup.subirArchivosForm', ['id' => $solicitud->id]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al guardar la solicitud, intente nuevamente.');
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


        $solicitud = SolicitudAlta::find($solicitudId);

        if(Auth::user()->rol == 'admin'){
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

            $solicitud->status = 'Aceptada';
            $solicitud->observaciones = 'Solicitud Aceptada.';
            $solicitud->save();
        }

        return redirect()->route('sup.nuevoUsuarioForm')->with('success', 'Documentación subida correctamente');
    }

    public function historialSolicitudes()
    {
        $usuario = Auth::user();
        if($usuario->rol == 'Supervisor')
        {
            $solicitudes = SolicitudAlta::where('solicitante', $usuario)
            ->orderBy('created_at', 'desc')
            ->get();
        }else{
            $solicitudes = SolicitudAlta::orderBy('created_at', 'desc')
            ->get();
        }
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
                'nss' => ['required', 'digits:11'],
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
            if(Auth::user()->rol == 'admin'){
                $solicitud->status = 'Aceptada';
                $solicitud->observaciones = 'Solicitud Aceptada.';
            }else{
                $solicitud->status = 'En Proceso';
                $solicitud->observaciones = 'Cambios realizados, en espera de revisión.';
            }
            $solicitud->save();
            $user = User::where('sol_alta_id', $id)->first();

            $documentacion = DocumentacionAltas::where('solicitud_id', $id)->first();
            if(Auth()->user()->rol == 'admin'){
                $user = User::where('sol_alta_id', $id)->first();
                $user->name = $solicitud->nombre . " " . $solicitud->apellido_paterno . " " . $solicitud->apellido_materno;
                $user->email = $solicitud->email;
                $user->punto = $solicitud->punto;
                $user->rol = $solicitud->rol;
                $user->empresa = $solicitud->empresa;

                $user->save();
            }
            return view('supervisor.editarArchivosForm', compact('solicitud','id' ,'documentacion', 'user'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar la solicitud: ' . $e->getMessage());
        }
    }

    public function subirArchivosEditados(Request $request, $id)
    {
        $solicitudId = $id;
        $sol = SolicitudAlta::find($solicitudId);
        $user = User::where('sol_alta_id', $solicitudId)->first();
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
        if(Auth()->user()->rol == 'admin')
            $sol->observaciones = 'Solicitud Aceptada.';
        else
            $sol->observaciones = 'Documentación actualizada, en espera de revisión.';
        $sol->save();
        if(Auth()->user()->rol == 'admin')
            return redirect()->route('user.verFicha', $user->id)->with('success', 'Documentos actualizados correctamente.');
        else
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
        $user = Auth::user();

    $solicitudes = SolicitudBajas::whereHas('user', function ($query) use ($user) {
        $query->where('empresa', $user->empresa)
            ->where('punto', $user->punto)
            ->where('por','Renuncia');
            })->with('user')->get();

        return view('supervisor.historialBajas', compact('solicitudes'));
    }

    public function listaAsistencia(){
        $user = Auth::user();

        $asistenciasHoy = 0;
        $supervisores = User::where('rol', 'Supervisor')->get();

        $supervisores->map(function ($supervisor) {
            $supervisor->envio_asistencia = Asistencia::where('user_id', $supervisor->id)
                ->whereDate('fecha', Carbon::today())
                ->exists() ? 'Sí' : 'No';

            return $supervisor;
        });

        $asistencia_hoy = Asistencia::where('fecha', Carbon::now()->toDateString())
                        ->where('user_id', $user->id)
                        ->get();

        $elementos = User::where('punto', $user->punto)
            ->where('empresa', $user->empresa)
            ->where('estatus', 'Activo')
            ->where('rol', '!=', 'Supervisor')
            ->with('solicitudAlta.documentacion')
            ->get();
        return view('supervisor.listaAsistencia', compact('elementos', 'asistencia_hoy', 'supervisores'));
    }

    public function guardarAsistencias(Request $request)
    {
        $validated = $request->validate([
            'asistencias' => 'required|array',
            'asistencias.*' => 'integer',
            'foto_evidencia' => 'nullable|array',
            'foto_evidencia.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'observaciones' => 'nullable|string|max:255'
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $now = now('America/Mexico_City');

            $asistencias = $request->input('asistencias', []);
            $todosUsuarios = User::where('punto', $user->punto)
                            ->where('empresa', $user->empresa)
                            ->where('estatus', 'Activo')
                            ->where('rol', '!=', 'Supervisor')
                            ->pluck('id')
                            ->toArray();

            $faltas = array_values(array_diff($todosUsuarios, $asistencias));

            $fotosAsistentes = [];
            $rutaBase = "asistencias/".Str::slug($user->name)."/".$now->format('Y-m-d');
            Storage::disk('public')->makeDirectory($rutaBase, 0755, true);

            foreach ($request->file('foto_evidencia', []) as $elementoId => $foto) {
                if ($foto && $foto->isValid()) {
                    $extension = $foto->extension();
                    $nombreArchivo = $elementoId.$now->timestamp.'.'.$extension;
                    $rutaCompleta = $foto->storeAs($rutaBase, $nombreArchivo, 'public');
                    $fotosAsistentes[$elementoId] = $rutaCompleta;
                }
            }

            Asistencia::create([
                'user_id' => $user->id,
                'fecha' => $now->toDateString(),
                'hora_asistencia' => $now->toTimeString(),
                'elementos_enlistados' => json_encode($asistencias,true),
                'faltas' => json_encode($faltas, true),
                'observaciones' => $request->input('observaciones') ?: 'Ninguna',
                'punto' => $user->punto,
                'empresa' => $user->empresa,
                'fotos_asistentes' => json_encode($fotosAsistentes, true),
            ]);

            DB::commit();
            return redirect()->route('dashboard')
                ->with('success', 'Asistencia registrada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al guardar asistencia: '.$e->getMessage()."\n".$e->getTraceAsString());

            return back()->withInput()
                ->with('error', 'Error al guardar: '.$e->getMessage());
        }
    }

    public function verAsistencias($id){
        $user = User::find($id);

        if($user->rol == 'Supervisor')
        {
            $asistencias = Asistencia::where('user_id', $user->id)
                ->with('usuario')
                ->orderBy('fecha', 'desc')
                ->get();
        }else{
            $asistencias = Asistencia::orderBy('fecha', 'desc')
                ->with('usuario')
                ->get();
        }

        return view('supervisor.verAsistencias', compact('asistencias', 'user'));
    }

    public function detalleAsistencia($id){
        $asistencia = Asistencia::find($id);
        $idsAsistieron = json_decode($asistencia->elementos_enlistados, true) ?? [];
        $idsFaltaron = json_decode($asistencia->faltas, true) ?? [];

        $usuariosAsistieron = User::whereIn('id', $idsAsistieron)->with('solicitudAlta.documentacion')->get();
        $usuariosFaltaron = User::whereIn('id', $idsFaltaron)->with('solicitudAlta.documentacion')->get();

        $fotos = json_decode($asistencia->fotos_asistentes, true) ?? [];
        if (is_array($fotos)) {
            foreach ($fotos as $id => $path) {
                $fotos[$id] = asset('storage/' . $path);
            }
        }
        $asistencia->usuarios_enlistados = $usuariosAsistieron;
        $asistencia->usuarios_faltantes = $usuariosFaltaron;
        $asistencia->fotos_asistentes = $fotos;

        return view('supervisor.detalleAsistencia', compact('asistencia'));
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

    public function solicitudesVacaciones(){
        $punto = Auth::user()->punto;

        if(Auth::user()->rol == 'admin'){
            $solicitudes = SolicitudVacaciones::where('estatus', 'En Proceso')->get();
        }else{
            $solicitudes = SolicitudVacaciones::whereHas('user', function ($query) use ($punto) {
                $query->where('punto', $punto);
            })->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        }
        return view('supervisor.solicitudesVacaciones', compact('solicitudes'));
    }

    public function aceptarSolicitudVacaciones($id){
        $solicitud = SolicitudVacaciones::find($id);
        $solicitud->estatus = 'Aceptada';
        $solicitud->observaciones = 'Solicitud de vacaciones aceptada';
        $solicitud->autorizado_por = Auth::user()->name;

        $solicitud->save();

        return redirect()->route('sup.solicitudesVacaciones')->with('success', 'Solicitud de vacaciones aceptada correctamente.');
    }

    public function rechazarSolicitudVacaciones($id){
        $solicitud = SolicitudVacaciones::find($id);
        $solicitud->estatus = 'Rechazada';
        $solicitud->observaciones = 'Solicitud de vacaciones rechazada';

        $solicitud->save();

        return redirect()->route('sup.solicitudesVacaciones')->with('success', 'Solicitud de vacaciones rechazada correctamente.');
    }

    public function verSolicitudBaja($id){
        $solicitudBaja = SolicitudBajas::findOrFail($id);
        $user = User::findOrFail($solicitudBaja->user_id);
        $solicitudAlta = SolicitudAlta::findOrFail($user->sol_alta_id);

        return view('supervisor.detalleBaja', compact('user','solicitudAlta', 'solicitudBaja'));
    }

    public function tiemposExtras(){
        $user = Auth::user();
        $elementos = User::where('punto', Auth::user()->punto)
            ->where('empresa', Auth::user()->empresa)
            ->where('estatus', 'Activo')
            ->where('rol', '!=', 'Supervisor')
            ->with('solicitudAlta.documentacion')
            ->get();
        return view('supervisor.tiemposExtras', compact('elementos'));
    }

    public function tiemposExtrasForm($id){
        $supervisor = Auth::user();
        $extraHoy = TiemposExtra::where('user_id', $id)
                ->whereDate('fecha', Carbon::now()->toDateString())
                ->first();
        $elemento = User::where('id', $id)
            ->with('solicitudAlta.documentacion')
            ->firstOrFail();

        $foto = optional($elemento->solicitudAlta->documentacion)->arch_foto;
        $foto = $foto ? asset($foto) : null;
        $solicitud = SolicitudAlta::where('id', $elemento->sol_alta_id)->first();

        return view('supervisor.tiemposExtrasForm', compact('supervisor', 'elemento','solicitud', 'foto', 'extraHoy'));
    }

    public function guardarTiempoExtra(Request $request, $id){
        $request->validate([
            'user_id',
            'fecha' => [
                'required',
                function ($attribute, $value, $fail) {
                    try {
                        Carbon::parse($value, 'America/Mexico_City');
                    } catch (\Exception $e) {
                        $fail('La fecha no es válida.');
                    }
                }
            ],
            'hora_inicio',
            'hora_fin',
            'observaciones',
        ]);

        $horaInicio = Carbon::createFromFormat('H:i', $request->hora_inicio);
        $horaFin = Carbon::createFromFormat('H:i', $request->hora_fin);

        $diferenciaSegundos = abs($horaFin->floatDiffInSeconds($horaInicio));
        $totalHoras = gmdate('H:i:s', $diferenciaSegundos);

        $tiempoExtra = new TiemposExtra();
        $tiempoExtra->user_id = $request->user_id;
        $tiempoExtra->fecha = $request->fecha;
        $tiempoExtra->hora_inicio = $horaInicio;
        $tiempoExtra->hora_fin = $horaFin;
        $tiempoExtra->total_horas = $totalHoras;
        $tiempoExtra->autorizado_por = Auth::user()->name;
        if($request->observaciones){
            $tiempoExtra->observaciones = $request->observaciones;
        }else{
            $tiempoExtra->observaciones = 'Ninguna';
        }
        $tiempoExtra->save();

        return redirect()->route('sup.tiemposExtras')->with('success', 'Tiempo extra registrado correctamente.');
    }

    public function historialTiemposExtras(){
        $supervisor = Auth::user();
        $tiemposExtras = TiemposExtra::whereHas('user', function ($query) use ($supervisor) {
            $query->where('empresa', $supervisor->empresa)
                ->where('punto', $supervisor->punto);
        })
        ->with('user')
        ->orderBy('fecha', 'desc')
        ->get();

        return view('supervisor.historialTiemposExtras', compact('tiemposExtras'));
    }

    public function gestionUsuarios(){
        $user = Auth::user();
        $usuarios = User::where('punto', $user->punto)
            ->where('empresa', $user->empresa)
            ->where('estatus', 'Activo')
            ->where('rol', '!=', 'Supervisor')
            ->get();

        return view('supervisor.gestionUsuarios', compact('usuarios'));
    }

    public function coberturaTurnoForm($id){
        $elemento = User::find($id);
        $hoy = Carbon::now('America/Mexico_City')->toDateString();
        $solicitud = SolicitudAlta::where('id', $elemento->sol_alta_id)->first();
        $coberturaHoy = CubrirTurno::where('user_id', $id)
            ->where('fecha',$hoy)->first();
        $cobertura = 0;
        $coberturaHoy ? $cobertura = 1 : $cobertura = 0;

        return view('supervisor.coberturaTurnoForm', compact('elemento','solicitud', 'cobertura'));
    }

    public function guardarCoberturaTurno(Request $request, $id){
        $request->validate([
            'user_id',
            'fecha',
            'hora_inicio',
            'hora_fin',
            'punto_procedencia',
            'punto_cobertura',
            'observaciones',
        ]);

        $user_cubre = User::find($id);
        $fecha = Carbon::createFromFormat('Y-m-d', $request->fecha);
        $horaInicio = Carbon::createFromFormat('H:i', $request->hora_inicio);
        $horaFin = Carbon::createFromFormat('H:i', $request->hora_fin);

        $turno = new CubrirTurno();
        $turno->user_id = $user_cubre->id;
        $turno->fecha = $fecha;
        $turno->hora_inicio = $horaInicio;
        $turno->hora_fin = $horaFin;
        $turno->punto_procedencia = $request->punto_procedencia;
        $turno->punto_cobertura = $request->punto_cobertura;
        $turno->autorizado_por = Auth::user()->id;
        $turno->observaciones = $request->observaciones;
        $turno->save();

        return redirect()->route('sup.tiemposExtras')->with('success', 'Cobertura de turno registrada correctamente.');
    }

}
