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

    public function render()
    {
        $usuarios = User::where('estatus', 'Activo')
            ->when($this->filtroMes !== 'todos', function ($query) {
                $query->whereMonth('fecha_ingreso', $this->filtroMes);
            })
            ->when($this->filtroAnio !== 'todos', function ($query) {
                $query->whereYear('fecha_ingreso', $this->filtroAnio);
            })
            ->orderBy('fecha_ingreso')
            ->get();

        $agrupados = $usuarios->groupBy(function ($usuario) {
            return \Carbon\Carbon::parse($usuario->fecha_ingreso)->format('Y-m');
        })->map(function ($grupo) {
            return [
                'del_1_al_15' => $grupo->filter(function ($usuario) {
                    return \Carbon\Carbon::parse($usuario->fecha_ingreso)->day <= 15;
                }),
                'del_16_al_fin' => $grupo->filter(function ($usuario) {
                    return \Carbon\Carbon::parse($usuario->fecha_ingreso)->day >= 16;
                }),
            ];
        });

        if ($this->filtroQuincena === '1') {
            $agrupados = $agrupados->map(fn ($g) => ['del_1_al_15' => $g['del_1_al_15'], 'del_16_al_fin' => collect([])]);
        } elseif ($this->filtroQuincena === '2') {
            $agrupados = $agrupados->map(fn ($g) => ['del_1_al_15' => collect([]), 'del_16_al_fin' => $g['del_16_al_fin']]);
        }

        return view('livewire.antiguedades', [
            'agrupados' => $agrupados,
        ]);
    }
}
