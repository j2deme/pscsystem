<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Hash;
use Illuminate\Support\Facades\Log;
use Exception;

class ImportController extends Controller
{
    public function importarExcel(Request $request)
    {
        DB::beginTransaction();

        try {

            if (!$request->hasFile('excel')) {
                Log::error('No se subió ningún archivo.');
                return back()->with('error', 'No se subió ningún archivo.');
            }

            $file = $request->file('excel');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet(0);
            $rows = $sheet->toArray();

            foreach (array_slice($rows, 3) as $row) {

                $nombre = ucwords(mb_strtolower(preg_replace('/\s+/', ' ', trim($row[3])), 'UTF-8'));
                $apellidoPaterno = ucwords(mb_strtolower(preg_replace('/\s+/', ' ', trim($row[4])), 'UTF-8'));
                $apellidoMaterno = ucwords(mb_strtolower(preg_replace('/\s+/', ' ', trim($row[5])), 'UTF-8'));

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

            DB::commit();

            return back()->with('success', 'Importación completada.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al importar el archivo: ' . $e->getMessage());
        }
    }

    public function importarArchivoRosa(Request $request)
    {
        DB::beginTransaction();

        try {
            $file = $request->file('excel');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet(0);
            $rows = $sheet->toArray();

            foreach (array_slice($rows, 1) as $row) {

                $primeraCelda = strtoupper(trim($row[0] ?? ''));

                if (
                    $primeraCelda === '' ||
                    str_starts_with($primeraCelda, 'ALTAS') ||
                    str_contains($primeraCelda, 'SIN MOVIMIENTOS')
                ) {
                    continue;
                }

                $nombre = ucwords(mb_strtolower(preg_replace('/\s+/', ' ', trim($row[1])), 'UTF-8'));
                $apellidoPaterno = ucwords(mb_strtolower(preg_replace('/\s+/', ' ', trim($row[2])), 'UTF-8'));
                $apellidoMaterno = ucwords(mb_strtolower(preg_replace('/\s+/', ' ', trim($row[3])), 'UTF-8'));
                $estadoCivil = ucwords(mb_strtolower(preg_replace('/\s+/', ' ', trim($row[6])), 'UTF-8'));

                $fechaNacimiento = Carbon::parse($row[5])->format('Y-m-d');
                $fechaIngreso = Carbon::parse($row[4])->format('Y-m-d');

                $infonavit = !empty($row[13]) ? $row[13] : 'N/A';
                $fonacot = !empty($row[17]) ? $row[17] : 'N/A';
                $reingreso = !empty($row[21]) ? 'SI' : 'NO';

                $email = null;
                $password = null;
                if (!empty($row[29]) && filter_var($row[29], FILTER_VALIDATE_EMAIL)) {
                    $email = $row[29];
                }

                if(empty($row[11]))
                    $password = Hash::make($row[10]);
                else
                    $password = Hash::make($row[11]);

                $solicitudAltaId = DB::table('solicitud_altas')->insertGetId([
                    'punto' => $punto,
                    'nombre' => $nombre,
                    'apellido_paterno' => $apellidoPaterno,
                    'apellido_materno' => $apellidoMaterno,
                    'fecha_nacimiento' => $fechaNacimiento,
                    'estado_civil' => $estadoCivil,
                    'curp' => $row[10],
                    'rfc' => $row[11],
                    'nss' => $row[12],
                    'infonavit' => $infonavit,
                    'fonacot' => $fonacot,
                    'telefono' => $row[20],
                    'reingreso' => $reingreso,
                    'sd' => $row[24],
                    'sdi' => $row[25],
                    'empresa' => 'PSC',
                    'email' => $email,
                    'status' => 'Aceptada',
                    'observaciones' => 'Solicitud Aceptada.'
                ]);

                $solDocsId = DB::table('documentacion_altas')->insertGetId([
                    'solicitud_id' => $solicitudAltaId,
                ]);

                DB::table('users')->insert([
                    'name' => $nombre . ' ' . $apellidoPaterno . ' ' . $apellidoMaterno,
                    'sol_alta_id' => $solicitudAltaId,
                    'sol_docs_id' => $solDocsId,
                    'email' => $email,
                    'password' => $password,
                    'fecha_ingreso' => $fechaIngreso,
                    'punto' => $row[0],
                    'empresa' => 'PSC',
                    'estatus' => 'Activo'
                ]);
            }

            DB::commit();

            return back()->with('success', 'Importación completada.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al importar el archivo: ' . $e->getMessage());
        }
    }
}
