<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Hash;

class ImportController extends Controller
{
    public function importarExcel(Request $request)
    {
        $file = $request->file('excel');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        foreach (array_slice($rows, 3) as $row) {

            $nombre = ucwords(mb_strtolower(trim($row[3]), 'UTF-8'));
            $apellidoPaterno = ucwords(mb_strtolower(trim($row[4]), 'UTF-8'));
            $apellidoMaterno = ucwords(mb_strtolower(trim($row[5]), 'UTF-8'));

            $fechaNacimiento = Carbon::parse($row[6])->format('Y-m-d');
            $fechaIngreso = Carbon::now('America/Mexico_City')->format('Y-m-d');

            $solicitudAltaId = DB::table('solicitud_altas')->insertGetId([
                'nss' => $row[0],
                'rfc' => $row[1],
                'curp' => $row[2],
                'nombre' => $nombre,
                'apellido_paterno' => $apellidoPaterno,
                'apellido_materno' => $apellidoMaterno,
                'fecha_nacimiento' => $fechaNacimiento,
                'rol' => $row[7],
                'empresa' => $row[8],
                'email' => $row[9],
                'status' => 'Aceptada',
                'departamento' => $row[10],
                'observaciones' => 'Solicitud Aceptada.'
            ]);

            $solDocsId = DB::table('documentacion_altas')->insertGetId([
                'solicitud_id' => $solicitudAltaId,
            ]);

            DB::table('users')->insert([
                'name' => $nombre.' '.$apellidoPaterno.' '.$apellidoMaterno,
                'sol_alta_id' => $solicitudAltaId,
                'sol_docs_id' => $solDocsId,
                'email' => $row[9],
                'password' => Hash::make($row[1]),
                'fecha_ingreso' => $fechaIngreso,
                'rol' => $row[7],
                'empresa' => $row[8],
                'estatus' => 'Activo'
            ]);
        }

        return back()->with('success', 'Importación completada.');
    }
}
