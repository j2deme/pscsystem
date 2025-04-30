<?php

namespace App\Exports;

use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

        $usuarios = User::with('solicitudAlta')
            ->whereHas('solicitudAlta')
            ->orderBy('created_at')
            ->get()
            ->groupBy(fn($user) => $user->created_at->format('Y-m-d'));

        $row = 1;

        foreach ($usuarios as $fecha => $grupo) {
            $titulo = 'ALTAS ' . \Carbon\Carbon::parse($fecha)->translatedFormat('d \d\e F \d\e\l Y');
            $sheet->setCellValue("A{$row}", $titulo);
            $sheet->mergeCells("A{$row}:I{$row}");
            $sheet->getStyle("A{$row}")->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 13,
                    'color' => ['rgb' => '000000']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFC0CB']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ]);
            $row++;

            $sheet->fromArray([
                'CODIGO', 'NOMBRE', 'EMAIL', 'PUESTO', 'PUNTO', 'FECHA ALTA', 'TELEFONO', 'NSS', 'RFC'
            ], null, "A{$row}");

            $sheet->getStyle("A{$row}:I{$row}")->getFont()->setBold(true);
            $sheet->getStyle("A{$row}:I{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $row++;

            foreach ($grupo as $user) {
                $sheet->fromArray([
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->rol,
                    $user->solicitudAlta->punto ?? 'N/A',
                    $user->created_at->format('d/m/Y'),
                    $user->solicitudAlta->telefono ?? 'N/A',
                    $user->solicitudAlta->nss ?? 'N/A',
                    $user->solicitudAlta->rfc ?? 'N/A'
                ], null, "A{$row}");
                $row++;
            }

            $row++;
        }

        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);
    }
}
