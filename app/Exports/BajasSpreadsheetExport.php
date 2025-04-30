<?php

namespace App\Exports;

use App\Models\User;
use App\Models\SolicitudBajas;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BajasSpreadsheetExport
{
    public function generateFile(): BinaryFileResponse
    {
        $fileName = 'ARCHIVOROJO_' . now()->format('d-m-Y') . '.xlsx';
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

    $headers = ['CODIGO', 'NOMBRE', 'DEPARTAMENTO', 'CURP', 'RFC', 'NSS', 'ALTA', 'SUELDO', 'MOTIVO', 'FECHA BAJA', 'MOTIVO DE BAJA', 'TELEFONO'];
    $sheet->fromArray($headers, null, 'A1');

    $headerStyle = [
        'font' => [
            'bold' => true,
            'size' => 13,
            'color' => ['rgb' => '000000'],
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'ABB2B9'],
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
        ],
    ];
    $sheet->getStyle('A1:L1')->applyFromArray($headerStyle);

    $row = 2;

    User::with(['solicitudAlta', 'solicitudBaja'])
        ->orderBy('id')
        ->cursor()
        ->each(function ($user) use ($sheet, &$row) {
            $baja = $user->estatus === 'Inactivo' ? $user->solicitudBajas->sortByDesc('created_at')->first() : null;

            $sheet->fromArray([
                $user->id,
                $user->name,
                $user->punto,
                $user->solicitudAlta->curp ?? 'N/A',
                $user->solicitudAlta->rfc ?? 'N/A',
                $user->solicitudAlta->nss ?? 'N/A',
                $user->fecha_ingreso ? \Carbon\Carbon::parse($user->fecha_ingreso)->format('d/m/Y') : 'N/A',
                '',
                $baja->por ?? '',
                $baja && $baja->fecha_baja ? \Carbon\Carbon::parse($user->fecha_baja)->format('d/m/Y') : 'N/A',
                $baja->motivo ?? '',
                $user->solicitudAlta->telefono ?? 'N/A',
            ], null, "A{$row}");

            if ($user->estatus === 'Inactivo') {
                $sheet->getStyle("A{$row}:L{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FE0000'],
                    ],
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                        'color' => ['rgb' => '000000'],
                    ],
                ]);
            }

            $row++;
        });

    foreach (range('A', 'L') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $writer = new Xlsx($spreadsheet);
    $writer->save($filePath);
}
}
