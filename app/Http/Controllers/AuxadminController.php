<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudAlta;
use App\Models\DocumentacionAltas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AuxadminController extends Controller
{
    public function nuevasAltas(){
        $solicitudes = SolicitudAlta::where('status', 'Aceptada')
            ->whereDate('fecha_ingreso', '>=', Carbon::today('America/Mexico_City')->subDays(5))
            ->whereHas('documentacion', function ($q) {
                $q->whereNull('arch_acuse_imss');
            })
            ->get();

        return view('auxadmin.nuevasAltas', compact('solicitudes'));
    }

    public function guardarAcuses(Request $request, $id)
{
    try {
        $request->validate([
            'arch_acuse_imss' => 'nullable|file',
            'arch_retencion_infonavit' => 'nullable|file',
        ]);

        $solicitud = SolicitudAlta::findOrFail($id);
        $solicitudId = $id;
        $documentacion = DocumentacionAltas::firstOrNew(['solicitud_id' => $solicitudId]);
        $carpeta = 'solicitudesAltas/' . $solicitudId;

        $archivos = [
            'arch_acuse_imss',
            'arch_retencion_infonavit',
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

        return response()->json(['success' => true]);

    } catch (\Throwable $e) {
        Log::error("Error general en guardarArchivosAlta: " . $e->getMessage());
        return response()->json(['error' => 'Ocurrió un error al guardar los archivos.'], 500);
    }
}
}
