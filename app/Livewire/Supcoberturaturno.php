<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Asistencia;
use App\Models\User;
use App\Models\Subpunto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


class Supcoberturaturno extends Component
{
    use WithPagination;

    public $search = '';
    public $fecha = null;
    protected $queryString = ['search' => ['except' => '']];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDate()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();

        $asistencias = Asistencia::query()
            ->whereNotNull('coberturas')
            ->when($user->rol === 'Supervisor', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('fecha', 'desc')
            ->get();

        $usuariosCoberturas = collect();

        foreach ($asistencias as $asistencia) {
            $coberturas = json_decode($asistencia->coberturas, true);

            if (is_array($coberturas)) {
                foreach ($coberturas as $cobertura) {
                    if (isset($cobertura['id'])) {
                        $usuario = User::find($cobertura['id']);

                        if ($usuario) {
                            $subpuntoNombre = null;
                            if (!empty($cobertura['subpunto_id'])) {
                                $subpunto = Subpunto::find($cobertura['subpunto_id']);
                                $subpuntoNombre = $subpunto?->nombre ?? 'No encontrado';
                            }

                            $usuariosCoberturas->push([
                                'usuario' => $usuario,
                                'subpunto_nombre' => $subpuntoNombre,
                                'fecha' => $asistencia->fecha,
                                'supervisor' => $asistencia->usuario->name
                            ]);
                        }
                    }
                }
            }
        }

        $usuariosFiltrados = $usuariosCoberturas->filter(function ($registro) {
            $coincideNombre = empty($this->search) || str_contains(strtolower($registro['usuario']->name), strtolower($this->search));
            $coincideFecha = empty($this->fecha) || $registro['fecha'] === $this->fecha;
            return $coincideNombre && $coincideFecha;
        })->values();

        $page = request()->get('page', 1);
        $perPage = 10;
        $paginated = new LengthAwarePaginator(
            $usuariosFiltrados->forPage($page, $perPage),
            $usuariosFiltrados->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('livewire.supcoberturaturno', [
            'coberturas' => $paginated
        ]);
    }

}
