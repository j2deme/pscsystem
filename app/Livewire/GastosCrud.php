<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Gastos;
use App\Models\User;
use App\Models\Turno;
use App\Models\Misiones;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GastosCrud extends Component
{
  use WithPagination;

  // Propiedades para filtros
  public $perPage = 10;
  public $filtro_fecha_inicio = '';
  public $filtro_fecha_fin = '';
  public $filtro_punto = '';
  public $filtro_placas = '';
  public $filtro_tipo = ''; // Para filtrar por 'Gasolina' o 'Viaticos' (o vacío para todos)
  public $filtro_usuario_rol = ''; // Para activar el filtro de misiones si el rol es 'ESCOLTA'
  public $rolesDisponibles = [];

  public function mount()
  {
    // Carga los roles únicos de los usuarios activos y que 
    // tienen gastos registrados.
    // Si se ocupan todos los roles, se debe cambiar por:
    // User::distinct('rol')->pluck('rol')->filter()->values()->toArray();
    $this->rolesDisponibles = Gastos::distinct()
      ->join('users', 'gastos.user_id', '=', 'users.id')
      ->pluck('users.rol')
      ->filter() // Elimina valores null o vacíos
      ->unique()
      ->values()
      ->toArray();
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
  public function updatingFiltroPunto()
  {
    $this->resetPage();
  }
  public function updatingFiltroPlacas()
  {
    $this->resetPage();
  }
  public function updatingFiltroTipo()
  {
    $this->resetPage();
  }
  public function updatingFiltroUsuarioRol()
  {
    $this->resetPage();
  }
  public function updatingPerPage()
  {
    $this->resetPage();
  }

  /**
   * Construye la consulta base para Gastos y aplica los filtros.
   *
   * @return \Illuminate\Pagination\LengthAwarePaginator
   */
  public function render()
  {
    $query = Gastos::with(['user.solicitudAlta'])
      ->orderByDesc('Fecha')
      ->orderByDesc('Hora');

    // --- Aplicar Filtros ---

    // 1. Filtro por rango de fechas
    $query->when($this->filtro_fecha_inicio, function ($q) {
      $q->where('Fecha', '>=', $this->filtro_fecha_inicio);
    });

    $query->when($this->filtro_fecha_fin, function ($q) {
      $q->where('Fecha', '<=', $this->filtro_fecha_fin);
    });

    // 2. Filtro por punto del usuario
    $query->when($this->filtro_punto, function ($q) {
      $q->whereHas('user', function ($userQuery) {
        $userQuery->where('punto', 'LIKE', '%' . $this->filtro_punto . '%');
      })->orWhereHas('user.solicitudAlta', function ($solAltaQuery) {
        $solAltaQuery->where('punto', 'LIKE', '%' . $this->filtro_punto . '%');
      });
    });

    // 3. Filtro por placas de la unidad
    $query->when($this->filtro_placas, function ($q) {
      $userIdsConPlaca = Turno::where('Placas_unidad', 'LIKE', '%' . $this->filtro_placas . '%')
        ->pluck('User_id')
        ->unique()
        ->toArray();

      if (!empty($userIdsConPlaca)) {
        $q->whereIn('user_id', $userIdsConPlaca);
      } else {
        $q->whereNull('id');
      }
    });

    // 4. Filtro por tipo de gasto
    $query->when($this->filtro_tipo, function ($q) {
      $q->where('Tipo', $this->filtro_tipo);
    });

    // 5. Filtro por rol del usuario que hizo el gasto (USANDO LIKE para capturar variaciones)
    $query->when($this->filtro_usuario_rol, function ($q) {
      $q->whereHas('user', function ($userQuery) {
        // Filtra por coincidencia exacta de rol
        $userQuery->where('rol', $this->filtro_usuario_rol);
      });
    });

    // 6. Filtro especial: Aplicar filtro por fechas de misión SOLO si el rol seleccionado
    //    contiene la palabra 'ESCOLTA' (insensible a mayúsculas/minúsculas).
    if (!empty($this->filtro_usuario_rol) && stripos($this->filtro_usuario_rol, 'ESCOLTA') !== false) {
      //Log::info('Filtro Especial Activado', ['filtro_usuario_rol' => $this->filtro_usuario_rol]);

      // 1. Crear una nueva consulta base para Gastos
      $consultaParaIds = Gastos::query();

      // 2. Aplicar todos los filtros que ya se han aplicado a $query
      $consultaParaIds->when($this->filtro_fecha_inicio, function ($q) {
        $q->where('Fecha', '>=', $this->filtro_fecha_inicio);
      });
      $consultaParaIds->when($this->filtro_fecha_fin, function ($q) {
        $q->where('Fecha', '<=', $this->filtro_fecha_fin);
      });
      $consultaParaIds->when($this->filtro_punto, function ($q) {
        $q->whereHas('user', function ($userQuery) {
          $userQuery->where('punto', 'LIKE', '%' . $this->filtro_punto . '%');
        })
          ->orWhereHas('user.solicitudAlta', function ($solAltaQuery) {
            $solAltaQuery->where('punto', 'LIKE', '%' . $this->filtro_punto . '%');
          });
      });
      $consultaParaIds->when($this->filtro_placas, function ($q) {
        $userIdsConPlaca = Turno::where('Placas_unidad', 'LIKE', '%' . $this->filtro_placas . '%')->pluck('User_id')->unique()->toArray();
        if (!empty($userIdsConPlaca)) {
          $q->whereIn('user_id', $userIdsConPlaca);
        } else {
          $q->whereNull('id');
        }
      });
      $consultaParaIds->when($this->filtro_tipo, function ($q) {
        $q->where('Tipo', $this->filtro_tipo);
      });
      $consultaParaIds->when($this->filtro_usuario_rol, function ($q) { // Filtro general de rol
        $q->whereHas('user', function ($userQuery) {
          $userQuery->where('rol', 'LIKE', '%' . $this->filtro_usuario_rol . '%');
        });
      });

      // 3. Ahora, de esta consulta, obtener solo los user_ids de usuarios cuyo rol contiene 'ESCOLTA'
      $escoltasIdsEnConsultaActual = array_values(
        $consultaParaIds->whereHas('user', function ($userQuery) {
          // Usar LOWER para hacer la comparación insensible a mayúsculas
          $userQuery->whereRaw('LOWER(rol) LIKE ?', ['%escolta%']);
        })->pluck('user_id')->unique()->map(fn($id) => (int) $id)->toArray()
      );

      //Log::info('IDs de Escoltas encontrados en la consulta actual:', ['ids' => $escoltasIdsEnConsultaActual]);

      if (!empty($escoltasIdsEnConsultaActual)) {
        // Obtener las misiones asociadas a estos Escoltas
        // Añadimos logging aquí también
        //Log::info('Buscando misiones para los IDs de Escoltas:', ['ids' => $escoltasIdsEnConsultaActual]);

        $misiones = Misiones::whereJsonContains('agentes_id', $escoltasIdsEnConsultaActual)
          ->get(['id', 'fecha_inicio', 'fecha_fin', 'agentes_id']); // Incluimos 'id' para debugging

        //Log::info('Misiones encontradas:', ['misiones' => $misiones->toArray()]);

        if ($misiones->isNotEmpty()) {
          // Modificar la consulta principal ($query) para filtrar por fechas de misión
          // (Este bloque permanece igual)
          $query->where(function ($fechaQuery) use ($misiones, $escoltasIdsEnConsultaActual) {
            $condicionesMisiones = [];
            foreach ($misiones as $mision) {
              foreach ($escoltasIdsEnConsultaActual as $escoltaId) {
                if (in_array($escoltaId, $mision->agentes_id)) {
                  if (!isset($condicionesMisiones[$escoltaId])) {
                    $condicionesMisiones[$escoltaId] = [];
                  }
                  $condicionesMisiones[$escoltaId][] = [$mision->fecha_inicio, $mision->fecha_fin];
                }
              }
            }

            foreach ($condicionesMisiones as $userId => $rangos) {
              $fechaQuery->orWhere(function ($orQuery) use ($userId, $rangos) {
                $orQuery->where('gastos.user_id', $userId);
                $orQuery->where(function ($nestedOrQuery) use ($rangos) {
                  foreach ($rangos as $rango) {
                    $nestedOrQuery->orWhereBetween('gastos.Fecha', $rango);
                  }
                });
              });
            }
          });
          //Log::info('Filtro de fechas de misiones aplicado.');
        } else {
          // Si se filtró por un rol tipo 'ESCOLTA' pero no hay misiones registradas,
          // no mostrar sus gastos.
          //Log::info('No se encontraron misiones para los Escoltas. Aplicando whereNull.');
          $query->whereNull('id');
        }
      } else {
        // Si se filtró por un rol tipo 'ESCOLTA' pero no hay gastos de Escoltas en los resultados,
        // no mostrar resultados.
        //Log::info('No se encontraron IDs de Escoltas en la consulta. Aplicando whereNull.');
        $query->whereNull('id');
      }
    }

    // --- Paginación ---
    $gastos = $query->paginate($this->perPage);

    // --- Preparar datos para la vista ---
    $data = [
      'breadcrumbItems' => [
        ['icon' => 'ti-home', 'url' => route('dashboard')],
        ['icon' => 'ti-receipt-2', 'label' => 'Gastos'],
      ],
      'titleMain' => 'Gastos',
      'helpText' => 'Visualización de los registros de gastos (Gasolina, Viáticos) hechos por los elementos en campo.',
      'gastos' => $gastos,
      'filtro_fecha_inicio' => $this->filtro_fecha_inicio,
      'filtro_fecha_fin' => $this->filtro_fecha_fin,
      'filtro_punto' => $this->filtro_punto,
      'filtro_placas' => $this->filtro_placas,
      'filtro_tipo' => $this->filtro_tipo,
      'filtro_usuario_rol' => $this->filtro_usuario_rol,
      'perPage' => $this->perPage,
      'rolesDisponibles' => $this->rolesDisponibles,
    ];

    return view('livewire.gastos-crud', $data)
      ->layout('layouts.app');
  }
}
