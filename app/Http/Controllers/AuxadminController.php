<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudAlta;
use App\Models\DocumentacionAltas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AuxadminController extends Controller
{
    public function nuevasAltas(){
        $solicitudes = SolicitudAlta::where('status', 'Aceptada')
            ->whereDate('fecha_ingreso', '>=', Carbon::today('America/Mexico_City')->subDays(7))
            ->whereNull('sd')
            ->whereNull('sdi')
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
                'sd' => 'nullable|numeric',
                'sdi' => 'nullable|numeric',
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

            $solicitud->sd = $request->sd;
            $solicitud->sdi = $request->sdi;
            $solicitud->save();

            return response()->json(['success' => true]);

        } catch (\Throwable $e) {
            Log::error("Error general en guardarArchivosAlta: " . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al guardar los archivos.'], 500);
        }
    }

    public function listadoUsuarios(){
        $users = User::where('estatus', 'Activo')
            ->paginate(10);
        return view('auxadmin.listadoUsuarios', compact('users'));
    }

    public function actualizarAcuses(Request $request, $id)
    {
        try {
            $request->validate([
                'arch_acuse_imss' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'arch_retencion_infonavit' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'arch_modificacion_salario' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'sd' => 'nullable|numeric',
                'sdi' => 'nullable|numeric',
            ]);
            $solDocs= DocumentacionAltas::where('id', $id)->first();
            $solAlta = SolicitudAlta::where('id', $solDocs->solicitud_id)->first();
            Log::debug("Valores recibidos - SD: {$request->sd}, SDI: {$request->sdi}, IdSolicitud: {$solDocs->solicitud_id}");
            $solicitud = SolicitudAlta::findOrFail($solAlta->id);
            $solicitudId = $id;
            $solicitud->sd = $request->sd;
            $solicitud->sdi = $request->sdi;
            $solicitud->save();
            $solicitud->refresh();
            Log::debug("Después de guardar: SD={$solicitud->sd}, SDI={$solicitud->sdi}, ID={$solAlta->id}");

            $documentacion = DocumentacionAltas::firstOrNew(['id' => $solDocs->id]);
            $carpeta = 'solicitudesAltas/' . $solAlta->id;

            $archivos = [
                'arch_acuse_imss',
                'arch_retencion_infonavit',
                'arch_modificacion_salario',
            ];

            Log::debug("Antes de guardar archivos de guardar: ID={$solDocs->id}");
            foreach ($archivos as $campo) {
                if ($request->hasFile($campo)) {
                    try {
                        if ($documentacion->$campo && Storage::exists(str_replace('storage/', '', $documentacion->$campo))) {
                            Storage::delete(str_replace('storage/', '', $documentacion->$campo));
                        }

                        $archivo = $request->file($campo);
                        $nombreArchivo = $campo . '.' . $archivo->getClientOriginalExtension();
                        $ruta = $archivo->storeAs($carpeta, $nombreArchivo, 'public');
                        $documentacion->$campo = 'storage/' . $ruta;
                         Log::debug("Archivo {$campo} guardado en: storage/{$ruta}");
                    } catch (\Exception $e) {
                        Log::error("Error al guardar el archivo {$campo}: " . $e->getMessage());
                    }
                }
            }
            $documentacion->save();

            return response()->json(['success' => true]);

        } catch (\Throwable $e) {
            Log::error("Error general en guardarAcuses: " . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al guardar los archivos.'], 500);
        }
    }

}
