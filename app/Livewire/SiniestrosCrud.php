<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Siniestro;
use App\Models\Unidades;
use App\Models\User;

class SiniestrosCrud extends Component
{
  use WithPagination;

  public $editId = null;
  public $showForm = false;
  public $form = [
    'tipo_siniestro' => '',
    'unidad_id' => '',
    'fecha' => '',
    'tipo' => '',
    'zona' => '',
    'descripcion' => '',
    'seguimiento' => '',
    'costo' => 0,
    'usuarios' => [],
  ];
  public $placasDisponibles = [];
  public $usuariosDisponibles = [];
  public $perPage = 10;
  public $filtro_unidad = '';
  public $filtro_tipo = '';
  public $filtro_fecha_inicio = '';
  public $filtro_fecha_fin = '';

  public function mount()
  {
    $this->loadPlacasDisponibles();
    $this->loadUsuariosDisponibles();
  }

  public function loadPlacasDisponibles()
  {
    $this->placasDisponibles = Unidades::where('estado_vehiculo', 'Activo')
      ->whereNotNull('placas')
      ->orderBy('placas')
      ->get(['id as unidad_id', 'placas as numero', 'marca', 'modelo'])
      ->toArray();
  }

  public function loadUsuariosDisponibles()
  {
    $this->usuariosDisponibles = User::where('estatus', 'Operativo')
      ->orderBy('name')
      ->get(['id', 'name', 'rol'])
      ->toArray();
  }

  public function showCreateForm()
  {
    $this->reset('form');
    $this->form['costo']    = 0;
    $this->form['usuarios'] = [];
    $this->editId           = null;
    $this->showForm         = true;
  }

  public function cancelarForm()
  {
    $this->showForm = false;
    $this->editId   = null;
    $this->reset('form');
    $this->form['costo']    = 0;
    $this->form['usuarios'] = [];
  }

  public function save()
  {
    $this->validate([
      'form.tipo_siniestro' => 'required|string',
      'form.unidad_id' => 'nullable|integer',
      'form.fecha' => 'required|date',
      'form.tipo' => 'required|string',
      'form.zona' => 'nullable|string',
      'form.descripcion' => 'required|string',
      'form.seguimiento' => 'nullable|string',
      'form.costo' => 'nullable|numeric',
      'form.usuarios' => 'required|array|min:1',
    ]);

    if ($this->form['costo'] === null || $this->form['costo'] === '') {
      $this->form['costo'] = 0;
    }

    $data     = $this->form;
    $usuarios = $data['usuarios'];
    unset($data['usuarios']);

    if ($this->editId) {
      $siniestro = Siniestro::findOrFail($this->editId);
      $siniestro->update($data);
      $siniestro->usuarios()->sync($usuarios);
      session()->flash('success', 'Siniestro actualizado correctamente.');
    } else {
      $siniestro = Siniestro::create($data);
      $siniestro->usuarios()->sync($usuarios);
      session()->flash('success', 'Siniestro creado correctamente.');
    }

    $this->showForm = false;
    $this->reset('form');
    $this->form['costo']    = 0;
    $this->form['usuarios'] = [];
    $this->editId           = null;
  }

  public function editarSiniestro($id)
  {
    $siniestro      = Siniestro::findOrFail($id);
    $this->form     = [
      'tipo_siniestro' => $siniestro->tipo_siniestro,
      'unidad_id' => $siniestro->unidad_id,
      'fecha' => $siniestro->fecha,
      'tipo' => $siniestro->tipo,
      'zona' => $siniestro->zona,
      'descripcion' => $siniestro->descripcion,
      'seguimiento' => $siniestro->seguimiento,
      'costo' => $siniestro->costo,
      'usuarios' => $siniestro->usuarios()->pluck('id')->toArray(),
    ];
    $this->showForm = true;
    $this->editId   = $siniestro->id;
  }

  public function eliminarSiniestro($id)
  {
    Siniestro::destroy($id);
    session()->flash('success', 'Siniestro eliminado correctamente.');
    $this->resetPage();
  }

  public function render()
  {
    $query = Siniestro::query();

    if ($this->filtro_unidad) {
      $query->where('unidad_id', $this->filtro_unidad);
    }
    if ($this->filtro_tipo) {
      $query->where('tipo', $this->filtro_tipo);
    }
    if ($this->filtro_fecha_inicio) {
      $query->whereDate('fecha', '>=', $this->filtro_fecha_inicio);
    }
    if ($this->filtro_fecha_fin) {
      $query->whereDate('fecha', '<=', $this->filtro_fecha_fin);
    }

    $siniestros = $query->orderByDesc('fecha')->paginate($this->perPage);

    $data = [
      'breadcrumbItems' => [
        ['icon' => 'ti-home', 'url' => route('dashboard')],
        ['icon' => 'ti-car-crash', 'label' => 'Siniestros'],
      ],
      'titleMain' => 'Siniestros',
      'helpText' => 'Administra y consulta el listado de siniestros registrados para vehículos y personal.',
      'siniestros' => $siniestros,
      'showForm' => $this->showForm,
      'form' => $this->form,
      'placasDisponibles' => $this->placasDisponibles,
      'usuariosDisponibles' => $this->usuariosDisponibles,
      'perPage' => $this->perPage,
      'filtro_unidad' => $this->filtro_unidad,
      'filtro_tipo' => $this->filtro_tipo,
      'filtro_fecha_inicio' => $this->filtro_fecha_inicio,
      'filtro_fecha_fin' => $this->filtro_fecha_fin,
    ];

    return view('livewire.siniestros-crud', $data)
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
