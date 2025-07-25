<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Unidades;
use App\Models\Servicio;
use App\Traits\EstilosServicio;

class ServiciosCrud extends Component
{
    use WithPagination;
    use EstilosServicio;

    public $editId = null;
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
    public $filtro_fecha_inicio = '';
    public $filtro_fecha_fin = '';
    public $returnToDetalle = false;
    public $responsablesDisponibles = [];

    public function mount()
    {
        $this->loadPlacasDisponibles();
        $this->loadResponsablesDisponibles();
        $request = request();
        // Si la URL tiene ?editar=ID, abrir el formulario de edición automáticamente
        if ($request->has('editar')) {
            $id = $request->input('editar');
            $this->editarServicio($id);
        }
        // Detectar si se debe regresar al detalle después de editar/cancelar
        if ($request->has('return') && $request->input('return') === 'detalle') {
            $this->returnToDetalle = true;
        }
    }

    public function loadPlacasDisponibles()
    {
        // Tomar placas del listado de vehículos activos
        $this->placasDisponibles = Unidades::where("estado_vehiculo", 'Activo')
            ->whereNotNull('placas')
            ->orderBy('placas')
            ->get(['id as unidad_id', 'placas as numero', 'marca', 'modelo'])->toArray();
    }

    public function loadResponsablesDisponibles()
    {
        // Obtener responsables/talleres únicos de los servicios existentes
        $this->responsablesDisponibles = Servicio::query()
            ->whereNotNull('responsable')
            ->where('responsable', '!=', '')
            ->distinct()
            ->orderBy('responsable')
            ->pluck('responsable')
            ->toArray();
    }

    public function showCreateForm()
    {
        $this->reset('form');
        $this->form['costo']   = 0;
        $this->editId          = null;
        $this->showForm        = true;
        $this->returnToDetalle = false;
    }

    public function cancelarForm()
    {
        $this->showForm = false;
        $this->editId   = null;
        $this->reset('form');
        $this->form['costo'] = 0;
        // Si se inició edición desde el detalle, redirigir
        if ($this->returnToDetalle && $this->editId) {
            return redirect()->route('servicio.detalle', ['id' => $this->editId]);
        }
        if ($this->returnToDetalle && request()->has('editar')) {
            return redirect()->route('servicio.detalle', ['id' => request()->input('editar')]);
        }
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

        if ($this->editId) {
            // Actualizar registro existente
            $servicio = Servicio::findOrFail($this->editId);
            $servicio->update($this->form);
            session()->flash('success', 'Servicio actualizado correctamente.');
        } else {
            // Crear nuevo registro
            Servicio::create($this->form);
            session()->flash('success', 'Servicio creado correctamente.');
        }

        $id             = $this->editId ?? null;
        $this->showForm = false;
        $this->reset('form');
        $this->form['costo'] = 0;
        $this->editId        = null;

        // Si se inició edición desde el detalle, redirigir
        if ($this->returnToDetalle && $id) {
            return redirect()->route('servicio.detalle', ['id' => $id]);
        }
    }

    public function editarServicio($id)
    {
        $servicio       = Servicio::findOrFail($id);
        $this->form     = [
            'unidad_id' => $servicio->unidad_id,
            'fecha' => optional($servicio->fecha)->format('Y-m-d'),
            'descripcion' => $servicio->descripcion,
            'costo' => $servicio->costo,
            'responsable' => $servicio->responsable,
            'tipo' => $servicio->tipo,
            'observaciones' => $servicio->observaciones,
        ];
        $this->showForm = true;
        $this->editId   = $servicio->id;
        // Detectar si se debe regresar al detalle
        $request = request();
        if ($request->has('return') && $request->input('return') === 'detalle') {
            $this->returnToDetalle = true;
        } else {
            $this->returnToDetalle = false;
        }
    }

    public function eliminarServicio($id)
    {
        Servicio::destroy($id);
        session()->flash('success', 'Servicio eliminado correctamente.');
        $this->resetPage();
    }

    public function render()
    {
        $query = Servicio::query()
            ->when($this->filtro_unidad, function ($query) {
                $query->where('unidad_id', $this->filtro_unidad);
            })
            ->when($this->filtro_tipo, function ($query) {
                $query->where('tipo', $this->filtro_tipo);
            })
            ->when($this->filtro_fecha_inicio, function ($query) {
                $query->whereDate('fecha', '>=', $this->filtro_fecha_inicio);
            })
            ->when($this->filtro_fecha_fin, function ($query) {
                $query->whereDate('fecha', '<=', $this->filtro_fecha_fin);
            })
            ->orderByDesc('fecha');


        $servicios = $query->paginate($this->perPage);

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
            'responsablesDisponibles' => $this->responsablesDisponibles,
            'perPage' => $this->perPage,
            'filtro_unidad' => $this->filtro_unidad,
            'filtro_tipo' => $this->filtro_tipo,
            'filtro_fecha_inicio' => $this->filtro_fecha_inicio,
            'filtro_fecha_fin' => $this->filtro_fecha_fin,
            'estilos' => $this->getEstilosServicio(),
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