<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Unidades;
use App\Models\Servicio;
use App\Models\Siniestro;
use App\Models\Gastos;
use App\Models\Turno;
use App\Models\Location;

class DatosOrganicosSeeder extends Seeder
{
  public function run()
  {
    $usuarios  = User::all();
    $vehiculos = Unidades::all();

    foreach ($usuarios as $usuario) {
      // Crear turnos para cada usuario
      $kmInicio = rand(10000, 50000);
      $kmFinal  = $kmInicio + rand(25, 5000);
      $turno    = Turno::create([
        'User_id' => $usuario->id,
        'Nombre_elemento' => $usuario->name,
        'Tipo' => 'Entrada',
        'Hora_inicio' => now()->subHours(rand(1, 8)),
        'Hora_final' => now(),
        'Km_inicio' => $kmInicio,
        'Km_final' => $kmFinal,
        'Punto' => ['Base Central', 'Base Norte', 'Base Sur', 'Base Este', 'Base Oeste'][rand(0, 4)],
        'Placas_unidad' => $vehiculos->random()->placas ?? 'XXX-000',
        'Rayas_gasolina_inicio' => rand(1, 8),
        'Rayas_gasolina_final' => rand(1, 8),
        'Evidencia_inicio' => 'inicio.jpg',
        'Evidencia_final' => 'final.jpg',
      ]);

      // Crear siniestros de tipo "personal" vinculados al usuario
      // Usar el catálogo del trait TiposSiniestro para personal
      $tiposTrait        = new class {
        use \App\Traits\TiposSiniestro;
      };
      $tiposPersonal     = $tiposTrait->getTiposPersonal();
      $keysPersonal      = array_keys($tiposPersonal);
      $tipoKeyPersonal   = $keysPersonal[rand(0, count($keysPersonal) - 1)];
      $tipoInfoPersonal  = $tiposPersonal[$tipoKeyPersonal];
      $siniestroPersonal = Siniestro::create([
        'tipo_siniestro' => 'personal',
        'fecha' => now()->subDays(rand(1, 30)),
        'descripcion' => $tipoInfoPersonal['label'],
        'tipo' => $tipoKeyPersonal,
        'zona' => ['Norte', 'Sur', 'Este', 'Oeste', 'Centro'][rand(0, 4)],
      ]);
      // Asociar usuario mediante la relación pivote
      $siniestroPersonal->usuarios()->attach($usuario->id);


      // Crear siniestro vinculado al usuario y vehículo
      $vehiculo = $vehiculos->random(1)->first();
      // Usar el catálogo del trait TiposSiniestro
      $tiposTrait    = new class {
        use \App\Traits\TiposSiniestro;
      };
      $tiposVehiculo = $tiposTrait->getTiposVehiculo();
      $keys          = array_keys($tiposVehiculo);
      // Generar de 1 a 2 siniestros por año desde el año de la unidad hasta el año actual
      $añoUnidad = intval($vehiculo->modelo ?? date('Y'));
      $añoActual = intval(date('Y'));
      for ($anio = $añoUnidad; $anio <= $añoActual; $anio++) {
        $numSiniestros = rand(1, 2);
        for ($j = 0; $j < $numSiniestros; $j++) {
          $tipoKey           = $keys[rand(0, count($keys) - 1)];
          $tipoInfo          = $tiposVehiculo[$tipoKey];
          $conCosto          = rand(0, 1) === 1;
          $siniestroVehiculo = Siniestro::create([
            'tipo_siniestro' => 'vehiculo',
            'fecha' => now()->setYear($anio)->subDays(rand(1, 365)),
            'descripcion' => $tipoInfo['label'],
            'unidad_id' => $vehiculo->id,
            'tipo' => $tipoKey,
            'zona' => ['Norte', 'Sur', 'Este', 'Oeste', 'Centro'][rand(0, 4)],
            'costo' => $conCosto ? rand(1000, 20000) : null,
          ]);
          $siniestroVehiculo->usuarios()->attach($usuario->id);
          // Un porcentaje menor de los siniestros de vehiculos, puede tener asociado más de un usuario
          if (rand(0, 1) === 1) {
            $otrosUsuarios = $usuarios->where('id', '!=', $usuario->id)->random(rand(1, 3));
            foreach ($otrosUsuarios as $otroUsuario) {
              $siniestroVehiculo->usuarios()->attach($otroUsuario->id);
            }
          }
        }
      }
      // Siniestro actual
      $tipoKey   = $keys[rand(0, count($keys) - 1)];
      $tipoInfo  = $tiposVehiculo[$tipoKey];
      $conCosto  = rand(0, 1) === 1;
      $siniestro = Siniestro::create([
        'tipo_siniestro' => 'vehiculo',
        'fecha' => now()->subDays(rand(1, 30)),
        'descripcion' => $tipoInfo['label'],
        'unidad_id' => $vehiculo->id,
        'tipo' => $tipoKey,
        'zona' => ['Norte', 'Sur', 'Este', 'Oeste', 'Centro'][rand(0, 4)],
        'costo' => $conCosto ? rand(1000, 20000) : null,
      ]);
      $siniestro->usuarios()->attach($usuario->id);

      // Crear gasto asociado al usuario
      // Generar de 5 a 7 gastos por usuario en los últimos 16 meses, con lógica histórica de kilometraje
      $numGastos = rand(5, 7);
      $fechaBase = now()->copy()->subMonths(16);
      $kmBase    = $kmFinal;
      for ($i = 0; $i < $numGastos; $i++) {
        $tipoGasto  = ['Gasolina', 'Viaticos'][rand(0, 1)];
        $gasAntes   = rand(1, 7);
        $gasDespues = $gasAntes + rand(1, 8 - $gasAntes);
        // Fecha progresiva
        $fechaGasto = $fechaBase->copy()->addDays(rand(10, 40) * $i);
        // Km progresivo
        $kmBase += rand(20, 100);
        Gastos::create([
          'user_id' => $usuario->id,
          'user_name' => $usuario->name,
          'Monto' => rand(500, 5000),
          'Fecha' => $fechaGasto,
          'Hora' => now()->format('H:i'),
          'Evidencia' => 'ticket.jpg',
          'Tipo' => $tipoGasto,
          'Km' => $kmBase,
          'Gasolina_antes_carga' => $gasAntes,
          'Gasolina_despues_carga' => $gasDespues,
        ]);
      }

      // Crear servicio vinculado al vehículo
      // Crear de 3 a 5 servicios en los últimos 3 años
      $numServicios = rand(3, 5);
      for ($i = 0; $i < $numServicios; $i++) {
        Servicio::create([
          'unidad_id' => $vehiculo->id,
          'fecha' => now()->subYears(rand(0, 2))->subDays(rand(1, 365)),
          'tipo' => ['Preventivo', 'Correctivo', 'Incidencia', 'Otros'][rand(0, 3)],
          'costo' => rand(500, 10000),
          'responsable' => ['Taller Central', 'Taller Norte', 'Taller Sur'][rand(0, 2)],
          'descripcion' => 'Servicio realizado: ' . ['Cambio de aceite', 'Reparación de frenos', 'Alineación', 'Revisión general'][rand(0, 3)],
        ]);
      }

      // Crear location asociada al usuario
      // Crear de 7 a 10 ubicaciones para el usuario
      $numLocations = rand(7, 10);
      for ($i = 0; $i < $numLocations; $i++) {
        // Las últimas 3 ubicaciones deben ser de hace menos de 3 horas
        if ($i >= $numLocations - 3) {
          $fechaLocation = now()->subMinutes(rand(1, 180));
        } else {
          $fechaLocation = now()->subDays(rand(1, 30))->subHours(rand(1, 23));
        }
        Location::create([
          'user_id' => $usuario->id,
          // Dispersión ampliada: ±0.25 grados (~27 km)
          'latitude' => 25.686614 + (rand(-2500, 2500) / 10000),
          'longitude' => -100.316113 + (rand(-2500, 2500) / 10000),
          'created_at' => $fechaLocation,
        ]);
      }
    }
  }
}
