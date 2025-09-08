<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudBajas;

class JuridicoController extends Controller
{
    public function listaNuevasBajas(){
        $solicitudes = SolicitudBajas::where('estatus', 'Aceptada')
            ->whereDate('fecha_baja', '>=', now()->subDays(15))
            ->orderBy('fecha_baja', 'desc')
            ->paginate(10);

        return view('juridico.listaNuevasBajas', compact('solicitudes'));
    }

    public function actualizarMotivoBaja(Request $request)
    {
        try {
            $request->validate([
                'solicitud_id' => 'required|exists:solicitud_bajas,id',
                'nuevo_motivo' => 'required|in:Renuncia,Ausentismo,Separación Voluntaria'
            ]);

            $solicitud = SolicitudBajas::findOrFail($request->solicitud_id);
            $solicitud->por = $request->nuevo_motivo;
            $solicitud->save();

            return response()->json(['success' => true, 'message' => 'Motivo actualizado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Error al actualizar el motivo: ' . $e->getMessage()], 500);
        }
    }
}
