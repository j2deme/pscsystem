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
        $this->filtroAnio = $this->filtroAnio === 'todos' ? $hoy->year : $this->filtroAnio;
        $this->filtroQuincena = $this->filtroQuincena === 'todas' ? ($hoy->day <= 15 ? '1' : '2') : $this->filtroQuincena;
    }
    public function render()
{
    $usuarios = User::where('estatus', 'Activo')
        ->when($this->filtroMes !== 'todos', function ($query) {
            $query->whereMonth('fecha_ingreso', $this->filtroMes);
        })
        ->when($this->filtroAnio !== 'todos', function ($query) {
            $query->whereYear('fecha_ingreso', $this->filtroAnio);
        })
        ->orderBy('empresa', 'asc')
        ->get()
        ->filter(function ($usuario) {
            $dia = Carbon::parse($usuario->fecha_ingreso)->day;
            if ($this->filtroQuincena === '1') return $dia <= 15;
            if ($this->filtroQuincena === '2') return $dia >= 16;
            return true;
        });

    return view('livewire.antiguedades', [
        'usuarios' => $usuarios,
    ]);
}
}
