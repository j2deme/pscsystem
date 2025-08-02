<?php

use App\Exports\AltasSpreadsheetExport;
use App\Exports\BajasSpreadsheetExport;
use App\Exports\VacacionesSpreadsheetExport;
use App\Exports\VacacionesCortesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\RhController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\MonitoreoController;
use App\Http\Controllers\AuxadminController;
use App\Http\Controllers\NominasController;
use App\Http\Controllers\CustodiosController;
use App\Http\Controllers\ChatWebController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RiesgoTrabajoController;
use App\Http\Controllers\IncapacidadController;
use App\Http\Controllers\IncapacidadReporteController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CedulaController;
use App\Http\Controllers\SipareController;
use App\Http\Controllers\GraficosController;
use App\Http\Controllers\BajaAcuseController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    //Broadcast::routes(['middleware' => ['web', 'auth']]);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Usuario Admnistrador
    Route::get('/users', [ProfileController::class, 'mostrarUsuarios'])->name('admin.verUsuarios');
    Route::get('/users/registrarUsuario', [UserController::class, 'crearUsuario'])->name('admin.crearUsuarioForm');
    Route::post('/guardarUsuario', [UserController::class, 'registrarUsuario'])->name('registrarUsuario');
    Route::get('/editar_usuario/{id}', [AdminController::class, 'editarUsuario'])->name('admin.editarUsuarioForm');
    Route::get('/ver_usuarios', [AdminController::class, 'verUsuarios'])->name('admin.verUsuarios');
    Route::get('/tablero_supervisores', [AdminController::class, 'tableroSupervisores'])->name('admin.verTableroSupervisores');
    Route::get('/admin_solicitudes_altas', [AdminController::class, 'verSolicitudesAltas'])->name('admi.verSolicitudesAltas');
    Route::get('/admin/baja_usuario/{id}', [AdminController::class, 'bajaUsuario'])->name('admin.darDeBajaUsuario');
    Route::get('/editar_usuario/{id}', [AdminController::class, 'editarUsuario'])->name('admin.editarUsuarioForm');
    Route::get('/ver_buzon', [AdminController::class, 'verBuzon'])->name('admin.verBuzon');
    Route::post('/importar-excel', [ImportController::class, 'importarArchivoRosa'])->name('importar.excel');
    Route::get('/reingreso/{id}', [AdminController::class, 'darReingreso'])->name('admin.reingreso');
    Route::get('/tablero_nominas', [AdminController::class, 'tableroNominas'])->name('admin.nominasDashboard');
    Route::get('/tablero_imss', [AdminController::class, 'tableroImss'])->name('admin.imssDashboard');
    Route::get('/tablero_rh', [AdminController::class, 'tableroRh'])->name('admin.rhDashboard');
    Route::get('tablero_monitoreo', [AdminController::class, 'tableroMonitoreo'])->name('admin.monitoreoDashboard');
    Route::get('tablero_juridico', [AdminController::class, 'tableroJuridico'])->name('admin.juridicoDashboard');
    Route::get('/tablero_custodios', [AdminController::class, 'tableroCustodios'])->name('admin.custodiosDashboard');
    Route::get('/admin_vacaciones', [AdminController::class, 'solicitudesVacaciones'])->name('admin.solicitudesVacaciones');
    Route::get('/registrar_nominas', [AdminController::class, 'registrarNominas'])->name('registrarNominas');
    Route::get('/registrar_finiquitos', [AdminController::class, 'registrarFiniquitos'])->name('registrarFiniquitos');

    //Usuario Supervisor
    Route::get('/nuevoUsuario', [SupervisorController::class, 'nuevoUsuarioForm'])->name('sup.nuevoUsuarioForm');
    Route::post('/infoUsuario', [SupervisorController::class, 'guardarInfo'])->name('sup.guardarInfo');
    Route::get('/subir-archivos/{id}', [SupervisorController::class, 'subirArchivosForm'])->name('sup.subirArchivosForm');
    Route::post('/subir-archivos/{id}', [SupervisorController::class, 'guardarArchivos'])->name('sup.guardarArchivos');
    Route::get('/historial_solicitudes', [SupervisorController::class, 'historialSolicitudes'])->name('sup.historial');
    Route::get('/historial_solicitudes/{id}', [SupervisorController::class, 'detalleSolicitud'])->name('sup.solicitud.detalle');
    Route::get('/editar_solicitud/{id}', [SupervisorController::class, 'editarSolicitudForm'])->name('sup.editarSolicitudForm');
    Route::post('/editar_informacion_solicitud/{id}', [SupervisorController::class, 'editarInformacionSolicitud'])->name('sup.editarInformacionSolicitud');
    Route::post('/subir_archivos_editados/{id}', [SupervisorController::class, 'subirArchivosEditados'])->name('sup.guardarArchivosEditados');
    Route::get('/sup_solicitar_baja', [SupervisorController::class, 'solicitarBajaForm'])->name('sup.solicitarBajaForm');
    Route::get('/sup_solicitar_baja/{id}', [SupervisorController::class, 'solicitarBajaVista'])->name('sup.validarSolicitudBaja');
    Route::post('/nueva_guardar_baja/{id}', [SupervisorController::class, 'guardarBajaNueva'])->name('sup.guardarBajaNueva');
    Route::get('/historial_bajas', [SupervisorController::class, 'historialBajas'])->name('sup.historialBajas');
    Route::get('/lista_asistencia', [SupervisorController::class, 'listaAsistencia'])->name('sup.listaAsistencia');
    Route::post('/guardar_asistencias', [SupervisorController::class, 'guardarAsistencias'])->name('sup.guardarAsistencias');
    Route::get('/ver_asistencias/{id}', [SupervisorController::class, 'verAsistencias'])->name('sup.verAsistencias');
    Route::get('/supervisor/ver_fecha_sistencias', [SupervisorController::class, 'verFechaAsistencias'])->name('sup.verFechaAsistencias');
    Route::get('/detalle_asistencia/{id}', [SupervisorController::class, 'detalleAsistencia'])->name('sup.detalleAsistencia');
    Route::get('/solicitudes_vacaciones', [SupervisorController::class, 'solicitudesVacaciones'])->name('sup.solicitudesVacaciones');
    Route::get('/aceptar_solicitud_vacaciones/{id}', [SupervisorController::class, 'aceptarSolicitudVacaciones'])->name('sup.aceptarSolicitudVacaciones');
    Route::get('/rechazar_solicitud_vacaciones/{id}', [SupervisorController::class, 'rechazarSolicitudVacaciones'])->name('sup.rechazarSolicitudVacaciones');
    Route::get('/ver_solicitud_baja/{id}', [SupervisorController::class, 'verSolicitudBaja'])->name('sup.verSolicitudBaja');
    Route::get('/tiempos_extras', [SupervisorController::class, 'tiemposExtras'])->name('sup.tiemposExtras');
    Route::get('/tiempos_extras/{id}', [SupervisorController::class, 'tiemposExtrasForm'])->name('sup.tiemposExtrasForm');
    Route::post('/guardar_tiempo_extra/{id}', [SupervisorController::class, 'guardarTiempoExtra'])->name('sup.guardarTiempoExtra');
    Route::get('/cobertura_turno_form/{id}', [SupervisorController::class, 'coberturaTurnoForm'])->name('sup.coberturaTurnoForm');
    Route::post('/guardar_cobertura_turno/{id}', [SupervisorController::class, 'guardarCoberturaTurno'])->name('sup.guardarCoberturaTurno');
    Route::get('/historial_tiempos_extras', [SupervisorController::class, 'historialTiemposExtras'])->name('sup.historialTiemposExtras');
    Route::get('/gestion_usuarios', [SupervisorController::class, 'gestionUsuarios'])->name('sup.gestionUsuarios');
    Route::get('/descargar_formato_vacaciones/{id}', [SupervisorController::class, 'descargarSolicitudVacaciones'])->name('sup.descargarSolicitudVacaciones');
    Route::post('/solicitud-vacaciones/{id}/subir-archivo', [SupervisorController::class, 'subirArchivo'])->name('solicitud-vacaciones.subir-archivo');
    Route::get('/vacaciones_elemento', [SupervisorController::class, 'solicitarVacacionesElemento'])->name('sup.solicitarVacacionesElemento');
    Route::get('/vacaciones_elemento/{id}', [SupervisorController::class, 'vacacionesElementoForm'])->name('sup.solicitarVacacionesElementoForm');
    Route::get('/asistencias/confirmar-faltas', [SupervisorController::class, 'confirmarFaltas'])->name('asistencias.confirmarFaltas');
    Route::post('/asistencias/finalizar', [SupervisorController::class, 'finalizarAsistencia'])->name('asistencias.finalizar');
    Route::get('/sup-alta-usuario', [SupervisorController::class, 'formAlta'])->name('sup.formAlta');


    //usuario Recursos Humanos
    Route::get('/solicitudes_altas', [RhController::class, 'solicitudesAltas'])->name('rh.solicitudesAltas');
    Route::get('/solicitudes_altas/{id}', [RhController::class, 'detalleSolicitud'])->name('rh.detalleSolicitud');
    Route::get('/aceptar_solicitud/{id}', [RhController::class, 'aceptarSolicitud'])->name('rh.aceptarSolicitud');
    Route::post('/enviar_observacion/{id}', [RhController::class, 'enviarObservacion'])->name('rh.observacion_solicitud');
    Route::get('/rechazar_solicitud/{id}', [RhController::class, 'rechazarSolicitud'])->name('rh.rechazarSolicitud');
    Route::get('/historial_solicitudes_altas', [RhController::class, 'historialSolicitudesAltas'])->name('rh.historialSolicitudesAltas');
    Route::get('/solicitudes_bajas', [RhController::class, 'solicitudesBajas'])->name('rh.solicitudesBajas');
    Route::get('/historial_solicitudes_bajas', [RhController::class, 'historialSolicitudesBajas'])->name('rh.historialSolicitudesBajas');
    Route::get('/detalle_solicitud_baja/{id}', [RhController::class, 'detalleSolicitudBaja'])->name('rh.detalleSolicitudBaja');
    Route::get('/rechzar_baja/{id}', [RhController::class, 'rechazarBaja'])->name('rh.rechazarBaja');
    Route::get('/aceptar_baja/{id}', [RhController::class, 'aceptarBaja'])->name('rh.aceptarBaja');
    Route::get('/generar_nueva_alta', [RhController::class, 'generarNuevaAltaForm'])->name('rh.generarNuevaAltaForm');
    Route::post('/guardar_alta', [RhController::class, 'guardarAlta'])->name('rh.guardarAlta');
    Route::get('/subir_archivos_alta/{id}', [RhController::class, 'subirArchivosAltaForm'])->name('rh.subirArchivosAltaForm');
    Route::post('/guardar_archivos_alta/{id}', [RhController::class, 'guardarArchivosAlta'])->name('rh.guardarArchivosAlta');
    Route::get('/generar_nueva_baja', [RhController::class, 'generarNuevaBajaForm'])->name('rh.generarNuevaBajaForm');
    Route::get('llenar_baja/{id}', [RhController::class, 'llenarBaja'])->name('rh.llenarBaja');
    Route::post('almacenar_baja/{id}', [RhController::class, 'almacenarBaja'])->name('rh.almacenarBajaNueva');
    Route::get('/archivos', [RhController::class, 'verArchivos'])->name('rh.archivos');
    Route::get('/vista_vacaciones', [RhController::class, 'vistaVacaciones'])->name('rh.vistaVacaciones');
    Route::get('/historial_vacaciones', [RhController::class, 'historialVacaciones'])->name('rh.historialVacaciones');
    Route::get('/alta-usuario', [RhController::class, 'formAlta'])->name('rh.formAlta');
    Route::get('/descargar_ficha/{id}', [RhController::class, 'exportFichaTecnica'])->name('rh.descargarFicha');

    Route::get('/descargar-bajas', function () {
        return (new BajasSpreadsheetExport())->generateFile();
    })->name('exportar.bajas');

    Route::get('/descargar-altas', function () {
        return (new AltasSpreadsheetExport())->generateFile();
    })->name('exportar.altas');

    Route::get('/descargar-vacaciones', function () {
        return (new VacacionesSpreadsheetExport())->generateFile();
    })->name('exportar.vacaciones');

    Route::get('/descargar-vacaciones-cortes', function () {
        $inicio = request()->query('inicio');
        $fin    = request()->query('fin');
        return (new VacacionesCortesExport())->generateFile($inicio, $fin);
    })->name('exportar.vacacionesCortes');

    Route::get('/exportar-asistencias', function () {
        return (new \App\Exports\AsistenciasSpreadsheetExport(
            request('punto'),
            request('fecha_inicio'),
            request('fecha_fin')
        ))->generateFile();
    })->name('exportar.asistencias');

    //Usuario 'User'
    Route::get('/solicitar_baja', [UserController::class, 'solicitarBajaForm'])->name('user.solicitarBajaForm');
    Route::post('/registrar_solicitud_baja/{id}', [UserController::class, 'solicitarBaja'])->name('user.registrarSolicitudBaja');
    Route::get('/solicitar_vacaciones_form', [UserController::class, 'solicitarVacacionesForm'])->name('user.solicitarVacacionesForm');
    Route::post('/solicitar_vacaciones/{id}', [UserController::class, 'solicitarVacaciones'])->name('user.solicitarVacaciones');
    Route::get('/historial_solicitudes_vacaciones', [UserController::class, 'historialVacaciones'])->name('user.historialVacaciones');
    Route::get('/ver_ficha/{id}', [UserController::class, 'verFicha'])->name('user.verFicha');
    Route::get('/buzon', [UserController::class, 'buzon'])->name('user.buzon');
    Route::post('/enviar_sugerencia/{id}', [UserController::class, 'enviarSugerencia'])->name('user.enviarSugerencia');

    /*
     * MONITORISTA
     */
    Route::get('/ver_deducciones', [MonitoreoController::class, 'verDeducciones'])->name('monitoreo.deducciones');
    Route::get('/mapa', [MonitoreoController::class, 'mapa'])->name('monitoreo.mapa');
    Route::get('/vehiculos', function () {
        return view('vehiculos.crud');
    })->name('vehiculos.index');
    // Detalle de Vehículo (vista Blade con Livewire)
    Route::get('/vehiculos/{id}', function ($id) {
        return view('vehiculos.detalle', ['id' => $id]);
    })->name('vehiculos.detalle');

    // Servicios CRUD (Livewire)
    Route::get('/servicios', \App\Livewire\ServiciosCrud::class)->name('servicios.index');
    // Detalle de Servicio (Livewire)
    Route::get('/servicios/{id}', \App\Livewire\ServicioDetalle::class)->name('servicio.detalle');

    // Siniestros CRUD (Livewire)
    Route::get('/siniestros', \App\Livewire\SiniestrosCrud::class)->name('siniestros.index');
    // Detalle del Siniestro (Livewire)
    Route::get('/siniestros/{id}', \App\Livewire\SiniestroDetalle::class)->name('siniestros.detalle');

    //Usuario Aux Admin
    Route::get('/nuevas_altas_elementos', [AuxadminController::class, 'nuevasAltas'])->name('aux.nuevasAltas');
    Route::post('/subida_documentacion/{id}', [AuxadminController::class, 'guardarAcuses'])->name('documentacion.subir');
    Route::get('/listado_usuarios', [AuxadminController::class, 'listadoUsuarios'])->name('aux.usuariosList');
    Route::post('/actualizacion_documentacion/{id}', [AuxadminController::class, 'actualizarAcuses'])->name('documentacion.actualizar');
    Route::get('/confrontas', [AuxadminController::class, 'confrontasForm'])->name('aux.confrontas');
    Route::post('/confrontas-upload', [AuxadminController::class, 'confrontasUpload'])->name('confrontas.upload');

    Route::get('/sipare', [SipareController::class, 'form'])->name('aux.sipareForm');
    Route::post('/sipare/upload', [SipareController::class, 'upload'])->name('aux.sipareUpload');

    Route::get('/cedulas', [CedulaController::class, 'form'])->name('aux.cedulasForm');
    Route::post('/cedulas/upload/{tipo}', [CedulaController::class, 'upload'])->name('aux.cedulasupload');

   Route::get('/acuses-bajas', [BajaAcuseController::class, 'index'])->name('aux.acusesbajas');
   Route::post('/acuses-bajas/{solicitudBaja}', [BajaAcuseController::class, 'upload'])->name('aux.acusesbajasupload');

    //nuevass rutas
    Route::get('/riesgos-trabajo', [RiesgoTrabajoController::class, 'index'])->name('aux.riesgosTrabajo');
    Route::get('/riesgos-trabajo/generar/{user}', [RiesgoTrabajoController::class, 'create'])->name('aux.generarRiesgoForm');
    Route::post('/riesgos-trabajo/guardar', [RiesgoTrabajoController::class, 'store'])->name('aux.guardarRiesgo');
    //nuevas rutas incapacidades
    Route::get('/incapacidades', [IncapacidadController::class, 'index'])->name('aux.incapacidadesList');
    Route::get('/incapacidades/generar/{user}', [IncapacidadController::class, 'create'])->name('aux.generarIncapacidadForm');
    Route::post('/incapacidades/guardar', [IncapacidadController::class, 'store'])->name('aux.guardarIncapacidad');
    //historial incapacidades
    Route::get('/aux/historial-incapacidades', [IncapacidadController::class, 'showIncapacidadesHistory'])->name('aux.historialIncapacidades');
    Route::get('/aux/historial-riesgos-trabajo', [RiesgoTrabajoController::class, 'showHistorialRiesgosTrabajo'])->name('aux.historialRiesgosTrabajo');
    Route::get('/reporte/incapacidades', [IncapacidadReporteController::class, 'generarPdf'])->name('reporte.incapacidades.pdf');
    //graficas
    Route::get('/graficos', [GraficosController::class, 'index'])->name('auxadmin.index');


    //Usuario nominas
    Route::get('/antiguedades', [NominasController::class, 'antiguedades'])->name('nominas.usersAntiguedades');
    Route::get('/finiquitos', [NominasController::class, 'verBajas'])->name('nominas.verBajas');
    Route::get('/nuevas_altas', [NominasController::class, 'nuevasAltas'])->name('nominas.nuevasAltas');
    Route::post('/guardar-calculo-finiquito', [NominasController::class, 'guardarCalculoFiniquito'])->name('guardar.calculo.finiquito');
    Route::get('/asistencias_nominas', [NominasController::class, 'asistenciasNominas'])->name('nominas.asistencias');
    Route::get('/vacaciones_nominas', [NominasController::class, 'vacacionesNominas'])->name('nominas.vacaciones');
    Route::get('/nominas_vacaciones', [NominasController::class, 'vacacionesIndex'])->name('nominas.vacaciones');
    Route::get('/nominas', [NominasController::class, 'vistaNominas'])->name('vistaNominas');
    Route::get('/calculos_nominas', [NominasController::class, 'calculosNominas'])->name('nominas.calculos');
    Route::get('/graficas_estadisticas', [NominasController::class, 'graficas'])->name('nominas.graficas');
    Route::get('/deducciones', [NominasController::class, 'deduccionesIndex'])->name('nominas.deducciones');
    Route::get('/nueva_deduccion', [NominasController::class, 'nuevaDeduccionForm'])->name('crearDeduccion');
    Route::post('/guardar_deduccion', [NominasController::class, 'guardarDeduccion'])->name('guardarDeduccion');
    Route::post('/asginar_num_empleado', [NominasController::class, 'asignarNumEmpleado'])->name('nominas.asignarNumeroEmpleado');
    Route::post('/solicitar-constancia', [NominasController::class, 'solicitarConstancia'])->name('solicitar.constancia');
    Route::get('/destajos', [NominasController::class, 'destajos'])->name('nominas.destajos');
    Route::get('/calculo_destajos', [NominasController::class, 'calculoDestajos'])->name('nominas.calculoDestajos');
    Route::post('/notificaciones/leidas', function () {
        \App\Models\Alerta::where('user_id', Auth::id())
            ->where('leida', false)
            ->update(['leida' => true]);

        return response()->json(['ok' => true]);
    })->name('notificaciones.leer');

    //Custodios
    Route::get('/nueva_mision', [CustodiosController::class, 'nuevaMisionForm'])->name('custodios.nuevaMisionForm');
    Route::post('/agentes-disponibles', [CustodiosController::class, 'obtenerAgentesDisponibles']);
    Route::post('/guardarMision', [CustodiosController::class, 'guardarMision'])->name('misiones.store');
    Route::get('/misiones', [CustodiosController::class, 'misionesIndex'])->name('custodios.misiones');
    Route::get('/custodios', [CustodiosController::class, 'custodiosIndex'])->name('custodios.elementos');
    Route::get('/historial_misiones', [CustodiosController::class, 'historialMisiones'])->name('custodios.historialMisiones');
    Route::get('/misiones_terminadas', [CustodiosController::class, 'misionesTerminadas'])->name('custodios.misionesTerminadas');
    //Route::get('mensajes', [CustodiosController::class,'mensajesIndex'])->name('custodios.mensajes');

    //Mensajería
    Route::get('/mensajes/nuevo', [ChatWebController::class, 'crear'])->name('mensajes.crearChat');
    Route::post('/mensajes/nuevo', [ChatWebController::class, 'storeConversacion'])->name('mensajes.nueva');
    Route::get('/mensajes', [ChatWebController::class, 'index'])->name('mensajes.index');
    Route::get('/mensajes/{conversation}', [ChatWebController::class, 'show'])->name('mensajes.show');
    Route::post('/mensajes/enviar', [ChatWebController::class, 'storeMensaje'])->name('mensajes.store');
});

require __DIR__ . '/auth.php';
