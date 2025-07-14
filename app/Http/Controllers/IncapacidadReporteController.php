<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Incapacidad;

class IncapacidadReporteController extends Controller
{
     public function generarPdf()
    {
        // 1. Obtener los datos (la lógica sigue siendo la misma)
        $incapacidades = Incapacidad:: select(
                                'user_id',
                                'motivo',
                                'tipo_incapacidad',
                                'ramo_seguro',
                                'dias_incapacidad',
                                'fecha_inicio',
                                'folio'
                            )
                            ->orderBy('fecha_inicio', 'desc')
                            ->get();
         $incapacidades = $incapacidades->map(function($incapacidad) {
             $user = User::find($incapacidad->user_id);

            // Asignar el nombre si el usuario se encuentra, si no, "Usuario Desconocido"
            $incapacidad->user_name = $user ? $user->name : 'Usuario Desconocido';

            // Convertir el objeto Incapacidad mapeado a un array para Dompdf
            return $incapacidad->toArray();
        });
        $data = [
            'incapacidades' => $incapacidades,
        ];

        // Generar el PDF usando Dompdf
        $pdf = PDF::loadView('pdf.incapacidades_pdf', $data);

        // Devolver el PDF
        return $pdf->stream('reporte_incapacidades.pdf');
    }
}

