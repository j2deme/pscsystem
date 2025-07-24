<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SolicitudAltaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
       $user = DB::table('users')->insertGetId([
            'name' => 'Belen Chavez Mar',
            'email' => 'belen@spyt.com.mx',
            'password' => Hash::make('Belen!@1234'),
            'created_at' => now(),
            'updated_at' => now(),
            'estatus' => 'Activo',
            'rol' => 'AUXILIAR ADMINISTRATIVO',
        ]);

        DB::table('solicitud_altas')->insert([
            'nombre' => 'Belen',
            'apellido_paterno' => 'Chavez',
            'apellido_materno' => 'Mar',
            'fecha_nacimiento' => '1990-01-01',
            'curp' => 'BEMAR900101HDFRSLA01',
            'status' => 'Activo',
            'observaciones' => 'Solicitud Aceptada.',
            'departamento' => 'IMSS',
            'rol' => 'AUXILIAR ADMINISTRATIVO',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        }
}
