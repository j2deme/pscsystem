<?php

namespace App\Exports;

use App\Models\SolicitudVacaciones;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Carbon\Carbon;

class VacacionesSpreadsheetExport
{
    public function generateFile(): BinaryFileResponse
    {
        $fileName = 'VACACIONES ACTUALIZADAS ' . now()->format('d-m-Y') . '.xlsx';
        $tempFilePath = storage_path("app/public/{$fileName}");

        $this->createExcelFile($tempFilePath);

        return response()->download(
            $tempFilePath,
            $fileName,
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        )->deleteFileAfterSend(true);
    }

    private function createExcelFile(string $filePath): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'Punto', 'Codigo', 'Empleado', 'Fecha de Ingreso', 'Días Solicitados',
            'Fecha Inicio', 'Fecha Fin', 'Antigüedad', 'Días Iniciales', 'Días Utilizados', 'Días Disponibles', 'Observaciones'
        ];

        $sheet->fromArray($headers, null, 'A1');

        $headerStyle = [
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '000000']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'B0C4DE']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];

        $sheet->getStyle('A1:L1')->applyFromArray($headerStyle);

        $style1 = ['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '00b5fe']]];
        $style2 = ['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'b1fe00']]];

        $registros = SolicitudVacaciones::with('user')
            ->where('estatus', 'Aceptada')
            ->get()
            ->sortBy([['user.punto', 'asc'], ['user.name', 'asc']]);

        $row = 2;
        $previousUser = null;
        $alternate = false;

        foreach ($registros as $solicitud) {
            $user = $solicitud->user;
            $currentUser = $user->name;

            if ($currentUser !== $previousUser) {
                $alternate = !$alternate;
                $previousUser = $currentUser;
            }

            $antiguedad = (int) floor(Carbon::parse($user->fecha_ingreso)->floatDiffInYears(now())) . ' años';
            $tempAntiguedad = $antiguedad;
            if($antiguedad == '0 años')
                $tempAntiguedad = 1;
            $diasDisponibles = $solicitud->dias_disponibles - $solicitud->dias_solicitados;
            $diasUtilizados = $solicitud->dias_utilizados + $solicitud->dias_solicitados;
            $diasIniciales = $diasDisponibles + $solicitud->dias_solicitados;
            $observacion = 'El trabajador disfrutará de'.' '. $solicitud->dias_solicitados.' '. 'días de vacaciones, quedando disponibles'. ' '. $diasDisponibles.' '. 'días del periodo'. ' '. $tempAntiguedad. '.';


            $sheet->fromArray([
                $user->punto,
                $user->id,
                $user->name,
                $this->formatearFecha($user->fecha_ingreso),
                $solicitud->dias_solicitados,
                $this->formatearFecha($solicitud->fecha_inicio),
                $this->formatearFecha($solicitud->fecha_fin),
                $antiguedad,
                $diasIniciales,
                $diasUtilizados,
                $diasDisponibles,
                $observacion,
            ], null, "A{$row}");

            $sheet->getStyle("A{$row}:L{$row}")->applyFromArray($alternate ? $style1 : $style2);
            $row++;
        }

        foreach (range('A', 'L') as $col) {
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
