<?php

namespace App\Livewire;

use Livewire\Component;

class ServiciosCrud extends Component
{
    public $servicios;
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

    public function mount()
    {
        $this->loadServicios();
        $this->loadPlacasDisponibles();

    }

    public function loadPlacasDisponibles()
    {
        // Tomar placas del listado de vehículos activos
        $this->placasDisponibles = \App\Models\Unidades::where("estado_vehiculo", 'Activo')
            ->whereNotNull('placas')
            ->orderBy('placas')
            ->get(['id as unidad_id', 'placas as numero', 'marca', 'modelo'])->toArray();
    }

    public function loadServicios()
    {
        $this->servicios = \App\Models\Servicio::orderByDesc('fecha')->get();
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
        \App\Models\Servicio::create($this->form);
        $this->loadServicios();
        $this->showForm = false;
        $this->reset('form');
        $this->form['costo'] = 0;
        session()->flash('success', 'Servicio creado correctamente.');
    }

    public function render()
    {
        $data = [
            'breadcrumbItems' => [
                ['icon' => 'ti-home', 'url' => route('dashboard')],
                ['icon' => 'ti-tool', 'label' => 'Servicios y Reparaciones'],
            ],
            'titleMain' => 'Servicios y Reparaciones',
            'helpText' => 'Administra y consulta el listado de servicios y reparaciones registrados para los vehículos.',
            'servicios' => $this->servicios,
            'showForm' => $this->showForm,
            'form' => $this->form,
            'placasDisponibles' => $this->placasDisponibles,
        ];
        return view(
            'livewire.servicios-crud',
            $data
        )->layout('layouts.app');
    }
}
