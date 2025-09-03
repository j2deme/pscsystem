<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\SolicitudBajas;

class AuxcontController extends Controller
{
    public function listaFiniquitos()
    {
        $renuncias = SolicitudBajas::where('estatus', 'Aceptada')
            ->where('observaciones', 'Finiquito enviado a RH.')
            ->whereDate('fecha_baja', '>=', now()->subDays(30))
            ->orderBy('fecha_baja', 'desc')
            ->paginate(10);

        return view('auxcont.listaFiniquitos', compact('renuncias'));
    }

    public function subirCheque(Request $request, $id)
    {
        $request->validate([
            'archivo_cheque' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        try {
            $solicitud = SolicitudBajas::findOrFail($id);

            if ($request->hasFile('archivo_cheque')) {
                // Crear directorio si no existe
                $directorio = 'solicitudesBajas/' . $id;
                Storage::disk('public')->makeDirectory($directorio);

                // Generar nombre de archivo único
                $extension = $request->file('archivo_cheque')->getClientOriginalExtension();
                $nombreArchivo = 'cheque_' . date('Ymd_His') . '.' . $extension;
                $rutaCompleta = $directorio . '/' . $nombreArchivo;

                // Guardar archivo
                $rutaArchivo = $request->file('archivo_cheque')->storeAs($directorio, $nombreArchivo, 'public');

                // Actualizar registro en la base de datos
                $solicitud->update([
                    'arch_cheque' => $rutaArchivo,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Cheque guardado correctamente.',
                    'ruta' => Storage::url($rutaArchivo)
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No se encontró el archivo.'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el cheque: ' . $e->getMessage()
            ], 500);
        }
    }
}
