<?php

namespace App\Exports;

use App\Models\SolicitudVacaciones;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Writer\XLSX\Options;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\Color;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Carbon\Carbon;

class VacacionesSpreadsheetExport
{
    public function generateFile(): BinaryFileResponse
    {
        $fileName = 'vacaciones_aceptadas_' . now()->format('Y-m-d') . '.xlsx';
        $tempFilePath = sys_get_temp_dir() . '/' . uniqid('vacaciones_', true) . '.xlsx';

        $this->createExcelFile($tempFilePath);

        return response()->download(
            $tempFilePath,
            $fileName,
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        )->deleteFileAfterSend(true);
    }

    private function createExcelFile(string $filePath): void
    {
        $writer = new Writer(new Options());
        $writer->openToFile($filePath);

        $writer->addRow(Row::fromValues([
            'Punto',
            'Codigo',
            'Empleado',
            'Fecha de Ingreso',
            'Días Solicitados',
            'Fecha Inicio',
            'Fecha Fin',
            'Antigüedad',
            'Días Disponibles',
            'Dias Utilizados',
            'Observaciones'
        ]));

        SolicitudVacaciones::with(['user'])
            ->where('estatus', 'Aceptada')
            ->cursor()
            ->each(function ($solicitud) use ($writer) {
                $writer->addRow(Row::fromValues([
                    $solicitud->user->punto,
                    $solicitud->user->id,
                    $solicitud->user->name,
                    $this->formatearFecha($solicitud->user->created_at),
                    $solicitud->dias_solicitados,
                    $this->formatearFecha($solicitud->fecha_inicio),
                    $this->formatearFecha($solicitud->fecha_fin),
                    $antiguedad = (int) floor(Carbon::parse($solicitud->user->fecha_ingreso)->floatDiffInYears(now())) . ' años',
                    $diasDisponibles = $solicitud->dias_disponibles - $solicitud->solicitdados,
                    $diasUtilizados = $solicitud->dias_utilizados + $solicitud->dias_solicitados,
                    $solicitud->observaciones,
                ]));
            });
        $writer->close();
    }

    private function formatearFecha($fechaBD): string
    {
        if (!$fechaBD) return 'N/A';
        return \Carbon\Carbon::parse($fechaBD)->format('d/m/Y');
    }
}
