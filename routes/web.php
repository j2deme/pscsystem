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
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
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
    Route::post('/importar-excel', [ImportController::class, 'importarUnidades'])->name('importar.excel');
    Route::get('/reingreso/{id}', [AdminController::class, 'darReingreso'])->name('admin.reingreso');

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
    Route::get('/sup_solicitar_baja', [SupervisorController::class,'solicitarBajaForm'])->name('sup.solicitarBajaForm');
    Route::get('/sup_solicitar_baja/{id}', [SupervisorController::class,'solicitarBajaVista'])->name('sup.validarSolicitudBaja');
    Route::post('/nueva_guardar_baja/{id}', [SupervisorController::class, 'guardarBajaNueva'])->name('sup.guardarBajaNueva');
    Route::get('/historial_bajas', [SupervisorController::class,'historialBajas'])->name('sup.historialBajas');
    Route::get('/lista_asistencia', [SupervisorController::class, 'listaAsistencia'])->name('sup.listaAsistencia');
    Route::post('/guardar_asistencias', [SupervisorController::class, 'guardarAsistencias'])->name('sup.guardarAsistencias');
    Route::get('/ver_asistencias/{id}', [SupervisorController::class,'verAsistencias'])->name('sup.verAsistencias');
    Route::get('/supervisor/ver_fecha_sistencias', [SupervisorController::class, 'verFechaAsistencias'])->name('sup.verFechaAsistencias');
    Route::get('/detalle_asistencia/{id}', [SupervisorController::class, 'detalleAsistencia'])->name('sup.detalleAsistencia');
    Route::get('/solicitudes_vacaciones', [SupervisorController::class,'solicitudesVacaciones'])->name('sup.solicitudesVacaciones');
    Route::get('/aceptar_solicitud_vacaciones/{id}', [SupervisorController::class, 'aceptarSolicitudVacaciones'])->name('sup.aceptarSolicitudVacaciones');
    Route::get('/rechazar_solicitud_vacaciones/{id}', [SupervisorController::class,'rechazarSolicitudVacaciones'])->name('sup.rechazarSolicitudVacaciones');
    Route::get('/ver_solicitud_baja/{id}', [SupervisorController::class,'verSolicitudBaja'])->name('sup.verSolicitudBaja');
    Route::get('/tiempos_extras', [SupervisorController::class, 'tiemposExtras'])->name('sup.tiemposExtras');
    Route::get('/tiempos_extras/{id}', [SupervisorController::class, 'tiemposExtrasForm'])->name('sup.tiemposExtrasForm');
    Route::post('/guardar_tiempo_extra/{id}', [SupervisorController::class, 'guardarTiempoExtra'])->name('sup.guardarTiempoExtra');
    Route::get('/cobertura_turno_form/{id}', [SupervisorController::class, 'coberturaTurnoForm'])->name('sup.coberturaTurnoForm');
    Route::post('/guardar_cobertura_turno/{id}', [SupervisorController::class, 'guardarCoberturaTurno'])->name('sup.guardarCoberturaTurno');
    Route::get('/historial_tiempos_extras', [SupervisorController::class, 'historialTiemposExtras'])->name('sup.historialTiemposExtras');
    Route::get('/gestion_usuarios', [SupervisorController::class, 'gestionUsuarios'])->name('sup.gestionUsuarios');
    Route::get('/descargar_formato_vacaciones/{id}', [SupervisorController::class, 'descargarSolicitudVacaciones'])->name('sup.descargarSolicitudVacaciones');
    Route::post('/solicitud-vacaciones/{id}/subir-archivo', [SupervisorController::class, 'subirArchivo'])->name('solicitud-vacaciones.subir-archivo');

    //usuario Recursos Humanos
    Route::get('/solicitudes_altas', [RhController::class,'solicitudesAltas'])->name('rh.solicitudesAltas');
    Route::get('/solicitudes_altas/{id}', [RhController::class, 'detalleSolicitud'])->name('rh.detalleSolicitud');
    Route::get('/aceptar_solicitud/{id}', [RhController::class, 'aceptarSolicitud'])->name('rh.aceptarSolicitud');
    Route::post('/enviar_observacion/{id}', [RhController::class, 'enviarObservacion'])->name('rh.observacion_solicitud');
    Route::get('/rechazar_solicitud/{id}', [RhController::class,'rechazarSolicitud'])->name('rh.rechazarSolicitud');
    Route::get('/historial_solicitudes_altas', [RhController::class, 'historialSolicitudesAltas'])->name('rh.historialSolicitudesAltas');
    Route::get('/solicitudes_bajas', [RhController::class, 'solicitudesBajas'])->name('rh.solicitudesBajas');
    Route::get('/historial_solicitudes_bajas', [RhController::class, 'historialSolicitudesBajas'])->name('rh.historialSolicitudesBajas');
    Route::get('/detalle_solicitud_baja/{id}', [RhController::class, 'detalleSolicitudBaja'])->name('rh.detalleSolicitudBaja');
    Route::get('/rechzar_baja/{id}', [RhController::class, 'rechazarBaja'])->name('rh.rechazarBaja');
    Route::get('/aceptar_baja/{id}', [RhController::class, 'aceptarBaja'])->name('rh.aceptarBaja');
    Route::get('/generar_nueva_alta', [RhController::class, 'generarNuevaAltaForm'])->name('rh.generarNuevaAltaForm');
    Route::post('/guardar_alta', [RhController::class, 'guardarAlta'])->name('rh.guardarAlta');
    Route::get('/subir_archivos_alta/{id}', [RhController::class,'subirArchivosAltaForm'])->name('rh.subirArchivosAltaForm');
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
        $fin = request()->query('fin');
        return (new App\Exports\VacacionesCortesExport())->generateFile($inicio, $fin);
    })->name('exportar.vacacionesCortes');

    //Usuario 'User'
    Route::get('/solicitar_baja', [UserController::class,'solicitarBajaForm'])->name('user.solicitarBajaForm');
    Route::post('/registrar_solicitud_baja/{id}', [UserController::class,'solicitarBaja'])->name('user.registrarSolicitudBaja');
    Route::get('/solicitar_vacaciones_form', [UserController::class,'solicitarVacacionesForm'])->name('user.solicitarVacacionesForm');
    Route::post('/solicitar_vacaciones/{id}', [UserController::class,'solicitarVacaciones'])->name('user.solicitarVacaciones');
    Route::get('/historial_solicitudes_vacaciones', [UserController::class, 'historialVacaciones'])->name('user.historialVacaciones');
    Route::get('/ver_ficha/{id}', [UserController::class,'verFicha'])->name('user.verFicha');
    Route::get('/buzon', [UserController::class, 'buzon'])->name('user.buzon');
    Route::post('/enviar_sugerencia/{id}', [UserController::class, 'enviarSugerencia'])->name('user.enviarSugerencia');

    //Uusario Monitorista

    //Usuario Aux Admin
    Route::get('/nuevas_altas', [AuxadminController::class, 'nuevasAltas'])->name('aux.nuevasAltas');

});

require __DIR__.'/auth.php';
