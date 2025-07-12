<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RiesgoTrabajo;
use Illuminate\Support\Facades\Storage; // Para manejar la subida de archivos
use Illuminate\Support\Facades\Log;
class RiesgoTrabajoController extends Controller
{
    /**
     * Muestra el listado de usuarios para generar riesgos de trabajo.
     */
    public function index()
    {

        // Pasamos los usuarios para que el Livewire para cargarlos
        return view('auxadmin.riesgosTrabajoList');
    }

    /**
     * Muestra el formulario para generar un nuevo riesgo de trabajo para un usuario.
     */
    public function create(User $user)
    {
        return view('auxadmin.riesgosTrabajoForm', compact('user'));
    }

    /**
     * Guarda un nuevo riesgo de trabajo.
     */
   public function store(Request $request, $id=null)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tipo_riesgo' => 'required|in:En el trabajo,En trayecto',
            'descripcion_observaciones' => 'nullable|string',
            'archivo_pdf' => 'nullable|file|mimes:pdf|max:2048', // PDF, max 2MB
        ]);

        $rutaArchivo = null;
        if ($request->hasFile('archivo_pdf')) {
            $rutaArchivo = $request->file('archivo_pdf')->store('riesgos_trabajo_pdfs', 'public');
        }

        RiesgoTrabajo::create([
            'user_id' => $request->user_id,
            'tipo_riesgo' => $request->tipo_riesgo,
            'descripcion_observaciones' => $request->descripcion_observaciones,
            'ruta_archivo_pdf' => $rutaArchivo,
        ]);

        return redirect()->route('aux.riesgosTrabajo')->with('success', 'Riesgo de trabajo registrado exitosamente.');
    }
 public function showHistorialRiesgosTrabajo()
    {
        return view('auxadmin.historialRiesgosTrabajo');
    }

}
