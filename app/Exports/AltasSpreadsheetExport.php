<?php

namespace App\Exports;

use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Carbon\Carbon;

class AltasSpreadsheetExport
{
    public function generateFile(): BinaryFileResponse
    {
        $fileName = 'ARCHIVOROSA_' . now()->format('Y-m-d') . '.xlsx';
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
        $sheet->setTitle('ALTAS 2025');

        $usuarios = User::with('solicitudAlta')
            ->whereHas('solicitudAlta')
            ->orderBy('fecha_ingreso')
            ->get()
            ->groupBy(fn($user) => $user->fecha_ingreso ? Carbon::parse($user->fecha_ingreso)->format('Y-m-d') : 'Sin Fecha')
            ->sortKeys();

        $row = 1;

        $headers1 = [
            'PUNTO', 'EMPRESA', 'NOMBRE', 'ALTA', 'FECHA DE NACIMIENTO', 'ESTADO CIVIL', 'DOMICILIO',
            'CURP', 'RFC', 'NSS', 'INFONAVIT', '', 'TIPO DE DESCUENTO', 'IMPORTE', 'FONACOT:', '', 'SUELDO', 'TELEFONO', 'REINGRESO', '', 'VACANTE',
            'SD', 'SDI', 'CORREO'
        ];

        $headers2 = [
            '', '', '', '', '', '', '', '', '', '',
            '', '', '', 'SI', 'NO', '', '', 'SI', 'NO', '', '', 'SI', 'NO', '', ''
        ];

        $sheet->fromArray($headers1, null, "A{$row}");
        $sheet->fromArray($headers2, null, "A" . ($row + 1));

        $sheet->getStyle("A{$row}:AE" . ($row + 1))->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFC0FB']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
        ]);

        $mergeColumns = [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
            'K', 'L', 'M', 'P', 'Q', 'R', 'U', 'V', 'W', 'Y',
            'Z', 'AA', 'AB', 'AC', 'AD', 'AE'
        ];

        foreach ($mergeColumns as $col) {
            $sheet->mergeCells("{$col}{$row}:{$col}" . ($row + 1));
        }

        $sheet->mergeCells("N{$row}:O{$row}"); // INFONAVIT
        $sheet->mergeCells("S{$row}:T{$row}"); // FONACOT
        $sheet->mergeCells("V{$row}:W{$row}"); // REINGRESO

        $row += 2;

        foreach ($usuarios as $fecha => $grupo) {
            $titulo = $fecha === 'Sin Fecha'
                ? 'ALTAS SIN FECHA DE INGRESO'
                : 'ALTAS DEL ' . Carbon::parse($fecha)->translatedFormat('d \d\e F \d\e\l Y');

            $sheet->setCellValue("A{$row}", $titulo);
            $sheet->mergeCells("A{$row}:AE{$row}");
            $sheet->getStyle("A{$row}")->applyFromArray([
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D3D3D3']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            ]);
            $row++;

            foreach ($grupo as $user) {
                $sheet->fromArray([
                    $user->punto,
                    $user->empresa ?? '',
                    $user->name,
                    $user->fecha_ingreso ? Carbon::parse($user->fecha_ingreso)->format('d/m/Y') : '',
                    $user->solicitudAlta->fecha_nacimiento ? Carbon::parse($user->solicitudAlta->fecha_nacimiento)->format('d/m/Y') : '',
                    $user->solicitudAlta->estado_civil ?? '',
                    $user->solicitudAlta->domicilio_comprobante ?? '',
                    $user->solicitudAlta->curp ?? '',
                    $user->solicitudAlta->rfc ?? '',
                    $user->solicitudAlta->nss ?? '',
                    optional($user->solicitudAlta)->infonavit == 'SI' ? '✔' : '',
                    optional($user->solicitudAlta)->infonavit == 'NO' ? '✔' : '',
                    optional($user->solicitudAlta)->tipo_descuento ?? '',
                    optional($user->solicitudAlta)->importe_descuento ?? '',
                    optional($user->solicitudAlta)->fonacot == 'SI' ? '✔' : '',
                    optional($user->solicitudAlta)->fonacot == 'NO' ? '✔' : '',
                    optional($user->solicitudAlta)->sueldo ?? '',
                    $user->solicitudAlta->telefono ?? '',
                    optional($user->solicitudAlta)->reingreso == 'SI' ? '✔' : '',
                    optional($user->solicitudAlta)->reingreso == 'NO' ? '✔' : '',
                    optional($user->solicitudAlta)->vacante ?? '',
                    optional($user->solicitudAlta)->sd ?? '',
                    optional($user->solicitudAlta)->sdi ?? '',
                    $user->email ?? '',
                ], null, "A{$row}");
                $row++;
            }

        }

        foreach (range('A', 'Z') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        foreach (range('AA', 'AE') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);
    }
}
