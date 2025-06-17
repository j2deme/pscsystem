<?php

namespace App\Livewire;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class Antiguedades extends Component
{
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

        return view('livewire.antiguedades', [
            'usuarios' => $usuarios,
        ]);
    }
}
