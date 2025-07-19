<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Unidades;
use App\Models\Servicio;

class ServiciosCrud extends Component
{
    use WithPagination;

    public $showForm = false;
    public $form = [
        'unidad_id' => '',
        'fecha' => '',
        'descripcion' => '',
        'costo' => 0,
        'responsable' => '',
        'tipo' => '',
        'observaciones' => '',
    ];
    public $placasDisponibles = [];
    public $perPage = 10;
    public $filtro_unidad = '';
    public $filtro_tipo = '';

    public function mount()
    {
        $this->loadPlacasDisponibles();
    }

    public function loadPlacasDisponibles()
    {
        // Tomar placas del listado de vehículos activos
        $this->placasDisponibles = Unidades::where("estado_vehiculo", 'Activo')
            ->whereNotNull('placas')
            ->orderBy('placas')
            ->get(['id as unidad_id', 'placas as numero', 'marca', 'modelo'])->toArray();
    }

    public function showCreateForm()
    {
        $this->reset('form');
        $this->form['costo'] = 0;
        $this->showForm      = true;
    }

    public function save()
    {
        $this->validate([
            'form.unidad_id' => 'required|integer',
            'form.fecha' => 'required|date',
            'form.descripcion' => 'required|string',
            'form.costo' => 'nullable|numeric',
            'form.responsable' => 'nullable|string',
            'form.tipo' => 'required|string',
            'form.observaciones' => 'nullable|string',
        ]);

        if ($this->form['costo'] === null || $this->form['costo'] === '') {
            $this->form['costo'] = 0;
        }

        Servicio::create($this->form);

        $this->showForm = false;
        $this->reset('form');
        $this->form['costo'] = 0;
        session()->flash('success', 'Servicio creado correctamente.');
    }

    public function render()
    {
        $query = Servicio::query();

        if ($this->filtro_unidad) {
            $query->where('unidad_id', $this->filtro_unidad);
        }

        if ($this->filtro_tipo) {
            $query->where('tipo', $this->filtro_tipo);
        }

        $servicios = $query->orderByDesc('fecha')->paginate($this->perPage);

        $data = [
            'breadcrumbItems' => [
                ['icon' => 'ti-home', 'url' => route('dashboard')],
                ['icon' => 'ti-tool', 'label' => 'Servicios y Reparaciones'],
            ],
            'titleMain' => 'Servicios y Reparaciones',
            'helpText' => 'Administra y consulta el listado de servicios y reparaciones registrados para los vehículos.',
            'servicios' => $servicios,
            'showForm' => $this->showForm,
            'form' => $this->form,
            'placasDisponibles' => $this->placasDisponibles,
            'perPage' => $this->perPage,
            'filtro_unidad' => $this->filtro_unidad,
            'filtro_tipo' => $this->filtro_tipo,
        ];

        return view('livewire.servicios-crud', $data)
            ->layout('layouts.app');
    }

    public function updatingFiltroUnidad()
    {
        $this->resetPage();
    }

    public function updatingFiltroTipo()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }
}