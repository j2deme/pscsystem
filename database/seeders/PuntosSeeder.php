<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Punto;
use App\Models\Subpunto;

class PuntosSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'MONTERREY' => [
                ['CUSTODIO', '001'],
                ['DALTILE', '002'],
                ['TORRENOVO', '003'],
                ['TRASLADOS', '004'],
                ['BONETERA', '005'],
                ['HOMEDEPOT', '007'],
                ['AMERICAN AIRLINES', '009'],
                ['MARYKAY CORPORATIVO', '011'],
                ['CIMARRON', '012'],
                ['OFICINA', '044'],
                ['ASSET', '013'],
                ['TORRE DELTA', '014'],
                ['SACMI DE MEXICO', '015'],
                ['THERMO ELECTRICA', '016'],
                ['KINDER MORGAN', '019'],
                ['GOBAR', '020'],
                ['PEMCORP #2', '021'],
                ['ROCHE BOBOIS', '023'],
                ['OFF ON GREEN', '025'],
                ['COOPER LIGHT', '026'],
                ['MONTE PALATINO', '027'],
                ['OATEY', '029'],
                ['PLAZA DOMENA', '030'],
            ],
            'GUANAJUATO' => [
                ['SILAO', null],
                ['CELAYA', null],
                ['SALAMANCA', null],
            ],
            'NUEVO LAREDO' => [
                ['ZONA DE ABASTOS V', '017'],
            ],
            'MEXICO' => [
                ['VALLE DE MEXICO', null],
            ],
            'SLP' => [
                ['WATCO', '006'],
                ['BMW', '010'],
                ['ZONA DE ABASTOS I', '018'],
                ['INTERPUERTO Y TALLER', null],
            ],
            'XALAPA' => [
                ['XALAPA', null],
            ],
            'MICHOACAN' => [
                ['MICHOACAN', null],
            ],
            'PUEBLA' => [
                ['PUEBLA', null],
            ],
            'TOLUCA' => [
                ['TOLUCA', null],
            ],
            'QUERETARO' => [
                ['QUERETARO', null],
            ],
            'SALTILLO' => [
                ['SALTILLO', null],
            ],
            'DRONES' => [
                ['DRONES', null],
            ],
            'KANSAS' => [
                ['KANSAS', null],
            ],
        ];

        foreach ($data as $puntoNombre => $subpuntos) {
            $punto = Punto::firstOrCreate(['nombre' => $puntoNombre]);

            foreach ($subpuntos as [$nombre, $codigo]) {
                Subpunto::firstOrCreate([
                    'punto_id' => $punto->id,
                    'nombre' => $nombre,
                    'codigo' => $codigo,
                ]);
            }
        }
    }
}
