<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subpunto;

class SubpuntoSiglasSeeder extends Seeder
{
    public function run(): void
    {
        $siglas = [
            1 => 'CUS',
            2 => 'DAL',
            3 => 'TRN',
            4 => 'TRA',
            5 => 'BON',
            6 => 'HMD',
            7 => 'AA',
            8 => 'MK',
            9 => 'KAN',
            10 => 'CIM',
            11 => 'OFI',
            12 => 'ASS',
            13 => 'TDL',
            14 => 'SAC',
            15 => 'THE',
            16 => 'KMG',
            17 => 'GOB',
            18 => 'PEM',
            19 => 'RBO',
            20 => 'OOG',
            21 => 'COO',
            22 => 'MPA',
            23 => 'OAT',
            24 => 'PDO',
            25 => 'SIL',
            26 => 'CEL',
            27 => 'SAL',
            28 => 'ABV',
            29 => 'VMX',
            30 => 'WAT',
            31 => 'BMW',
            32 => 'ABI',
            33 => 'INT',
            34 => 'XAL',
            35 => 'MIC',
            36 => 'PUE',
            37 => 'TOL',
            38 => 'QUE',
            39 => 'SAL',
            40 => 'DRO',
            41 => 'MTY',

        ];

        foreach ($siglas as $id => $sigla) {
            Subpunto::where('id', $id)->update(['siglas' => $sigla]);
        }
    }
}
