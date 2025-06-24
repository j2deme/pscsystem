<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Subpunto;
use App\Models\Punto;

class Seleccioncoberturas extends Component
{
    public $search = '';
    public $usuarios = [];
    public $seleccionados = [];
    public $showDropdown = false;
    public $puntos = [];

        public function mount()
    {
        $this->puntos = Punto::with('subpuntos')->get();
    }

    public function asignarSubpunto($usuarioId, $subpuntoId)
    {
        $subpunto = Subpunto::with('punto')->find($subpuntoId);

        foreach ($this->seleccionados as &$usuario) {
            if ($usuario['id'] === $usuarioId) {
                $usuario['punto'] = $subpunto->punto->nombre . ' - ' . $subpunto->nombre;
                $usuario['subpunto_id'] = $subpunto->id;
                break;
            }
        }
    }

    public function updatedSearch($value)
    {
        if (strlen($value) >= 2) {
            $this->usuarios = User::where('estatus', 'Activo')
                ->where('name', 'like', '%'.$value.'%')
                ->limit(5)
                ->get();
            $this->showDropdown = true;
        } else {
            $this->resetDropdown();
        }
    }

    public function seleccionarUsuario($id)
    {
        $user = User::find($id);

        if ($user) {
            $puntoNombre = optional(optional($user->subpunto)->punto)->nombre ?? 'No definido';

            if (!collect($this->seleccionados)->pluck('id')->contains($user->id)) {
                $this->seleccionados[] = [
                    'id' => $user->id,
                    'nombre' => $user->name,
                    'punto' => $puntoNombre
                ];
            }
        }

        $this->search = '';
        $this->usuarios = [];
        $this->showDropdown = false;
    }

    public function eliminarSeleccionado($id)
    {
        $this->seleccionados = array_values(array_filter($this->seleccionados, function ($usuario) use ($id) {
            return $usuario['id'] !== $id;
        }));
    }


    private function resetDropdown()
    {
        $this->usuarios = [];
        $this->showDropdown = false;
    }

    public function render()
    {
        return view('livewire.seleccioncoberturas');
    }
}
