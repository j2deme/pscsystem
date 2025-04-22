<?php

namespace App\Exports;

use App\Models\User;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Writer\XLSX\Options;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\Color;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AltasSpreadsheetExport
{
    public function generateFile(): BinaryFileResponse
    {
        $fileName = 'altas_usuarios_' . now()->format('Y-m-d') . '.xlsx';
        $tempFilePath = sys_get_temp_dir() . '/' . uniqid('altas_', true) . '.xlsx';

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

        $headerStyle = (new Style())
            ->setFontBold()
            ->setFontSize(12)
            ->setFontColor(Color::WHITE)
            ->setBackgroundColor(Color::PINK)
            ->setCellAlignment(CellAlignment::CENTER);

        $writer->addRow(
            Row::fromValues([
                'ID USUARIO',
                'NOMBRE',
                'EMAIL',
                'ROL',
                'FECHA ALTA',
                'TELEFONO',
                'NSS',
                'RFC'
            ])->setStyle($headerStyle)
        );

        User::with('solicitudAlta')
            ->whereHas('solicitudAlta')
            ->cursor()
            ->each(function ($user) use ($writer) {
                $writer->addRow(Row::fromValues([
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->rol,
                    $user->created_at->format('d/m/Y'),
                    $user->solicitudAlta->telefono ?? 'N/A',
                    $user->solicitudAlta->nss ?? 'N/A',
                    $user->solicitudAlta->rfc ?? 'N/A'
                ]));
            });

        $writer->close();
    }
}
