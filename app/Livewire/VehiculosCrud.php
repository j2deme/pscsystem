<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;


use App\Models\Unidades;


class VehiculosCrud extends Component
{
    use WithPagination;
    public $perPage = 10;
    public $filtro_punto = '';
    public $puntos_disponibles = [];
    public $filtro_placas = '';
    public $unidadId;
    public $nombre_propietario, $zona, $marca, $modelo, $placas, $kms, $asignacion_punto, $estado_vehiculo, $observaciones;
    public $modo = 'crear';
    public $mostrarFormulario = false;

    protected $rules = [
        'nombre_propietario' => 'required|string',
        'zona' => 'required|string',
        'marca' => 'required|string',
        'modelo' => 'required|string',
        'placas' => 'required|string',
        'kms' => 'required|numeric',
        'asignacion_punto' => 'nullable|string',
        'estado_vehiculo' => 'required|string',
        'observaciones' => 'nullable|string',
    ];

    public function mostrarFormularioCrear()
    {
        $this->resetCampos();
        $this->modo              = 'crear';
        $this->mostrarFormulario = true;
    }

    public function guardarUnidad()
    {
        $this->validate();
        Unidades::create($this->only(array_keys($this->rules)));
        $this->mostrarFormulario = false;
        session()->flash('success', 'Unidad creada correctamente.');
    }

    public function editarUnidad($id)
    {
        $unidad         = Unidades::findOrFail($id);
        $this->unidadId = $unidad->id;
        foreach ($this->rules as $campo => $regla) {
            $this->modo              = 'editar';
            $this->mostrarFormulario = true;
        }
        $this->modo = 'editar';
    }

    public function actualizarUnidad()
    {
        $this->validate();
        $unidad = Unidades::findOrFail($this->unidadId);
        $unidad->update($this->only(array_keys($this->rules)));
        $this->modo              = 'crear';
        $this->mostrarFormulario = false;
        $this->modo              = 'crear';
        session()->flash('success', 'Unidad actualizada correctamente.');
    }

    public function eliminarUnidad($id)
    {
        Unidades::destroy($id);
        $this->mostrarFormulario = false;
        session()->flash('success', 'Unidad eliminada correctamente.');
    }

    public function resetCampos()
    {
        $this->unidadId = null;
        foreach ($this->rules as $campo => $regla) {
            $this->$campo = '';
        }
        $this->mostrarFormulario = false;
    }

    public function render()
    {
        $this->puntos_disponibles = Unidades::query()
            ->select('asignacion_punto')
            ->whereNotNull('asignacion_punto')
            ->distinct()
            ->orderBy('asignacion_punto')
            ->pluck('asignacion_punto')
            ->toArray();

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
        ]);
    }
}
