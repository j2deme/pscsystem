<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Unidades;
use App\Models\SolicitudAlta;
use App\Models\Archivonomina;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
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

    Log::info('Inicio de importación de archivo rosa');

    try {
        $file = $request->file('excel');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet(0);
        $rows = $sheet->toArray();

        foreach (array_slice($rows, 1) as $index => $row) {
            try {
                $primeraCelda = strtoupper(trim($row[0] ?? ''));

                if (
                    $primeraCelda === '' ||
                    str_starts_with($primeraCelda, 'ALTAS') ||
                    str_contains($primeraCelda, 'SIN MOVIMIENTOS') ||
                    str_contains($primeraCelda, 'SIN ALTAS')
                ) {
                    Log::info("Fila $index omitida (vacía o encabezado)");
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
                Log::info("Fila $index importada correctamente: $nombre $apellidoPaterno $apellidoMaterno");

            } catch (\Throwable $e) {
                $fallidos++;
                Log::error("Error en fila $index: " . $e->getMessage(), ['fila' => $row]);
                continue;
            }
        }

        DB::commit();

        Log::info("Importación completada. Importados: $importados, Fallidos: $fallidos");

        return back()->with('success', "Importación completada. Importados: $importados, Fallidos: $fallidos.");

    } catch (Exception $e) {
        DB::rollBack();
        Log::error('Error general al importar archivo rosa: ' . $e->getMessage());
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


     public function unifyDuplicates(Request $request)
    {
        // Opcional: poner un límite de tiempo
        ini_set('max_execution_time', 300);

        \Log::info('Iniciando unificación de duplicados desde la interfaz.');

        try {
            // Paso 1: Obtener nombres duplicados (insensible a mayúsculas)
            $duplicates = DB::table('users')
                ->select(DB::raw('LOWER(name) as lower_name'), DB::raw('COUNT(*) as count'))
                ->groupBy(DB::raw('LOWER(name)'))
                ->having('count', '>', 1)
                ->pluck('lower_name');

            if ($duplicates->isEmpty()) {
                return redirect()->back()->with('info', 'No se encontraron usuarios duplicados.');
            }

            $processed = 0;
            $logInfo = "<strong>Usuarios procesados:</strong><ul>";

            foreach ($duplicates as $lowerName) {
                $users = User::whereRaw('LOWER(name) = ?', [$lowerName])
                    ->orderBy('fecha_ingreso', 'asc')
                    ->get();

                if ($users->count() <= 1) continue;

                $latestUser = $users->last();
                $solicitudAlta = SolicitudAlta::find($latestUser->sol_alta_id);

                if (!$solicitudAlta) {
                    \Log::warning("SolicitudAlta no encontrada para sol_alta_id: {$latestUser->sol_alta_id}");
                    continue;
                }

                // Construir historial de reingresos
                $reingresos = $users->map(function ($user, $index) {
                    $fecha = Carbon::parse($user->fecha_ingreso)->format('d/m/Y');
                    return ($index + 1) . "° ingreso: {$fecha}";
                })->implode(', ');

                // Actualizar
                $solicitudAlta->reingreso = $reingresos;
                $solicitudAlta->save();

                // Eliminar antiguos
                $users->except($latestUser->id)->each(function ($user) {
                    \Log::info("Procesando usuario antiguo: {$user->id}, {$user->name}, ingreso: {$user->fecha_ingreso}");

                    // Marcar como Inactivo ANTES de eliminar
                    $user->estatus = 'Inactivo';
                    $user->save();
                    $user->delete();
                });

                $logInfo .= "<li><strong>{$users->first()->name}</strong>: {$users->count()} ingresos unificados</li>";
                $processed++;
            }

            $logInfo .= "</ul>";

            return redirect()->back()->with('success', "✅ Proceso completado. Se unificaron $processed usuarios.")
                             ->with('info', $logInfo);

        } catch (\Exception $e) {
            \Log::error('Error en unifyDuplicates: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Ocurrió un error: ' . $e->getMessage());
        }
    }

public function updateDestajos()
{
    ini_set('max_execution_time', 600);
    ini_set('memory_limit', '1024M');
    set_time_limit(600);

    $offset = request('offset', 0);
    $limit = 10; // Procesar de 10 en 10 para ser más conservador

    $registros = Archivonomina::whereNotNull('arch_destajo')
        ->where('arch_destajo', '!=', '')
        ->skip($offset)
        ->take($limit)
        ->get();

    $actualizados = 0;
    $totalProcesados = 0;

    foreach ($registros as $registro) {
        try {
            if (!Storage::disk('public')->exists($registro->arch_destajo)) {
                continue;
            }

            $filePath = Storage::disk('public')->path($registro->arch_destajo);

            if (!is_readable($filePath)) {
                continue;
            }

            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $this->encontrarHojaResumen($spreadsheet);

            if (!$worksheet) {
                continue;
            }

            $valorDestajo = $this->encontrarValorDestajo($worksheet);

            if ($valorDestajo !== null) {
                $registro->total_destajos = $valorDestajo;
                $registro->save();
                $actualizados++;
            }

            $totalProcesados++;

        } catch (\Exception $e) {
            \Log::error("Error registro {$registro->id}: " . $e->getMessage());
            continue;
        }
    }

    // Verificar si hay más registros por procesar
    $totalRegistros = Archivonomina::whereNotNull('arch_destajo')
        ->where('arch_destajo', '!=', '')
        ->count();

    $quedanMas = ($offset + $limit) < $totalRegistros;

    return response()->json([
        'success' => true,
        'message' => "Lote procesado ({$offset}-" . ($offset + $limit) . "). Actualizados: {$actualizados}/{$totalProcesados}",
        'actualizados' => $actualizados,
        'continuar' => $quedanMas,
        'siguiente_offset' => $offset + $limit,
        'total_registros' => $totalRegistros,
        'procesados_hasta_ahora' => $offset + $totalProcesados
    ]);
}

private function encontrarHojaResumen($spreadsheet)
{
    if ($spreadsheet->sheetNameExists('RESUMEN')) {
        return $spreadsheet->getSheetByName('RESUMEN');
    }

    $sheetCount = $spreadsheet->getSheetCount();
    if ($sheetCount > 0) {
        return $spreadsheet->getSheet($sheetCount - 1);
    }

    return null;
}

private function encontrarValorDestajo($worksheet)
{
    $highestRow = $worksheet->getHighestRow();
    $highestColumn = $worksheet->getHighestColumn();

    \Log::info("Buscando en hoja. Filas: {$highestRow}, Columnas: {$highestColumn}");

    for ($row = 1; $row <= $highestRow; $row++) {
        for ($col = 'A'; $col <= $highestColumn && $col <= 'J'; $col++) {
            $cellCoordinate = $col . $row;
            $cell = $worksheet->getCell($cellCoordinate);
            $cellValue = $cell->getValue();

            if ($cellValue && $this->esTotalDestajo($cellValue)) {
                \Log::info("Encontrado 'TOTAL DESTAJO' en celda: {$cellCoordinate} con valor: " . trim($cellValue));

                // Obtener la celda a la derecha
                $colIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($col);
                $nextCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
                $nextCellCoordinate = $nextCol . $row;

                \Log::info("Buscando valor en celda: {$nextCellCoordinate}");

                $nextCell = $worksheet->getCell($nextCellCoordinate);
                $valorCeldaDerecha = $nextCell->getValue();
                $valorCalculado = $nextCell->getCalculatedValue(); // Obtener el valor calculado
                $valorFormateado = $nextCell->getFormattedValue();

                \Log::info("Valor crudo: " . var_export($valorCeldaDerecha, true));
                \Log::info("Valor calculado: " . var_export($valorCalculado, true));
                \Log::info("Valor formateado: " . $valorFormateado);

                // Priorizar el valor calculado, luego el formateado
                $valorNumerico = null;

                if (is_numeric($valorCalculado)) {
                    $valorNumerico = (float) $valorCalculado;
                    \Log::info("Usando valor calculado: " . $valorNumerico);
                } else {
                    $valorNumerico = $this->extraerValorNumerico($valorFormateado);
                    \Log::info("Usando valor formateado procesado: " . var_export($valorNumerico, true));
                }

                if ($valorNumerico !== null) {
                    return $valorNumerico;
                } else {
                    \Log::info("No se pudo extraer valor numérico");
                }
            }
        }
    }

    return null;
}

private function esTotalDestajo($texto)
{
    if (!is_string($texto)) {
        return false;
    }

    $textoNormalizado = trim(strtoupper($texto));
    $esTotalDestajo = strpos($textoNormalizado, 'TOTAL DESTAJO') !== false;

    if ($esTotalDestajo) {
        \Log::info("Coincidencia encontrada: " . $textoNormalizado);
    }

    return $esTotalDestajo;
}

private function extraerValorNumerico($valor)
{
    if ($valor === null || $valor === '') {
        return null;
    }

    if (is_numeric($valor)) {
        return (float) $valor;
    }

    // Limpiar el texto para extraer números
    $valorString = (string) $valor;
    $valorLimpio = preg_replace('/[^\d.,]/', '', $valorString);

    if (empty($valorLimpio)) {
        return null;
    }

    \Log::debug("Valor limpio: " . $valorLimpio);

    // Manejar diferentes formatos de número
    if (strpos($valorLimpio, ',') !== false && strpos($valorLimpio, '.') !== false) {
        // Determinar cuál es el separador decimal
        $lastDot = strrpos($valorLimpio, '.');
        $lastComma = strrpos($valorLimpio, ',');

        if ($lastDot > $lastComma) {
            // Formato: 1,234.56 (punto como decimal)
            $valorLimpio = str_replace(',', '', $valorLimpio);
        } else {
            // Formato: 1.234,56 (coma como decimal)
            $valorLimpio = str_replace('.', '', $valorLimpio);
            $valorLimpio = str_replace(',', '.', $valorLimpio);
        }
    } elseif (strpos($valorLimpio, ',') !== false) {
        // Posiblemente formato europeo, verificar si tiene sentido como decimal
        $parts = explode(',', $valorLimpio);
        if (strlen(end($parts)) <= 2 && count($parts) > 1) {
            // Última parte tiene 2 o menos dígitos, probablemente decimal
            $valorLimpio = str_replace('.', '', $valorLimpio);
            $valorLimpio = str_replace(',', '.', $valorLimpio);
        } else {
            // Parte entera con comas como separadores de miles
            $valorLimpio = str_replace(',', '', $valorLimpio);
        }
    }

    return is_numeric($valorLimpio) ? (float) $valorLimpio : null;
}
}
