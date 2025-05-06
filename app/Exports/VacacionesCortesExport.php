<?php

namespace App\Exports;

use App\Models\SolicitudVacaciones;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Carbon\Carbon;

class VacacionesCortesExport
{
    public function generateFile(string $inicio, string $fin): BinaryFileResponse
    {
        $fileName = 'VACACIONES POR CORTE ' . now()->format('d-m-Y') . '.xlsx';
        $tempFilePath = storage_path("app/public/{$fileName}");

        $this->createExcelFile($tempFilePath, $inicio, $fin);

        return response()->download(
            $tempFilePath,
            $fileName,
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        )->deleteFileAfterSend(true);
    }

    private function createExcelFile(string $filePath, string $inicio, string $fin): void
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $titulo = "ALTAS DEL " . Carbon::parse($inicio)->format('d/m/Y') . " A " . Carbon::parse($fin)->format('d/m/Y');
    $sheet->mergeCells('A1:I1');
    $sheet->setCellValue('A1', $titulo);
    $sheet->getStyle('A1')->applyFromArray([
        'font' => ['bold' => true, 'size' => 14],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    ]);

    $headers = [
        'Punto', 'Código', 'Empleado', 'Días Solicitados',
        'Fecha Inicio', 'Fecha Fin', 'Días Iniciales', 'Días Utilizados', 'Días Disponibles'
    ];

    $sheet->fromArray($headers, null, 'A2');

    $headerStyle = [
        'font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => '000000']],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E5BE01']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    ];

    $sheet->getStyle('A2:I2')->applyFromArray($headerStyle);

    $registros = SolicitudVacaciones::with('user')
        ->where('estatus', 'Aceptada')
        ->whereDate('created_at', '>=', $inicio)
        ->whereDate('created_at', '<=', $fin)
        ->get()
        ->sortBy([['user.punto', 'asc'], ['user.name', 'asc']]);

    $row = 3;

    foreach ($registros as $solicitud) {
        $user = $solicitud->user;
        $diasDisponibles = $solicitud->dias_disponibles - $solicitud->dias_solicitados;
        $diasUtilizados = $solicitud->dias_utilizados + $solicitud->dias_solicitados;
        $diasIniciales = $diasDisponibles + $solicitud->dias_solicitados;

        $sheet->fromArray([
            $user->punto,
            $user->id,
            $user->name,
            $solicitud->dias_solicitados,
            $this->formatearFecha($solicitud->fecha_inicio),
            $this->formatearFecha($solicitud->fecha_fin),
            $diasIniciales,
            $diasUtilizados,
            $diasDisponibles,
        ], null, "A{$row}");

        $row++;
    }

    foreach (range('A', 'I') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $writer = new Xlsx($spreadsheet);
    $writer->save($filePath);
}

    private function formatearFecha($fechaBD): string
    {
        if (!$fechaBD) return 'N/A';
        return Carbon::parse($fechaBD)->format('d/m/Y');
    }
}
