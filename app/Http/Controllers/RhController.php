<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use setasign\Fpdi\Fpdi;
use Mpdf\Mpdf;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\SolicitudAlta;
use App\Models\SolicitudVacaciones;
use App\Models\DocumentacionAltas;
use App\Models\SolicitudBajas;
use App\Models\User;
use App\Models\Punto;
use Carbon\Carbon;
use Hash;

class RhController extends Controller
{
    public function solicitudesAltas(){
        $solicitudes = SolicitudAlta::where('status', 'En Proceso')
            ->where('Observaciones', '!=', 'Solicitud enviada a Administrador.')
            ->get();
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
        $solicitud->observaciones = 'Solicitud Aceptada.';
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

    public function solicitudesBajas() {
        $solicitudes = SolicitudBajas::with('user.solicitudAlta')
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('estatus', 'En Proceso')
                    ->where('por', 'Renuncia');
                })->orWhere(function ($q) {
                    $q->where('estatus', 'Aceptada')
                    ->where('observaciones', 'Finiquito enviado a RH.');
                });
            })
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

    public function detalleSolicitudBaja($id)
{
    $solicitud = SolicitudBajas::find($id);
    $userId = $solicitud->user_id;
    $user = User::find($userId);
    $dias = 0;
    $diasDisponibles = 0;

    $antiguedad = (int) floor(Carbon::parse($user->fecha_ingreso)->floatDiffInYears(now()));

    if ($antiguedad < 2) {
        $dias = 12;
    } elseif ($antiguedad == 2) {
        $dias = 14;
    } elseif ($antiguedad == 3) {
        $dias = 16;
    } elseif ($antiguedad == 4) {
        $dias = 18;
    } elseif ($antiguedad == 5) {
        $dias = 20;
    } elseif ($antiguedad > 5 && $antiguedad <= 10) {
        $dias = 22;
    } elseif ($antiguedad > 10 && $antiguedad <= 15) {
        $dias = 24;
    } elseif ($antiguedad > 15 && $antiguedad <= 20) {
        $dias = 26;
    } elseif ($antiguedad > 20 && $antiguedad <= 25) {
        $dias = 28;
    } elseif ($antiguedad > 25 && $antiguedad <= 30) {
        $dias = 30;
    } else {
        $dias = 32;
    }

    $diasDisponibles = $dias;
    $diasUtilizados = 0;
    $fechaIngreso = Carbon::parse($user->fecha_ingreso);
    $hoy = now('America/Mexico_City');

    $aniversario = Carbon::createFromDate(
        $hoy->year,
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

    $inicioAño = Carbon::create($hoy->year, 1, 1);

    if ($fechaIngreso->year < $hoy->year) {
        $diasTrabajadosAnio = ceil($inicioAño->diffInDays($hoy));
    } else {
        $diasTrabajadosAnio = ceil($fechaIngreso->diffInDays($hoy));
    }

    $diasVacacionesProporcionales = round(($dias * $diasTrabajadosAnio) / 365, 2);
    $aguinaldoProporcional = round((15 * $diasTrabajadosAnio) / 365, 2);
    $primaVacacional = round($diasVacacionesProporcionales * 0.25, 2);

    $solicitudAlta = SolicitudAlta::find($user->sol_alta_id);
    $documentacion = DocumentacionAltas::where('solicitud_id', $user->sol_alta_id)->first();

    return view('rh.detalleSolicitudBaja', compact(
        'solicitud',
        'user',
        'documentacion',
        'solicitudAlta',
        'dias',
        'diasDisponibles',
        'diasTrabajadosAnio',
        'diasVacacionesProporcionales',
        'aguinaldoProporcional',
        'primaVacacional'
    ));
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
        $puntos = Punto::with('subpuntos')->get();
        return view('rh.generarAlta', compact('puntos'));
    }
    public function formAlta(Request $request)
    {
        $puntos = Punto::with('subpuntos')->get();
        $tipo = $request->get('tipo', 'oficina');
        return view('rh.generarAlta', compact('tipo', 'puntos'));
    }


    public function guardarAlta(Request $request){
        try {
            $validated = $request->validate([
                'tipo' => 'required|in:oficina,armado,noarmado',
                'name' => 'nullable|string|max:255',
                'apellido_paterno' => 'nullable|string|max:255',
                'apellido_materno' => 'nullable|string|max:255',
                'fecha_nacimiento' => 'nullable|date',
                'curp' => 'nullable|string|max:255',
                'nss' => 'nullable|string|max:255',
                'edo_civil' => 'nullable|string',
                'rfc' => 'nullable|string|max:255',
                'telefono' => 'nullable|string|max:255',
                'calle' => 'nullable|string|max:255',
                'num_ext' => 'nullable|string|max:255',
                'colonia' => 'nullable|string|max:255',
                'ciudad' => 'nullable|string|max:255',
                'peso' => 'nullable|string|max:255',
                'estatura' => 'nullable|string|max:255',
                'cp_fiscal' => 'nullable|string|max:255',
                'estado' => 'nullable|string|max:255',
                'liga_rfc' => 'nullable|string|max:255',
                'infonavit' => 'nullable|string|max:255',
                'fonacot' => 'nullable|string|max:255',
                'domicilio_comprobante' => 'nullable|string|max:255',
                'departamento' => 'nullable|string|max:255',
                'rol' => 'nullable|string|max:255',
                'reingreso' => 'nullable|string',
                'punto' => 'nullable|string|max:255',
                'empresa' => 'nullable|string',
                'sueldo_mensual' => 'nullable|string',
                'fecha_ingreso' => 'nullable|date',
                'email' => 'nullable|email|unique:solicitud_altas,email',
            ]);
            $tipoSeleccionado = $request->get('tipo', 'oficina');

            $solicitud = new SolicitudAlta();
            $solicitud->solicitante = auth()->user()->name;
            $solicitud->nombre = $request->name;
            $solicitud->apellido_paterno = $request->apellido_paterno;
            $solicitud->apellido_materno = $request->apellido_materno;
            $solicitud->fecha_nacimiento = $request->fecha_nacimiento;
            $solicitud->tipo_empleado = $request->get('tipo', 'oficina');
            $solicitud->curp = $request->curp;
            $solicitud->nss = $request->nss;
            $solicitud->estado_civil = $request->edo_civil;
            $solicitud->rfc = $request->rfc;
            $solicitud->telefono = $request->telefono;
            $solicitud->domicilio_calle = $request->calle;
            $solicitud->domicilio_numero = $request->num_ext;
            $solicitud->domicilio_colonia = $request->colonia;
            $solicitud->cp_fiscal = $request->cp_fiscal;
            $solicitud->domicilio_ciudad = $request->ciudad;
            $solicitud->peso = $request->peso;
            $solicitud->estatura = $request->estatura;
            $solicitud->liga_rfc = $request->liga_rfc;
            $solicitud->domicilio_estado = $request->estado;
            $solicitud->infonavit = $request->infonavit;
            $solicitud->fonacot = $request->fonacot;
            $solicitud->domicilio_comprobante = $request->domicilio_comprobante;
            $solicitud->rol = $request->rol;
            $solicitud->punto = $request->punto;
            $solicitud->reingreso = $request->reingreso;
            $solicitud->empresa = $request->empresa;
            $solicitud->fecha_ingreso = $request->fecha_ingreso;
            $solicitud->sueldo_mensual = $request->sueldo_mensual;
            $solicitud->email = $request->email;
            $solicitud->status = 'Aceptada';
            $solicitud->observaciones = 'Solicitud Aceptada.';
            $solicitud->created_at = Carbon::now('America/Mexico_City');
            $solicitud->updated_at = Carbon::now('America/Mexico_City');

            $solicitud->save();

            return redirect()->route('rh.subirArchivosAltaForm', ['id' => $solicitud->id, 'tipo' => $tipoSeleccionado]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al guardar la solicitud, intente nuevamente.'. $e->getMessage());
        }
    }
    public function subirArchivosAltaForm($id){
        $tipo = request('tipo');
        $solicitud = SolicitudAlta::find($id);
        $fecha_ingreso = request('fecha_ingreso');
        return view('rh.subirArchivosAlta', compact('solicitud', 'tipo', 'fecha_ingreso'));
    }

public function guardarArchivosAlta(Request $request, $id)
{
    try {
        $request->validate([
            'arch_acta_nacimiento' => 'nullable|file',
            'arch_curp' => 'nullable|file',
            'arch_ine' => 'nullable|file',
            'arch_comprobante_domicilio' => 'nullable|file',
            'arch_rfc' => 'nullable|file',
            'arch_comprobante_estudios' => 'nullable|file',
            'arch_carta_rec_laboral' => 'nullable|file',
            'arch_carta_rec_personal' => 'nullable|file',
            'arch_cartilla_militar' => 'nullable|file',
            'arch_infonavit' => 'nullable|file',
            'arch_fonacot' => 'nullable|file',
            'arch_licencia_conducir' => 'nullable|file',
            'arch_carta_no_penales' => 'nullable|file',
            'arch_solicitud_empleo' => 'nullable|file',
            'arch_antidoping' => 'nullable|file',
            'arch_nss' => 'nullable|file',
            'arch_contrato' => 'nullable|file',
            'arch_foto' => 'nullable|file',
            'visa' => 'nullable|file',
            'pasaporte' => 'nullable|file',
        ]);

        $solicitud = SolicitudAlta::findOrFail($id);
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
            'arch_nss',
            'arch_contrato',
            'arch_antidoping',
            'arch_solicitud_empleo',
            'visa',
            'pasaporte',
        ];

        foreach ($archivos as $campo) {
            if ($request->hasFile($campo)) {
                try {
                    $archivo = $request->file($campo);
                    $nombreArchivo = $campo . '.' . $archivo->getClientOriginalExtension();
                    $ruta = $archivo->storeAs($carpeta, $nombreArchivo, 'public');
                    $documentacion->$campo = 'storage/' . $ruta;
                } catch (\Exception $e) {
                    Log::error("Error al guardar el archivo {$campo}: " . $e->getMessage());
                }
            }
        }

        $documentacion->solicitud_id = $solicitudId;
        $documentacion->save();

        $solicitud->status = 'Aceptada';
        $solicitud->observaciones = 'Solicitud Aceptada.';
        $solicitud->save();

        if(Auth::user()->rol != 'Supervisor' && Auth::user()->rol != 'SUPERVISOR'){
            $user = new User();
            $user->sol_alta_id = $solicitudId;
            $user->sol_docs_id = $documentacion->id;
            $user->name = $solicitud->nombre . " " . $solicitud->apellido_paterno . " " . $solicitud->apellido_materno;
            $user->email = $solicitud->email;
            if(!empty($solicitud->rfc))
                $user->password = Hash::make($solicitud->rfc);
            else
                $user->password = Hash::make($solicitud->curp);

            $user->fecha_ingreso = $solicitud->fecha_ingreso;
            $user->fecha_ingreso = $solicitud->fecha_ingreso;
            $user->punto = $solicitud->punto;
            $user->rol = $solicitud->rol;
            $user->estatus = 'Activo';
            $user->empresa = $solicitud->empresa;

            $user->save();
        }

        return redirect()->route('dashboard')->with('success', 'Documentación subida correctamente');
    } catch (\Throwable $e) {
        Log::error("Error general en guardarArchivosAlta: " . $e->getMessage());
        return redirect()->back()->with('error', 'Ocurrió un error al guardar los archivos. Verifica el log para más detalles.');
    }
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

    public function almacenarBaja(Request $request, $id)
{
    $request->validate([
        'fecha_hoy' => 'required|date',
        'incapacidad' => 'nullable|string|max:255',
        'por' => 'required|in:Ausentismo,Separación Voluntaria,Renuncia',
        'ultima_asistencia' => 'nullable|date',
        'motivo' => 'nullable|string',
        'adelanto_nomina' => 'nullable|string',
        'descuento' => 'nullable|string',
        'archivo_baja' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'arch_equipo_entregado' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'arch_renuncia' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
    ]);

    $user = User::findOrFail($id);

    $solicitud = new SolicitudBajas();
    $solicitud->user_id = $user->id;
    $solicitud->fecha_solicitud = $request->fecha_hoy;
    $solicitud->motivo = $request->motivo;
    $solicitud->adelanto_nomina = $request->adelanto_nomina;
    $solicitud->descuento = $request->descuento;
    $solicitud->incapacidad = $request->incapacidad;
    $solicitud->por = $request->por;
    $solicitud->ultima_asistencia = $request->ultima_asistencia;
    if((Auth::user()->rol=='admin' || Auth::user()->rol == 'AUXILIAR RECURSOS HUMANOS' || Auth::user()->rol == 'AUXILIAR RH' || Auth::user()->solicitudAlta->rol == 'AUXILIAR RH' || Auth::user()->solicitudAlta->rol == 'AUXILIAR RECURSOS HUMANOS') && $solicitud->por == 'Renuncia'){
        $solicitud->estatus = 'Aceptada';
        $solicitud->observaciones = 'Baja de elemento Aprobada.';
        $solicitud->fecha_baja = $request->fecha_hoy;
        $solicitud->save();

        $userId = $solicitud->user_id;
        $user = User::find($userId);
        $user->estatus = 'Inactivo';
        $user->save();
    }else{
        $solicitud->estatus = 'En Proceso';
        $solicitud->observaciones = 'Solicitud en revisión';
        $solicitud->fecha_baja = Carbon::now('America/Mexico_City');
    }

    try {
        $solicitud->save();
    } catch (\Exception $e) {
        return redirect()->route('dashboard')->with('error', 'Error al enviar la solicitud.');
    }

    $carpeta = 'solicitudesBajas/' . $solicitud->id;
    Storage::disk('public')->makeDirectory($carpeta);

    $archivos = [
        'archivo_baja',
        'arch_equipo_entregado',
        'arch_renuncia'
    ];

    foreach ($archivos as $campo) {
        if ($request->hasFile($campo)) {
            $archivo = $request->file($campo);
            $nombre = $campo . '_' . time() . '.' . $archivo->getClientOriginalExtension();
            $ruta = $archivo->storeAs($carpeta, $nombre, 'public');
            $solicitud->$campo = $ruta;
        }
    }

    if ($solicitud->arch_renuncia !== null) {
        $user->estatus = 'Inactivo';
        $user->save();
    }

    $solicitud->save();

    return redirect()->route('dashboard')->with('success', 'Baja de usuario realizada correctamente.');
}


    public function verArchivos(){
        return view('rh.archivos');
    }

    public function vistaVacaciones(){
        $solicitudes = SolicitudVacaciones::where('estatus', 'En Proceso')
            ->where('observaciones', 'Solicitud aceptada, falta subir archivo de solicitud.')
            ->paginate(10);
        return view('rh.vistaVacaciones', compact('solicitudes'));
    }

    public function historialVacaciones(){
        $solicitudes = SolicitudVacaciones::orderBy('fecha_inicio', 'desc')
            ->paginate(10);
        return view('rh.historialVacaciones', compact('solicitudes'));
    }

    public function exportFichaTecnica($id)
    {
        $user = User::findOrFail($id);
        $docs = DocumentacionAltas::where('solicitud_id', $user->sol_alta_id)->first();

    $tipo = $user->solicitudAlta->tipo_empleado ?? 'oficina';

        if ($tipo === 'armado') {
            $documentosObligatorios = [
                ['label' => 'Solicitud/CV', 'name' => 'arch_solicitud_empleo'],
                ['label' => 'INE', 'name' => 'arch_ine'],
                ['label' => 'NSS', 'name' => 'arch_nss'],
                ['label' => 'CURP', 'name' => 'arch_curp'],
                ['label' => 'RFC', 'name' => 'arch_rfc'],
                ['label' => 'Acta de Nacimiento', 'name' => 'arch_acta_nacimiento'],
                ['label' => 'Comprobante de Estudios', 'name' => 'arch_comprobante_estudios'],
                ['label' => 'Comprobante de Domicilio', 'name' => 'arch_comprobante_domicilio'],
                ['label' => 'Carta de Recomendación Laboral', 'name' => 'arch_carta_rec_laboral'],
                ['label' => 'Carta de Recomendación Personal', 'name' => 'arch_carta_rec_personal'],
                ['label' => 'Cartilla Militar', 'name' => 'arch_cartilla_militar'],
                ['label' => 'Antidoping', 'name' => 'arch_antidoping'],
                ['label' => 'Carta de No Antecedentes Penales', 'name' => 'arch_carta_no_penales'],
                ['label' => 'Contrato', 'name' => 'arch_contrato'],
                ['label' => 'Fotografía (Reciente)', 'name' => 'arch_foto'],
            ];
        } else {
            $documentosObligatorios = [
                ['label' => 'Solicitud/CV', 'name' => 'arch_solicitud_empleo'],
                ['label' => 'INE', 'name' => 'arch_ine'],
                ['label' => 'NSS', 'name' => 'arch_nss'],
                ['label' => 'CURP', 'name' => 'arch_curp'],
                ['label' => 'RFC', 'name' => 'arch_rfc'],
                ['label' => 'Acta de Nacimiento', 'name' => 'arch_acta_nacimiento'],
                ['label' => 'Comprobante de Estudios', 'name' => 'arch_comprobante_estudios'],
                ['label' => 'Comprobante de Domicilio', 'name' => 'arch_comprobante_domicilio'],
                ['label' => 'Carta de Recomendación Laboral', 'name' => 'arch_carta_rec_laboral'],
                ['label' => 'Carta de Recomendación Personal', 'name' => 'arch_carta_rec_personal'],
                ['label' => 'Contrato', 'name' => 'arch_contrato'],
                ['label' => 'Fotografía (Reciente)', 'name' => 'arch_foto'],
            ];
        }
        $mpdf = new Mpdf();
        $html = view('pdf.fichaTecnica', compact('user', 'docs', 'documentosObligatorios'))->render();
        $mpdf->WriteHTML($html);

        $tempFile = tempnam(sys_get_temp_dir(), 'ficha_') . '.pdf';
        $mpdf->Output($tempFile);

        $pdf = new Fpdi();

        $pageCount = $pdf->setSourceFile($tempFile);
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $tplId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($tplId);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tplId);
        }

        foreach ($documentosObligatorios as $doc) {
            $archivo = $docs ? ($docs->{$doc['name']} ?? null) : null;

            if ($archivo && file_exists(public_path($archivo))) {
                $rutaArchivo = public_path($archivo);
                $ext = strtolower(pathinfo($rutaArchivo, PATHINFO_EXTENSION));

                if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                    $pdf->AddPage();
                    $pdf->Image($rutaArchivo, 10, 10, 190);
                } elseif ($ext === 'pdf') {
                    $docPageCount = $pdf->setSourceFile($rutaArchivo);
                    for ($i = 1; $i <= $docPageCount; $i++) {
                        $tplId = $pdf->importPage($i);
                        $size = $pdf->getTemplateSize($tplId);

                        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                        $pdf->useTemplate($tplId);
                    }
                }
            }
        }

        unlink($tempFile);

        return response($pdf->Output('ficha_tecnica_' . $user->id . '.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="ficha_tecnica_'.$user->id.'.pdf"');
    }
}
