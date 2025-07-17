<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;


use App\Models\Unidades;


class VehiculosCrud extends Component
{
    use WithPagination;
    public $perPage = 10;
    public $sin_kilometraje = false;
    public $filtro_punto = '';
    public $puntos_disponibles = [];
    public $filtro_placas = '';
    public $unidadId;
    public $nombre_propietario, $zona, $marca, $modelo, $placas, $kms, $asignacion_punto, $estado_vehiculo, $observaciones;
    public $is_activo = false;
    public $modo = 'crear';
    public $mostrarFormulario = false;
    public $marcas = [];
    public $propietarios = [];
    public $zonas = [];
    public $returnTo = null;

    protected $rules = [
        'nombre_propietario' => 'required|string',
        'zona' => 'required|string',
        'marca' => 'required|string',
        'modelo' => 'required|string',
        'placas' => 'required|string',
        'kms' => 'nullable|numeric',
        'asignacion_punto' => 'nullable|string',
        'is_activo' => 'boolean',
        'observaciones' => 'nullable|string',
    ];

    public function mount()
    {
        $this->recargarListas();
        $this->returnTo = request()->query('return');
        $editarId       = request()->query('editar');
        if ($editarId) {
            $this->editarUnidad($editarId);
        }
    }

    public function recargarListas()
    {
        $this->puntos_disponibles = Unidades::query()
            ->select('asignacion_punto')
            ->whereNotNull('asignacion_punto')
            ->distinct()
            ->orderBy('asignacion_punto')
            ->pluck('asignacion_punto')
            ->filter()
            ->values()
            ->toArray();

        $this->marcas = Unidades::query()
            ->select('marca')
            ->distinct()
            ->orderBy('marca')
            ->pluck('marca')
            ->filter()
            ->values()
            ->toArray();

        $this->propietarios = Unidades::query()
            ->select('nombre_propietario')
            ->whereNotNull('nombre_propietario')
            ->distinct()
            ->orderBy('nombre_propietario')
            ->pluck('nombre_propietario')
            ->filter()
            ->values()
            ->toArray();

        $this->zonas = Unidades::query()
            ->select('zona')
            ->whereNotNull('zona')
            ->distinct()
            ->orderBy('zona')
            ->pluck('zona')
            ->filter()
            ->values()
            ->toArray();
    }

    public function mostrarFormularioCrear()
    {
        $this->resetCampos();
        $this->modo              = 'crear';
        $this->mostrarFormulario = true;
    }

    public function guardarUnidad()
    {
        $this->validate();
        $kms = $this->sin_kilometraje ? null : $this->kms;
        // Sanitizar placas: solo alfanuméricos
        $placaSanitizada = preg_replace('/[^A-Za-z0-9]/', '', $this->placas);
        $unidad          = Unidades::create([
            'nombre_propietario' => $this->nombre_propietario,
            'zona' => $this->zona,
            'marca' => $this->marca,
            'modelo' => $this->modelo,
            'placas' => $placaSanitizada,
            'kms' => $kms,
            'asignacion_punto' => $this->asignacion_punto,
            'estado_vehiculo' => $this->is_activo ? 'Activo' : 'Inactivo',
            'is_activo' => $this->is_activo,
            'observaciones' => $this->observaciones,
        ]);

        // Crear registro en la tabla placas y asociar al vehículo
        if (!empty($placaSanitizada)) {
            \App\Models\Placa::create([
                'unidad_id' => $unidad->id,
                'numero' => $placaSanitizada,
                'fecha_asignacion' => now(),
                'estado' => 'Activa', // Estado explícito en alta
            ]);
        }

        $this->recargarListas();
        $this->mostrarFormulario = false;
        session()->flash('success', 'Unidad creada correctamente.');
        if ($this->returnTo === 'detalle' && $unidad->id) {
            return redirect()->route('vehiculos.detalle', ['id' => $unidad->id]);
        }
    }

