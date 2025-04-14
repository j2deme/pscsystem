<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupervisorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/rh_dashboard', function () {
    return view('rh.rhDashboard');
})->middleware(['auth', 'verified'])->name('rhdashboard');

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
    Route::get('/archuvos_usuario', [SupervisorController::class, 'subirArchivosForm'])->name('sup.subirArchivosForm');
});

require __DIR__.'/auth.php';
