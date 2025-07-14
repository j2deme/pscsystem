<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incapacidad;
use App\Models\User; // Importa el modelo User

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Para depuración

class IncapacidadController extends Controller
{
    /**
     * Muestra el listado de usuarios para generar incapacidades.
     */
    public function index()
    {
        // La lógica de paginación y búsqueda se maneja en el componente Livewire
        return view('auxadmin.incapacidadesList');
    }

    /**
     * Muestra el formulario para generar una nueva incapacidad para un usuario.
     */
    public function create(User $user)
    {
        // Laravel automáticamente inyecta el User basado en el ID de la URL
        Log::info('IncapacidadController@create: User ID recibido - ' . $user->id);
        return view('auxadmin.incapacidadForm', compact('user'));
    }

    /**
     * Guarda una nueva incapacidad en la base de datos.
     */
    public function store(Request $request, User $id=null) // Recibimos el Request y el modelo User
    {
        //Log::info('IncapacidadController@store: Intentando guardar incapacidad para User ID - ' . $user->id);

        // Validar los datos del formulario
        $request->validate([
            'motivo' => 'required|string|max:255',
            'tipo_incapacidad' => 'required|string|max:255',
            'ramo_seguro' => 'required|string|max:255',
            'dias_incapacidad' => 'required|integer|min:1',
            'fecha_inicio' => 'required|date',
            'folio' => 'required|string|max:255|unique:incapacidades,folio',
            'archivo_pdf' => 'required|file|mimes:pdf|max:2048', // PDF, max 2MB
        ], [
            'folio.unique' => 'El folio de incapacidad ya existe. Por favor, verifica.',
        ]);

        $rutaArchivo = null;
        if ($request->hasFile('archivo_pdf')) {
            // Guarda el archivo en storage/app/public/incapacidades_pdfs
            $rutaArchivo = $request->file('archivo_pdf')->store('incapacidades_pdfs', 'public');
        }

        // Crear el registro de la incapacidad
        Incapacidad::create([
            'user_id' =>$request->user_id, // Asignamos el ID del usuario
            'motivo' => $request->motivo,
            'tipo_incapacidad' => $request->tipo_incapacidad,
            'ramo_seguro' => $request->ramo_seguro,
            'dias_incapacidad' => $request->dias_incapacidad,
            'fecha_inicio' => $request->fecha_inicio,
            'folio' => $request->folio,
            'ruta_archivo_pdf' => $rutaArchivo,
        ]);

        return redirect()->route('aux.incapacidadesList')->with('success', 'Incapacidad registrada exitosamente para ');
    }
     public function showIncapacidadesHistory()
    {
        return view('auxadmin.historialIncapacidades');
    }
}