    public function editarUnidad($id)
    {
        $unidad                   = Unidades::findOrFail($id);
        $this->unidadId           = $unidad->id;
        $this->nombre_propietario = $unidad->nombre_propietario;
        $this->zona               = $unidad->zona;
        $this->marca              = $unidad->marca;
        $this->modelo             = $unidad->modelo;
        $this->placas             = $unidad->placas;
        $this->kms                = $unidad->kms;
        $this->asignacion_punto   = $unidad->asignacion_punto;
        $this->estado_vehiculo    = $unidad->estado_vehiculo;
        $this->is_activo          = (bool) ($unidad->is_activo ?? ($unidad->estado_vehiculo === 'Activo'));
        $this->observaciones      = $unidad->observaciones;
        $this->modo               = 'editar';
        $this->mostrarFormulario  = true;
        $this->returnTo           = request()->query('return', $this->returnTo);
    }

    public function actualizarUnidad()
    {
        $this->validate();
        $unidad        = Unidades::findOrFail($this->unidadId);
        $placaAnterior = $unidad->placas;
        // Sanitizar placa nueva
        $placaNuevaSanitizada = preg_replace('/[^A-Za-z0-9]/', '', $this->placas);

        $unidad->update([
            'nombre_propietario' => $this->nombre_propietario,
            'zona' => $this->zona,
            'marca' => $this->marca,
            'modelo' => $this->modelo,
            'placas' => $placaNuevaSanitizada,
            'kms' => $this->kms,
            'asignacion_punto' => $this->asignacion_punto,
            'estado_vehiculo' => $this->is_activo ? 'Activo' : 'Inactivo',
            'is_activo' => $this->is_activo,
            'observaciones' => $this->observaciones,
        ]);

        // Si la placa fue cambiada, actualizar historial
        if ($placaAnterior !== $placaNuevaSanitizada && !empty($placaNuevaSanitizada)) {
            // Dar de baja la placa anterior y actualizar estado
            $placaActiva = \App\Models\Placa::where('unidad_id', $unidad->id)
                ->where('numero', $placaAnterior)
                ->whereNull('fecha_baja')
                ->first();
            if ($placaActiva) {
                $placaActiva->fecha_baja = now();
                $placaActiva->estado     = 'Inactiva'; // Cambia el estado a Inactiva
                $placaActiva->save();
            }
            // Crear nueva placa con estado Activa
            \App\Models\Placa::create([
                'unidad_id' => $unidad->id,
                'numero' => $placaNuevaSanitizada,
                'fecha_asignacion' => now(),
                'estado' => 'Activa',
            ]);
        }

        $this->recargarListas();
        $this->modo              = 'crear';
        $this->mostrarFormulario = false;
        session()->flash('success', 'Unidad actualizada correctamente.');
        if ($this->returnTo === 'detalle' && $unidad->id) {
            return redirect()->route('vehiculos.detalle', ['id' => $unidad->id]);
        }
    }

    public function eliminarUnidad($id)
    {
        Unidades::destroy($id);
        $this->recargarListas();
        $this->mostrarFormulario = false;
        session()->flash('success', 'Unidad eliminada correctamente.');
    }

    public function resetCampos()
    {
        // Si estamos editando y returnTo es 'detalle', redirigir a detalle
        if ($this->returnTo === 'detalle' && $this->unidadId) {
            return redirect()->route('vehiculos.detalle', ['id' => $this->unidadId]);
        }
        $this->unidadId = null;
        foreach ($this->rules as $campo => $regla) {
            $this->$campo = '';
        }
        $this->is_activo         = false;
        $this->mostrarFormulario = false;
    }

    public function render()
    {
        $query = Unidades::query();
        if ($this->filtro_punto) {
            $query->whereRaw('LOWER(TRIM(asignacion_punto)) = ?', [strtolower(trim($this->filtro_punto))]);
        }
        if ($this->filtro_placas) {
            $query->where('placas', 'like', "%{$this->filtro_placas}%");
        }
        return view('livewire.vehiculos-crud', [
            'unidades' => $query->orderBy('id', 'asc')->paginate($this->perPage),
            'puntos_disponibles' => $this->puntos_disponibles,
            'marcas' => $this->marcas,
            'propietarios' => $this->propietarios,
            'zonas' => $this->zonas,
        ]);
    }

    public function updatingFiltroPlacas()
    {
        $this->resetPage();
    }
    public function updatingFiltroPunto()
    {
        $this->resetPage();
    }
    public function updatingPerPage()
    {
        $this->resetPage();
    }
}
