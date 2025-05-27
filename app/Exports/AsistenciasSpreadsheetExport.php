<?php

namespace App\Exports;

use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\DB;

class AsistenciasSpreadsheetExport
{
    protected $punto;
    protected $fechaInicio;
    protected $fechaFin;

    public function __construct($punto = null, $fechaInicio = null, $fechaFin = null)
    {
        $this->punto = strtoupper($punto);
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }

    public function generateFile()
    {
        $subpuntos = $this->getSubpuntosPorPunto($this->punto);

        $usuarios = User::where('estatus', 'Activo')
            ->when($this->punto, function ($query) use ($subpuntos) {
                $query->where(function ($q) use ($subpuntos) {
                    foreach ($subpuntos as $subpunto) {
                        $q->orWhereRaw('LOWER(punto) LIKE ?', ['%' . strtolower($subpunto) . '%']);
                    }
                });
            })
            ->get()
            ->filter(function ($user) {
                $rol = $this->normalize($user->rol);
                return in_array($rol, ['patrullero', 'guardia']);
            });

        $startDate = new \DateTime($this->fechaInicio);
        $endDate = new \DateTime($this->fechaFin);
        $interval = $startDate->diff($endDate)->days + 1;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $columnasBase = ['No.', 'Nombre','Sueldo Quincenal', 'T.Extra Horas' , 'Sueldo Quincenal', 'FJ', 'FALTAS', 'INC', 'VACACI', 'Punto'];
        $baseColumnCount = count($columnasBase);

        foreach ($columnasBase as $index => $title) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 1);
            $sheet->setCellValue("{$col}1", $title);
            $sheet->mergeCells("{$col}1:{$col}2");

            $style = [
                'font' => ['name' => 'Century Gothic', 'size' => 9, 'bold' => true],
                'alignment' => ['vertical' => 'center', 'horizontal' => 'center'],
            ];

            if ($title === strtoupper($title) && preg_match('/[A-Z]/', $title)) {
                $style['alignment']['textRotation'] = 90;
            }

            $sheet->getStyle("{$col}1")->applyFromArray($style);
        }

        $diasSemanaES = [
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado',
            'Sunday' => 'Domingo',
        ];

        $start = new \DateTime($this->fechaInicio);
        $end = new \DateTime($this->fechaFin);
        $interval = $start->diff($end)->days + 1;

        for ($i = 0; $i < $interval; $i++) {
            $currentDate = clone $start;
            $currentDate->modify("+$i day");

            $diaIngles = $currentDate->format('l');
            $diaEspanol = $diasSemanaES[$diaIngles];
            $numeroDia = $currentDate->format('d');

            $colIndex = $baseColumnCount + ($i * 2) + 1;
            $col1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $col2 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);

            $sheet->setCellValue("{$col1}1", "{$diaEspanol}\n{$numeroDia}");
            $sheet->mergeCells("{$col1}1:{$col2}2");

            $sheet->getStyle("{$col1}1:{$col2}2")->applyFromArray([
                'font' => ['name' => 'Century Gothic', 'size' => 9, 'bold' => true],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                    'wrapText' => true,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FDDDCA'],
                ],
            ]);

            $sheet->getColumnDimension($col1)->setWidth(5);
            $sheet->getColumnDimension($col2)->setWidth(5);
        }

        $row = 3;
        foreach ($usuarios as $user) {
            $sheet->setCellValue("A{$row}", $user->id);
            $sheet->setCellValue("B{$row}", $user->name);
            if($user->rol == 'Guardia' || $user->rol == 'GUARDIA')
                $sheet->setCellValue("C{$row}", '$5000.00');
            else
                $sheet->setCellValue("C{$row}", '$5500.00'); //Patrullero
            $sheet->setCellValue("D{$row}", '0');
            $sheet->setCellValue("E{$row}", '$          -');
            $sheet->setCellValue("F{$row}", '0');
            $sheet->setCellValue("G{$row}", '0');
            $sheet->setCellValue("H{$row}", '0');
            $sheet->setCellValue("I{$row}", '0');
            $sheet->setCellValue("J{$row}", $user->punto);
            $row++;
        }

        foreach (range('A', \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($baseColumnCount)) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'asistencias_filtradas.xlsx';
        $tempPath = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempPath);

        return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
    }

    protected function getSubpuntosPorPunto($punto)
    {
        $map = [
            'MONTERREY' => [
                'MONTERREY','CUSTODIO', 'DAL TILE', 'TORRE NOVO', 'TRASLADOS',
                'BONETERA', 'HOME DEPO', 'AMERICAN AIRLINES',
                'MARY KAY CORPORATIVO', 'KANSAS', 'CIMARRON', 'OFICINA',
                'ASSET', 'TORRE DELTA', 'SACMI DE MEXICO',
                'THERMO ELÉCTRICA', 'KINDEER MORGAN', 'GOBAR',
                'PEMCORP #2', 'ROCHE BOBOIS', 'OFF ON GREEN',
                'COOPER LIGHT', 'MONTE PALATINO', 'OATEY', 'PLAZA DOMENA'
            ],
            'GUANAJUATO' => ['SILAO', 'CELAYA', 'SALAMANCA'],
            'NUEVO LAREDO' => ['ZONA DE ABASTOS V'],
            'MEXICO' => ['VALLE DE MEXICO'],
            'SLP' => ['WATCO', 'BMW', 'ZONA DE ABASTOS I', 'INTERPUERTO Y TALLER'],
            'XALAPA' => ['XALAPA'],
            'MICHOACAN' => ['MICHOACÁN'],
            'PUEBLA' => ['PUEBLA'],
            'TOLUCA' => ['TOLUCA'],
            'QUERETARO' => ['QUERÉTARO'],
            'SALTILLO' => ['SALTILLO'],
            'DRONES' => ['DRONES'],
            'KANSAS' => ['KANSAS'],
        ];

        return $map[$punto] ?? [];
    }

    protected function normalize($string)
    {
        return strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $string));
    }
}
