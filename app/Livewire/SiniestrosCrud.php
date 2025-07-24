<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Siniestro;
use App\Models\Unidades;
use App\Models\User;
use App\Traits\TiposSiniestro;

class SiniestrosCrud extends Component
{
  use WithPagination;
  use TiposSiniestro;

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
  public $returnToDetalle = false;

  public function mount()
  {
    $this->loadPlacasDisponibles();
    $this->loadUsuariosDisponibles();
    $request = request();
    if ($request->has('editar')) {
      $id = $request->input('editar');
      $this->editarSiniestro($id);
    }
    // Detectar si se debe regresar al detalle después de editar/cancelar
    if ($request->has('return') && $request->input('return') === 'detalle') {
      $this->returnToDetalle = true;
    }
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

  private function procesaBadges($siniestros, $tiposVehiculo, $tiposPersonal)
  {
    $siniestros->getCollection()->transform(function ($siniestro) use ($tiposVehiculo, $tiposPersonal) {
      $tipo                         = strtolower($siniestro->tipo_siniestro);
      $tipos                        = $tipo === 'vehiculo' ? $tiposVehiculo : ($tipo === 'personal' ? $tiposPersonal : []);
      $infoTipo                     = $siniestro->tipo && isset($tipos[$siniestro->tipo]) ? $tipos[$siniestro->tipo] : null;
      $gravedad                     = $infoTipo['gravedad'] ?? null;
      $siniestro->badgeGravedadInfo = $this->getGravedadBadgeInfo($gravedad);
      $siniestro->gravedad          = $gravedad;
      return $siniestro;
    });
    return $siniestros;
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
    // Si se inició edición desde el detalle, redirigir
    if ($this->returnToDetalle && $this->editId) {
      return redirect()->route('siniestros.detalle', ['id' => $this->editId]);
    }
    if ($this->returnToDetalle && request()->has('editar')) {
      return redirect()->route('siniestros.detalle', ['id' => request()->input('editar')]);
    }
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

    $id             = $this->editId ?? null;
    $this->showForm = false;
    $this->reset('form');
    $this->form['costo']    = 0;
    $this->form['usuarios'] = [];
    $this->editId           = null;

    // Si se inició edición desde el detalle, redirigir
    if ($this->returnToDetalle && $id) {
      return redirect()->route('siniestros.detalle', ['id' => $id]);
    }
  }

  public function editarSiniestro($id)
  {
    $siniestro      = Siniestro::findOrFail($id);
    $this->form     = [
      'tipo_siniestro' => $siniestro->tipo_siniestro,
      'unidad_id' => $siniestro->unidad_id,
      'fecha' => $siniestro->fecha ? $siniestro->fecha->format('Y-m-d') : '',
      'tipo' => $siniestro->tipo,
      'zona' => $siniestro->zona,
      'descripcion' => $siniestro->descripcion,
      'seguimiento' => $siniestro->seguimiento,
      'costo' => $siniestro->costo,
      'usuarios' => $siniestro->usuarios()->pluck('users.id')->toArray(),
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
      $query->where('tipo_siniestro', $this->filtro_tipo);
    }
    if ($this->filtro_fecha_inicio) {
      $query->whereDate('fecha', '>=', $this->filtro_fecha_inicio);
    }
    if ($this->filtro_fecha_fin) {
      $query->whereDate('fecha', '<=', $this->filtro_fecha_fin);
    }

    $siniestros = $query->orderByDesc('fecha')->paginate($this->perPage);

    $tiposVehiculo = $this->getTiposVehiculo();
    $tiposPersonal = $this->getTiposPersonal();
    $siniestros    = $this->procesaBadges($siniestros, $tiposVehiculo, $tiposPersonal);

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
      'tiposVehiculo' => $tiposVehiculo,
      'tiposPersonal' => $tiposPersonal,
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
