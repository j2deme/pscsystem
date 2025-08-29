<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Compra;
use App\Models\Unidades;

class ComprasCrud extends Component
{
  use WithPagination;

  // Propiedades para filtros
  public $perPage = 10;
  public $filtro_fecha_inicio = '';
  public $filtro_fecha_fin = '';
  public $filtro_unidad = ''; // Para filtrar por unidad_id
  public $filtro_tipo = '';
  public $filtro_proveedor = '';
  public $filtro_garantia = ''; // '' = todos, '1' = con garantía, '0' = sin garantía

  // Propiedades para el formulario
  public $editId = null;
  public $showForm = false;
  public $form = [
    'unidad_id' => '',
    'fecha_hora' => '',
    'tipo' => '',
    'descripcion' => '',
    'proveedor' => '',
    'costo' => '',
    'kilometraje' => '',
    'garantia' => false,
    'notas' => '',
  ];

  // Propiedades para datos auxiliares
  public $unidadesDisponibles = [];
  public $tiposDisponibles = [];

  protected $rules = [
    'form.unidad_id' => 'nullable|exists:unidades,id',
    'form.fecha_hora' => 'required|date',
    'form.tipo' => 'required|string|max:255',
    'form.descripcion' => 'required|string',
    'form.proveedor' => 'nullable|string|max:255',
    'form.costo' => 'nullable|numeric|min:0',
    'form.kilometraje' => 'nullable|integer|min:0',
    'form.garantia' => 'boolean',
    'form.notas' => 'nullable|string',
  ];

  protected $messages = [
    'form.unidad_id.exists' => 'La unidad seleccionada no es válida.',
    'form.fecha_hora.required' => 'La fecha y hora son obligatorias.',
    'form.tipo.required' => 'El tipo es obligatorio.',
    'form.descripcion.required' => 'La descripción es obligatoria.',
    'form.costo.numeric' => 'El costo debe ser un número válido.',
    'form.kilometraje.integer' => 'El kilometraje debe ser un número entero.',
  ];

  public function mount()
  {
    $this->loadUnidadesDisponibles();
    $this->loadTiposDisponibles();

    // Verificar si se solicita edición desde el detalle
    $request = request();
    if ($request->has('editar')) {
      $id = $request->input('editar');
      $this->editarCompra($id);
    }

    // Establecer fecha y hora por defecto para nuevos registros
    $this->form['fecha_hora'] = now()->format('Y-m-d\TH:i');
  }

  public function loadUnidadesDisponibles()
  {
    // Cargar unidades activas ordenadas por placas
    $this->unidadesDisponibles = Unidades::where('estado_vehiculo', 'Activo')
      ->orderBy('placas')
      ->get(['id', 'placas', 'marca', 'modelo'])
      ->toArray();
  }

  public function loadTiposDisponibles()
  {
    // Definir tipos por defecto basados en el análisis del archivo
    $this->tiposDisponibles = [
      'Accesorios',
      'Afinación',
      'Cambio de Llantas',
      'Compra Directa',
      'Hojalatería y Pintura',
      'Insumo',
      'Mantenimiento',
      'Refacción',
      'Reparación',
      'Servicio Mayor',
      'Servicio Menor',
      'Siniestro',
      'Verificación'
    ];

    // Ordenar alfabéticamente para mejor UX
    sort($this->tiposDisponibles);
  }

  public function showCreateForm()
  {
    $this->resetForm();
    $this->editId   = null;
    $this->showForm = true;
    // Establecer fecha y hora actuales por defecto
    $this->form['fecha_hora'] = now()->format('Y-m-d\TH:i');
  }

  public function resetForm()
  {
    $this->reset('form');
    $this->resetErrorBag();
  }

  public function cancelarForm()
  {
    $this->showForm = false;
    $this->editId   = null;
    $this->resetForm();
  }

