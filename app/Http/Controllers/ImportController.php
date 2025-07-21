<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
//use Hash;
//use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Unidades;
use Exception;
use Illuminate\Support\Facades\Hash;

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
    ini_set('max_execution_time', 300);
    DB::beginTransaction();

    $importados = 0;
    $fallidos = 0;

    try {
        $file = $request->file('excel');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet(0);
        $rows = $sheet->toArray();

        foreach (array_slice($rows, 1) as $row) {
            try {
                $primeraCelda = strtoupper(trim($row[0] ?? ''));

                if (
                    $primeraCelda === '' ||
                    str_starts_with($primeraCelda, 'ALTAS') ||
                    str_contains($primeraCelda, 'SIN MOVIMIENTOS')
                ) {
                    continue;
                }

                $punto = ucwords(mb_strtolower(preg_replace('/\s+/', ' ', trim($row[0])), 'UTF-8'));
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

                if (empty($row[11]))
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

                $importados++;
            } catch (\Throwable $e) {
                $fallidos++;
                continue; // sigue con la siguiente fila
            }
        }

        DB::commit();

        return back()->with('success', "Importación completada. Importados: $importados, Fallidos: $fallidos.");
    } catch (Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Error al importar el archivo: ' . $e->getMessage());
    }
}


public function importarBajas(Request $request)
{
    ini_set('max_execution_time', 600);
    DB::beginTransaction();

    try {
        if (!$request->hasFile('excel')) {
            return back()->with('error', 'No se ha subido ningún archivo.');
        }

        $file = $request->file('excel');
        \Log::info("Iniciando importación del archivo: ".$file->getClientOriginalName());

        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getSheet(0);
        $rows = $sheet->toArray();

        $processed = 0;
        $skipped = 0;
        $fechaReferencia = Carbon::create(2023, 1, 1);

        foreach ($rows as $index => $row) {
            $fila = $index + 1;
            $nombreOriginal = trim($row[0] ?? '');

            if (empty($nombreOriginal) || str_starts_with(strtoupper($nombreOriginal), 'BAJAS') || str_contains(strtoupper($nombreOriginal), 'TOTAL')) {
                \Log::info("Fila {$fila} omitida (encabezado o vacía): {$nombreOriginal}");
                $skipped++;
                continue;
            }

            $partes = explode(' ', mb_convert_case($nombreOriginal, MB_CASE_TITLE, 'UTF-8'));

            if (count($partes) < 3) {
                \Log::warning("Nombre con estructura inesperada en fila {$fila}: {$nombreOriginal}");
                $skipped++;
                continue;
            }

            $apellidos = array_slice($partes, 0, 2);
            $nombres = array_slice($partes, 2);
            $nombreFormateado = implode(' ', $nombres).' '.implode(' ', $apellidos);


            try {
                $fechaIngreso = $row[2];
                $fechaBaja = $row[3];

                if (!$fechaIngreso || !$fechaBaja) {
                    \Log::warning("Fila {$fila} omitida - fechas inválidas: Ingreso=".($row[2] ?? 'NULL')." Baja=".($row[3] ?? 'NULL'));
                    $skipped++;
                    continue;
                }
            } catch (\Exception $e) {
                \Log::error("Error procesando fechas fila {$fila}: ".$e->getMessage());
                $skipped++;
                continue;
            }

            $descuento = isset($row[5]) ? floatval(str_replace(['$', ',', ' '], '', $row[5])) : 0;
            $nuevoRebaje = isset($row[6]) ? floatval(str_replace(['$', ',', ' '], '', $row[6])) : 0;

            $user = User::whereRaw('LOWER(TRIM(name)) = ?', [strtolower(trim($nombreFormateado))])
                      ->first();

            if (!$user) {
                \Log::warning("Usuario no encontrado - Fila {$fila}: {$nombreFormateado}");
                $skipped++;
                continue;
            }

            try {
                DB::table('solicitud_bajas')->insert([
                    'user_id' => $user->id,
                    'fecha_solicitud' => date('Y-m-d', strtotime($fechaBaja)),
                    'fecha_baja' => date('Y-m-d', strtotime($fechaBaja)),
                    'por' => trim($row[4] ?? ''),
                    'descuento' => $descuento,
                    'nuevorebajefiniquito' => $nuevoRebaje,
                    'estatus' => 'Aceptada',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $user->estatus = 'Inactivo';
                $user->save();

                $processed++;
                \Log::info("Registro insertado - Fila {$fila}: {$user->id} - {$nombreFormateado}");

            } catch (\Exception $e) {
                \Log::error("Error insertando fila {$fila}: ".$e->getMessage());
                $skipped++;
                continue;
            }
        }

        DB::commit();

        $message = "Importación completada. {$processed} registros procesados, {$skipped} filas omitidas.";
        \Log::info($message);

        return back()->with('success', $message);

    } catch (Exception $e) {
        DB::rollBack();
        \Log::error("Error general en importación: ".$e->getMessage());
        return back()->with('error', 'Error al importar: '.$e->getMessage());
    }
}

    public function importarUnidades(Request $request)
    {
        DB::beginTransaction();

        try {
            if (!$request->hasFile('excel')) {
                Log::error('No se subió ningún archivo.');
                return back()->with('error', 'No se subió ningún archivo.');
            }

            $file = $request->file('excel');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            foreach (array_slice($rows, 1) as $row) {
                if (empty($row[0])) continue;

                Unidades::create([
                    'nombre_propietario' => trim($row[0]),
                    'zona'               => trim($row[1]),
                    'marca'              => trim($row[2]),
                    'modelo'             => trim($row[3]),
                    'placas'             => trim($row[4]),
                    'kms'                => trim($row[5]),
                    'asignacion_punto'   => trim($row[6]),
                    'estado_vehiculo'    => trim($row[7]),
                    'observaciones'      => trim($row[8]),
                ]);
            }

            DB::commit();

            return back()->with('success', 'Unidades importadas correctamente.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error al importar unidades: ' . $e->getMessage());
            return back()->with('error', 'Error al importar el archivo: ' . $e->getMessage());
        }
    }


}
