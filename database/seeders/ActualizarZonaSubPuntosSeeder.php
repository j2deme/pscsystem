<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActualizarZonaSubPuntosSeeder extends Seeder
{
    public function run()
    {
        $actualizaciones = [
            'DALTILE' => 'A',
            'BONETERA' => 'B',
            'ROCHE BOBOIS' => 'B',
            'MONTE PALATINO' => 'B',
            'CIMARRON' => 'B',
            'TORRENOVO' => 'B',
            'AMERICAN AIRLINES' => 'B',
            'SACMI DE MEXICO' => 'B',
            'PLAZA DOMENA' => 'B',
            'GOBAR' => 'B',
            'COOPER LIGHT' => 'B',
            'OFICINA' => 'B',
            'HOMEDEPOT' => 'C',
            'OFF ON GREEN' => 'C',
            'TORRE DELTA' => 'C',
            'MARYKAY CORPORATIVO' => 'C',
            'OATEY' => 'C',
            'THERMO ELECTRICA' => 'D',
            'PEMCORP #2' => 'D',
            'KINDER MORGAN' => 'D',
            'KANSAS' => 'E',
        ];

        foreach ($actualizaciones as $nombre => $zona) {
            DB::table('subpuntos')
                ->where('nombre', $nombre)
                ->update(['zona' => $zona]);
        }
    }
}
