<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UsuariosPruebaSeeder extends Seeder
{
  public function run()
  {
    $roles = [
      'Auxiliar RH',
      'Auxiliar Monitoreo',
      'Patrullero',
      'Escolta',
      'Auxiliar Patrullero',
      'Auxiliar Escolta',
    ];

    $nombres = [
      'Juan Pérez',
      'María López',
      'Carlos Sánchez',
      'Ana Torres',
      'Luis Ramírez',
      'Sofía Gómez',
      'Miguel Herrera',
      'Laura Díaz',
      'José Castro',
      'Paola Ruiz',
      'Pedro Morales',
      'Lucía Vargas',
      'Andrés Jiménez',
      'Valeria Mendoza',
      'Jorge Silva'
    ];

    $empresas = ['Montana', 'PSC', 'SPYTT'];

    for ($i = 0; $i < 15; $i++) {
      User::create([
        'name' => $nombres[$i],
        'email' => Str::slug($nombres[$i], '.') . '@prueba.com',
        'password' => bcrypt('password123'),
        'rol' => $roles[$i % count($roles)],
        'estatus' => 'Activo',
        'fecha_ingreso' => now()->subDays(rand(1, 365)),
        'punto' => 'Punto ' . rand(1, 5),
        'empresa' => $empresas[rand(0, count($empresas) - 1)],
        'num_empleado' => rand(1000, 9999)
      ]);
    }
  }
}
