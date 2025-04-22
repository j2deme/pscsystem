<?php

namespace App\Exports;

use App\Models\SolicitudBajas;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Writer\XLSX\Options;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\Color;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BajasSpreadsheetExport
{
    public function generateFile(): BinaryFileResponse
{
    $fileName = 'bajas_aceptadas_' . now()->format('Y-m-d') . '.xlsx';

    $tempFilePath = sys_get_temp_dir() . '/' . uniqid('bajas_', true) . '.xlsx';

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

    // Estilo para encabezados
    $headerStyle = (new Style())
        ->setFontBold()
        ->setFontSize(12)
        ->setFontColor(Color::WHITE)
        ->setBackgroundColor('004080')
        ->setCellAlignment(CellAlignment::CENTER);

    // Encabezados
    $writer->addRow(
        Row::fromValues(['ID', 'NOMBRE', 'EMAIL', 'ROL', 'FECHA BAJA', 'MOTIVO'])
            ->setStyle($headerStyle)
    );

    // Datos
    SolicitudBajas::with('user')
        ->where('estatus', 'Aceptada')
        ->cursor()
        ->each(function ($baja) use ($writer) {
            $writer->addRow(Row::fromValues([
                $baja->id,
                $baja->user->name,
                $baja->user->email,
                $baja->user->rol,
                $baja->created_at->format('d/m/Y'),
                $baja->motivo
            ]));
        });

    $writer->close();
}
}
