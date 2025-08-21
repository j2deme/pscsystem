<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Misiones;

class MisionesEscoltaSeeder extends Seeder
{
  public function run()
  {
    // Obtener usuarios con roles asignados (case insensitive)
    $roles    = ['escolta', 'auxiliar escolta'];
    $usuarios = User::whereRaw('LOWER(rol) IN (?, ?)', array_map('strtolower', $roles))->get();

    if ($usuarios->isEmpty()) {
      return;
    }

    $faker = \Faker\Factory::create();

    for ($i = 1; $i <= 15; $i++) {
      // Seleccionar aleatoriamente entre 1 y 5 escoltas
      $numEscoltas = rand(1, min(5, $usuarios->count()));
      $escoltas    = $usuarios->random($numEscoltas)->pluck('id')->toArray();

      $numVehiculos = rand(1, 3);

      $fechaInicio = now()->subDays(rand(1, 10));
      $fechaFin    = (clone $fechaInicio)->addDays(rand(1, 5));

      Misiones::create([
        'agentes_id' => $escoltas,
        'nivel_amenaza' => $faker->randomElement(['Baja', 'Media', 'Alta']),
        'tipo_servicio' => $faker->randomElement(['Escolta', 'Protección', 'Traslado']),
        'nombre_clave' => "Misión " . $faker->unique()->word . " ({$i})",
        'ubicacion' => json_encode([
          'direccion' => $faker->address
        ]),
        'armados' => (rand(0, 1) ? 'Sí' : 'No'),
        'fecha_inicio' => $fechaInicio->format('Y-m-d'),
        'fecha_fin' => $fechaFin->format('Y-m-d'),
        'cliente' => $faker->name,
        'pasajeros' => rand(2, 4),
        'tipo_operacion' => $faker->randomElement(['Normal', 'Especial', 'Urgente']),
        'num_vehiculos' => $numVehiculos,
        'tipo_vehiculos' => json_encode(
          array_map(function () use ($faker) {
            return [
              'marca' => $faker->randomElement(['Toyota', 'Ford', 'Nissan', 'Chevrolet', 'Honda']),
              'modelo' => $faker->randomElement(['Corolla', 'Focus', 'Sentra', 'Aveo', 'Civic'])
            ];
          }, range(1, $numVehiculos))
        ),
        'arch_mision' => null,
        'datos_hotel' => rand(0, 1) ? json_encode([
          'nombre' => $faker->company,
          'direccion' => $faker->address,
          'telefono' => $faker->phoneNumber,
          'habitacion' => rand(100, 999)
        ]) : null,
        'datos_aeropuerto' => rand(0, 1) ? json_encode([
          'nombre' => $faker->city . ' Airport',
          'direccion' => $faker->address,
          'telefono' => $faker->phoneNumber,
          'codigo' => strtoupper($faker->lexify('???')),
        ]) : null,
        'datos_vuelo' => rand(0, 1) ? json_encode([
          'aerolinea' => $faker->company,
          'numero_vuelo' => strtoupper($faker->bothify('??###')),
          'hora_salida' => $faker->time('H:i'),
          'hora_llegada' => $faker->time('H:i'),
          'puerta' => strtoupper($faker->bothify('??')),
          'asiento' => strtoupper($faker->bothify('##A')),
        ]) : null,
        'datos_hospital' => rand(0, 1) ? json_encode([
          'nombre' => $faker->company . ' Hospital',
          'direccion' => $faker->address,
          'telefono' => $faker->phoneNumber,
          'habitacion' => rand(100, 999)
        ]) : null,
        'datos_embajada' => rand(0, 1) ? json_encode([
          'pais' => $faker->country,
          'direccion' => $faker->address,
          'telefono' => $faker->phoneNumber,
          'contacto' => $faker->name
        ]) : null,
        'lat' => 25.6866,
        'lng' => -100.3161,
        'estatus' => $faker->randomElement(['pendiente', 'en progreso', 'completada', 'cancelada']),
      ]);
    }
  }
}