  public function save()
  {
    $this->validate();

    // Convertir costo a null si es vacío
    if ($this->form['costo'] === '' || $this->form['costo'] === null) {
      $this->form['costo'] = null;
    }

    // Convertir kilometraje a null si es vacío
    if ($this->form['kilometraje'] === '' || $this->form['kilometraje'] === null) {
      $this->form['kilometraje'] = null;
    }

    if ($this->editId) {
      // Actualizar registro existente
      $compra = Compra::findOrFail($this->editId);
      $compra->update($this->form);
      session()->flash('message', 'Compra actualizada correctamente.');
    } else {
      // Crear nuevo registro
      Compra::create($this->form);
      session()->flash('message', 'Compra registrada correctamente.');
    }

    $this->showForm = false;
    $this->editId   = null;
    $this->resetForm();
  }

  public function editarCompra($id)
  {
    $compra         = Compra::findOrFail($id);
    $this->form     = [
      'unidad_id' => $compra->unidad_id,
      'fecha_hora' => $compra->fecha_hora->format('Y-m-d\TH:i'),
      'tipo' => $compra->tipo,
      'descripcion' => $compra->descripcion,
      'proveedor' => $compra->proveedor,
      'costo' => $compra->costo,
      'kilometraje' => $compra->kilometraje,
      'garantia' => $compra->garantia,
      'notas' => $compra->notas,
    ];
    $this->editId   = $compra->id;
    $this->showForm = true;
  }

  public function eliminarCompra($id)
  {
    Compra::destroy($id);
    session()->flash('message', 'Compra eliminada correctamente.');
    $this->resetPage();
  }

  // Métodos para reiniciar la página al cambiar un filtro
  public function updatingFiltroFechaInicio()
  {
    $this->resetPage();
  }
  public function updatingFiltroFechaFin()
  {
    $this->resetPage();
  }
  public function updatingFiltroUnidad()
  {
    $this->resetPage();
  }
  public function updatingFiltroTipo()
  {
    $this->resetPage();
  }
  public function updatingFiltroProveedor()
  {
    $this->resetPage();
  }
  public function updatingFiltroGarantia()
  {
    $this->resetPage();
  }
  public function updatingPerPage()
  {
    $this->resetPage();
  }

  public function render()
  {
    $query = Compra::with('unidad')
      ->orderByDesc('fecha_hora');

    // Aplicar filtros
    $query->when($this->filtro_fecha_inicio, function ($q) {
      $q->whereDate('fecha_hora', '>=', $this->filtro_fecha_inicio);
    });

    $query->when($this->filtro_fecha_fin, function ($q) {
      $q->whereDate('fecha_hora', '<=', $this->filtro_fecha_fin);
    });

    $query->when($this->filtro_unidad, function ($q) {
      $q->where('unidad_id', $this->filtro_unidad);
    });

    $query->when($this->filtro_tipo, function ($q) {
      $q->where('tipo', $this->filtro_tipo);
    });

    $query->when($this->filtro_proveedor, function ($q) {
      $q->where('proveedor', 'like', '%' . $this->filtro_proveedor . '%');
    });

    $query->when($this->filtro_garantia !== '', function ($q) {
      $q->where('garantia', $this->filtro_garantia);
    });

    $compras = $query->paginate($this->perPage);

    return view('livewire.compras-crud', [
      'breadcrumbItems' => [
        ['icon' => 'ti-home', 'url' => route('dashboard')],
        ['icon' => 'ti-shopping-cart', 'label' => 'Compras'],
      ],
      'titleMain' => 'Gestión de Compras',
      'helpText' => 'Administre y consulte los registros de compras y gastos relacionados con las unidades.',
      'compras' => $compras,
      'unidadesDisponibles' => $this->unidadesDisponibles,
      'tiposDisponibles' => $this->tiposDisponibles,
      'filtro_fecha_inicio' => $this->filtro_fecha_inicio,
      'filtro_fecha_fin' => $this->filtro_fecha_fin,
      'filtro_unidad' => $this->filtro_unidad,
      'filtro_tipo' => $this->filtro_tipo,
      'filtro_proveedor' => $this->filtro_proveedor,
      'filtro_garantia' => $this->filtro_garantia,
      'perPage' => $this->perPage,
      'showForm' => $this->showForm,
      'form' => $this->form,
      'editId' => $this->editId,
    ])->layout('layouts.app');
  }
}