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

  // Tipos de siniestro para vehículos
  public $tiposVehiculo = [
    'ASALTO' => [
      'label' => 'Asalto',
      'descripcion' => 'Robo con violencia directa hacia personas',
      'gravedad' => 'Alta'
    ],
    'ATAQUE A UNIDAD' => [
      'label' => 'Ataque a unidad',
      'descripcion' => 'Daño intencional grave al vehículo sin robo',
      'gravedad' => 'Alta'
    ],
    'ATASCO/INMOVILIZACIÓN' => [
      'label' => 'Atasco/inmovilización',
      'descripcion' => 'Unidad atrapada en lodo, vías, nieve, etc.',
      'gravedad' => 'Baja'
    ],
    'INTENTO DE ROBO A TREN' => [
      'label' => 'Intento de robo a tren',
      'descripcion' => 'Intento de robo a tren, con riesgo latente violencia y daños',
      'gravedad' => 'Media'
    ],
    'INTENTO DE ROBO DE UNIDAD' => [
      'label' => 'Intento de robo de unidad',
      'descripcion' => 'Intento de robo de unidad, con riesgo latente de violencia y daños',
      'gravedad' => 'Media'
    ],
    'ROBO DE UNIDAD' => [
      'label' => 'Robo de unidad',
      'descripcion' => 'Pérdida total de la unidad, con posible violencia o fuerza',
      'gravedad' => 'Alta'
    ],
    'SINIESTRO EN BRECHA' => [
      'label' => 'Siniestro en brecha',
      'descripcion' => 'Accidente por derrape o salida de vía con posibles daños secundarios',
      'gravedad' => 'Media'
    ],
    'SINIESTRO EN PÉRDIDA TOTAL' => [
      'label' => 'Siniestro en pérdida total',
      'descripcion' => 'Destrucción del vehículo, daño irreparable',
      'gravedad' => 'Alta'
    ],
    'SINIESTRO NATURAL' => [
      'label' => 'Siniestro natural',
      'descripcion' => 'Daños por causas naturales como inundación, granizo, caída de árbol, etc.',
      'gravedad' => 'Baja'
    ],
    'SINIESTRO VIAL' => [
      'label' => 'Siniestro vial',
      'descripcion' => 'Accidente común sin agresión externa y lesiones leves',
      'gravedad' => 'Media'
    ],
    'SINIESTRO VIAL POR AGRESIÓN' => [
      'label' => 'Siniestro vial por agresión',
      'descripcion' => 'Accidente potencialmente grave, causado por agresión a la unidad o al personal',
      'gravedad' => 'Alta'
    ],
    'VANDALISMO' => [
      'label' => 'Vandalismo',
      'descripcion' => 'Daños superficiales al vehículo sin robo ni violencia a personas',
      'gravedad' => 'Baja'
    ],
    'OTROS' => [
      'label' => 'Otros',
      'descripcion' => 'Casos atípicos no clasificados, sin gravedad definida',
      'gravedad' => 'Variable'
    ],
  ];

  // Tipos de siniestro para personal
  public $tiposPersonal = [
    'ATAQUE PERSONAL' => [
      'label' => 'Ataque personal',
      'descripcion' => 'Agresión física directa a personal, por parte de terceros',
      'gravedad' => 'Alta'
    ],
    'ACCIDENTE DE TRABAJO' => [
      'label' => 'Accidente de trabajo',
      'descripcion' => 'Lesiones sufridas por el trabajador en el ejercicio de sus funciones',
      'gravedad' => 'Media'
    ],
  ];

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
    $this->usuariosDisponibles = User::where('estatus', 'Activo')
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
      'form.usuarios' => 'nullable|array',
    ]);

    if ($this->form['costo'] === null || $this->form['costo'] === '') {
      $this->form['costo'] = 0;
    }


    $data = $this->form;
    // Si unidad_id está vacío, ponerlo explícitamente en null para evitar error SQL
    if (empty($data['unidad_id'])) {
      $data['unidad_id'] = null;
    }
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
