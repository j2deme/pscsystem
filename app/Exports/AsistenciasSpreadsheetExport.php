<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Asistencia;
use App\Models\TiemposExtra;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        $asistencias = Asistencia::where('punto', $this->punto)
            ->whereBetween('fecha', [$this->fechaInicio, $this->fechaFin])
            ->get()
            ->keyBy(fn($a) => Carbon::parse($a->fecha)->format('Y-m-d'));

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

        $columnasBase = ['No.', 'Nombre','Sueldo Quincenal', 'H.Extra' , 'Sueldo Quincenal', 'FJ', 'FALTAS', 'INC', 'VACACI', 'Punto'];
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
            $currentDate->modify("+{$i} day");

            $diaIngles = $currentDate->format('l');
            $diaEspanol = $diasSemanaES[$diaIngles];
            $numeroDia = $currentDate->format('d');

            $colIndex = $baseColumnCount + ($i * 2) + 1;
            $col1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $col2 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);

            $sheet->mergeCells("{$col1}1:{$col2}1");
            $sheet->setCellValue("{$col1}1", "$diaEspanol\n$numeroDia");
            $sheet->setCellValue("{$col1}2", '');
            $sheet->setCellValue("{$col2}2", '');

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
            $totalHorasExtra = DB::table('tiempos_extras')
                ->where('user_id', $user->id)
                ->whereBetween('fecha', [$this->fechaInicio, $this->fechaFin])
                ->select(DB::raw('SUM(HOUR(total_horas)) as horas'))
                ->value('horas');
            $totalHorasExtra = $totalHorasExtra ?? 0;

            $sheet->setCellValue("A{$row}", $user->id);
            $sheet->setCellValue("B{$row}", $user->name);
            $sheet->setCellValue("C{$row}", ($this->normalize($user->rol) === 'guardia') ? '$5000.00' : '$5500.00');
            $sheet->getStyle("C{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');
            $sheet->setCellValue("D{$row}", $totalHorasExtra);
            if ($totalHorasExtra > 0) {
                $valor = (940 / 24) * $totalHorasExtra;
                $sheet->setCellValue("E{$row}", '$' . number_format($valor, 2));
                $sheet->getStyle("E{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00'); // Amarillo
            } else {
                $sheet->setCellValue("E{$row}", '0');
                $sheet->getStyle("E{$row}")->getFill()->setFillType(Fill::FILL_NONE);
            }
            $sheet->setCellValue("F{$row}", '0');
            $sheet->setCellValue("G{$row}", '0');
            $sheet->setCellValue("H{$row}", '0');
            $sheet->setCellValue("I{$row}", '0');
            $sheet->setCellValue("J{$row}", $user->punto);

            $colDia = $baseColumnCount + 1;
            $current = clone $start;
            for ($i = 0; $i < $interval; $i++) {
                $fechaStr = $current->format('Y-m-d');
                $asistencia = $asistencias->get($fechaStr);
                $asistio = false;

                if ($asistencia && $asistencia->elementos_enlistados) {
                    $enlistados = json_decode($asistencia->elementos_enlistados, true);
                    $asistio = in_array($user->id, $enlistados);
                }

                $valorCelda = $asistio ? 'A' : 'F';

                $cellCol1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colDia);
                $cellCol2 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colDia + 1);

                $sheet->setCellValue("{$cellCol1}{$row}", $valorCelda);
                $horasExtraDelDia = TiemposExtra::where('user_id', $user->id)
                    ->whereDate('fecha', $fechaStr)
                    ->get()
                    ->sum(function ($registro) {
                        return (int) Carbon::parse($registro->total_horas)->format('H');
                    });

                if ($horasExtraDelDia > 0) {
                    $sheet->setCellValue("{$cellCol2}{$row}", $horasExtraDelDia);
                    $sheet->getStyle("{$cellCol2}{$row}")->getFont()->setBold(true);
                } else {
                    $sheet->setCellValue("{$cellCol2}{$row}", '');
                }

                if (!$asistio) {
                    $sheet->getStyle("{$cellCol1}{$row}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('f5b7b1');
                }

                $colDia += 2;
                $current->modify('+1 day');
            }

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
