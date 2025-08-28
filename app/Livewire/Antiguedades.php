<?php

namespace App\Livewire;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;


class Antiguedades extends Component
{
    use WithPagination;

    public $filtroQuincena = 'todas';
    public $filtroMes = 'todos';
    public $filtroAnio = 'todos';
    public $usuariosFiltrados;

    public function mount()
    {
        $hoy = Carbon::now();

        $this->filtroMes = $this->filtroMes === 'todos' ? $hoy->month : $this->filtroMes;
        $this->filtroQuincena = $this->filtroQuincena === 'todas' ? ($hoy->day <= 15 ? '1' : '2') : $this->filtroQuincena;
    }

    public function render()
    {
        $usuarios = User::where('estatus', 'Activo')
            ->whereMonth('fecha_ingreso', $this->filtroMes)
            ->get()
            ->filter(function ($usuario) {
                $fechaIngreso = Carbon::parse($usuario->fecha_ingreso);
                $dia = $fechaIngreso->day;
                $antiguedad = $fechaIngreso->diffInYears(Carbon::now());

                if ($antiguedad < 1) {
                    return false;
                }

                return match ($this->filtroQuincena) {
                    '1' => $dia >= 1 && $dia <= 15,
                    '2' => $dia >= 16,
                    default => true,
                };
            });

        // Convertir la colección filtrada a una instancia de LengthAwarePaginator
        $currentPage = request()->get('page', 1);
        $perPage = 10;
        $currentPageItems = $usuarios->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedUsers = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $usuarios->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );

        return view('livewire.antiguedades', [
            'usuarios' => $paginatedUsers,
        ]);
    }

    public function generarExcel()
    {
        $usuarios = $this->obtenerUsuariosFiltrados();


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $sheet->fromArray([
            ['No.', 'Empresa', 'Nombre', 'Sueldo', 'Fecha Ingreso', 'Antigüedad', 'Días', 'Salario Diario', '$ Vacaciones', 'Prima Vacacional']
        ], null, 'A1');

        $row = 2;

        foreach ($usuarios as $index => $usuario) {
            $fechaIngreso = \Carbon\Carbon::parse($usuario->fecha_ingreso);
            $antiguedad = $fechaIngreso->diff(now());

            $diasVacaciones = match (true) {
                $antiguedad->y < 2 => 12,
                $antiguedad->y === 2 => 14,
                $antiguedad->y === 3 => 16,
                $antiguedad->y === 4 => 18,
                $antiguedad->y === 5 => 20,
                $antiguedad->y > 5 && $antiguedad->y <= 10 => 22,
                $antiguedad->y > 10 && $antiguedad->y <= 15 => 24,
                $antiguedad->y > 15 && $antiguedad->y <= 20 => 26,
                $antiguedad->y > 20 && $antiguedad->y <= 25 => 28,
                $antiguedad->y > 25 && $antiguedad->y <= 30 => 30,
                default => 32,
            };

            $rawSueldo = $usuario->solicitudAlta->sueldo_mensual ?? '0';

            if (preg_match('/\((.*?)\)/', $rawSueldo, $matches)) {
                $soloNumero = preg_replace('/[^0-9.]/', '', $matches[1]);
            } else {
                $soloNumero = preg_replace('/[^0-9.]/', '', $rawSueldo);
            }

            $salario = floatval($soloNumero) / 2;
            $salarioDiario = $salario > 0 ? round($salario / 15, 2) : 0;
            $prima = round($salarioDiario * $diasVacaciones * 0.25, 2);
            $vacacionesMonto = $diasVacaciones * $salarioDiario;

            $sheet->fromArray([
                $index + 1,
                $usuario->empresa ?? '—',
                $usuario->name,
                number_format($salario, 2),
                $fechaIngreso->format('d/m/Y'),
                $antiguedad->y . ' ' . ($antiguedad->y == 1 ? 'Año' : 'Años'),
                $diasVacaciones,
                number_format($salarioDiario, 2),
                number_format($vacacionesMonto, 2),
                number_format($prima, 2),
            ], null, 'A' . $row);

            $row++;
        }

        $fileName = 'antiguedades_' . now()->format('Y_m_d_H_i_s') . '.xlsx';
        $filePath = 'public/excel/' . $fileName;

        $writer = new Xlsx($spreadsheet);
        Storage::put($filePath, '');
        $tempPath = Storage::path($filePath);
        $writer->save($tempPath);

        return response()->download($tempPath)->deleteFileAfterSend(true);
    }

    public function obtenerUsuariosFiltrados()
    {
        return User::where('estatus', 'Activo')
            ->whereMonth('fecha_ingreso', $this->filtroMes)
            ->get()
            ->filter(function ($usuario) {
                $fechaIngreso = Carbon::parse($usuario->fecha_ingreso);
                $dia = $fechaIngreso->day;
                $antiguedad = $fechaIngreso->diffInYears(Carbon::now());

                if ($antiguedad < 1) {
                    return false;
                }

                return match ($this->filtroQuincena) {
                    '1' => $dia >= 1 && $dia <= 15,
                    '2' => $dia >= 16,
                    default => true,
                };
            });
    }

    public function updatedFiltroQuincena()
    {
        $this->resetPage();
    }

    public function updatedFiltroMes()
    {
        $this->resetPage();
    }
}
