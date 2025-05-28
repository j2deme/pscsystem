<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Admigestionusuarios extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function render()
    {
        $baseQuery = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            });

        if (!(Auth::user()->rol == 'admin' ||
            in_array(Auth::user()->solicitudAlta->rol ?? '', [
                'AUXILIAR RECURSOS HUMANOS', 'AUXILIAR RH', 'AUX RH',
                'Auxiliar RH', 'Auxiliar Recursos Humanos', 'Aux RH', 'AUXILIAR NOMINAS', 'Auxiliar Nominas',
                'AUX NOMINAS', 'Aux Nominas', 'Auxiliar nóminas'
            ]) ||
            Auth::user()->solicitudAlta->departamento == 'Recursos Humanos')) {
            $baseQuery->where('punto', Auth()->user()->punto)
                    ->where('empresa', Auth()->user()->empresa)
                    ->where('rol', '!=', 'Supervisor');
        }

        $users = $baseQuery->get();

        $users = $users->map(function ($user) {
            $tipoEmpleado = $user->solicitudAlta?->tipo_empleado;
            $documentacion = $user->solicitudAlta?->documentacion;

            $documentosBase = [
                'arch_ine', 'arch_solicitud_empleo', 'arch_curp', 'arch_rfc', 'arch_nss',
                'arch_acta_nacimiento', 'arch_comprobante_estudios', 'arch_comprobante_domicilio',
                'arch_carta_rec_laboral', 'arch_carta_rec_personal',
            ];
            $documentosExtraArmado = ['arch_cartilla_militar', 'arch_carta_no_penales', 'arch_antidoping'];

            $documentos = $tipoEmpleado === 'armado'
                ? array_merge($documentosBase, $documentosExtraArmado)
                : $documentosBase;

            $completados = 0;
            foreach ($documentos as $campo) {
                if (!empty($documentacion?->$campo)) {
                    $completados++;
                }
            }

            $total = count($documentos);
            $user->progreso_documentos = $total > 0 ? round(($completados / $total) * 100) : 0;

            return $user;
        });

        if ($this->sortField === 'progreso_documentos') {
            $users = $this->sortDirection === 'asc'
                ? $users->sortBy('progreso_documentos')
                : $users->sortByDesc('progreso_documentos');
        } else {
            $users = $this->sortDirection === 'asc'
                ? $users->sortBy($this->sortField)
                : $users->sortByDesc($this->sortField);
        }

        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $users->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedUsers = new LengthAwarePaginator(
            $currentItems,
            $users->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('livewire.admigestionusuarios', [
            'users' => $paginatedUsers,
        ]);
    }

}
