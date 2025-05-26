<?php

namespace App\Http\Controllers;
use App\Models\SolicitudAlta;
use App\Models\SolicitudBajas;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NominasController extends Controller
{
    public function antiguedades(){
        return view('nominas.antiguedades');
    }

    public function verBajas(){
        $bajas = SolicitudBajas::where('estatus', 'Aceptada')
            ->where('por', 'Renuncia')
            ->whereDate('created_at', '>=', Carbon::today('America/Mexico_City')->subDays(5))
            ->paginate(10);
        return view('nominas.verBajas', compact('bajas'));
    }

    public function nuevasAltas(){
        $solicitudes = SolicitudAlta::where('status', 'Aceptada')
            ->whereDate('fecha_ingreso', '>=', Carbon::today('America/Mexico_City')->subDays(5))//si se requiere respetar a toda la quincena
            ->get();
        return view('nominas.nuevasAltas', compact('solicitudes'));
    }

public function guardarCalculoFiniquito(Request $request)
{
    Log::info('Solicitud recibida para guardar imagen de finiquito.', [
        'solicitud_id' => $request->input('solicitud_id')
    ]);

    try {
        $request->validate([
            'imagen' => 'required|string',
            'solicitud_id' => 'required|integer|exists:solicitud_bajas,id',
        ]);

        $imagenBase64 = $request->input('imagen');
        $solicitudId = $request->input('solicitud_id');

        $solicitud = SolicitudBajas::find($solicitudId);
        if (!$solicitud) {
            Log::error("Solicitud con ID {$solicitudId} no encontrada.");
            return response()->json(['success' => false, 'error' => 'Solicitud no encontrada.']);
        }

        if ($solicitud->calculo_finiquito && Storage::disk('public')->exists($solicitud->calculo_finiquito)) {
            Storage::disk('public')->delete($solicitud->calculo_finiquito);
            Log::info("Archivo anterior eliminado: {$solicitud->calculo_finiquito}");
        }

        $imagen = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imagenBase64));
        if (!$imagen) {
            Log::error('No se pudo decodificar la imagen.');
            return response()->json(['success' => false, 'error' => 'No se pudo decodificar la imagen.']);
        }

        $carpeta = "solicitudesBajas/{$solicitudId}";
        Storage::disk('public')->makeDirectory($carpeta);

        $nombreArchivo = 'finiquito_' . now()->format('Ymd_His') . '.png';
        $rutaCompleta = "{$carpeta}/{$nombreArchivo}";

        Storage::disk('public')->put($rutaCompleta, $imagen);
        Log::info("Imagen guardada correctamente en: {$rutaCompleta}");

        $solicitud->calculo_finiquito = $rutaCompleta;
        $solicitud->observaciones = "Finiquito enviado a RH.";
        $solicitud->save();

        Log::info('Ruta del finiquito actualizada en la base de datos.');

        return response()->json(['success' => true, 'ruta' => $rutaCompleta]);

    } catch (\Exception $e) {
        Log::error('Error al guardar imagen de finiquito: ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => 'Error interno.']);
    }
}

    public function asistenciasNominas(){
        return view('nominas.asistencias');
    }


}
