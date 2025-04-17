<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\RhController;
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
    Route::get('/users/registrarUsuario', [UserController::class, 'crearUsuario'])->name('crearUsuario');
    Route::post('/guardarUsuario', [UserController::class, 'registrarUsuario'])->name('registrarUsuario');

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
    Route::get('/ver_asistencias', [SupervisorController::class,'verAsistencias'])->name('sup.verAsistencias');
    Route::get('/supervisor/ver_fecha_sistencias', [SupervisorController::class, 'verFechaAsistencias'])->name('sup.verFechaAsistencias');


    //usuario Recuersos Humanos
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

    //Usuario 'User'
    Route::get('/solicitar_baja', [UserController::class,'solicitarBajaForm'])->name('user.solicitarBajaForm');
    Route::post('/registrar_solicitud_baja/{id}', [UserController::class,'solicitarBaja'])->name('user.registrarSolicitudBaja');

});

require __DIR__.'/auth.php';
