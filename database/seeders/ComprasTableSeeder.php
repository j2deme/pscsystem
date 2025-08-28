<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Compra;
use App\Models\Unidades;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ComprasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar que existan unidades
        $unidades = Unidades::where('estado_vehiculo', 'Activo')->get();

        if ($unidades->isEmpty()) {
            $this->command->info('No hay unidades activas para generar registros de compras.');
            return;
        }

        // Tipos de servicio/compra basados en el documento
        $tipos = [
            'Refacción',
            'Insumo',
            'Servicio Menor',
            'Compra Directa',
            'Mantenimiento',
            'Reparación',
            'Verificación',
            'Afinación',
            'Cambio de Llantas',
            'Servicio Mayor',
            'Accesorios',
            'Hojalatería y Pintura',
            'Siniestro'
        ];

        // Proveedores basados en el documento
        $proveedores = [
            'JASMAN AUTOMOTRIZ',
            'SERVICIO DE ALQUILER DE GRUA',
            'MARIBEL',
            'FERNANDO DIAZ SANCHEZ',
            'OSCAR ESTEBAN',
            'ALVAREZ SP',
            'DON DANY',
            'ACUMULADORES ZARAGOZA',
            'RADIADORES ALVAREZ',
            'JULIO CESAR MARTINEZ GUTIERREZ',
            'JKL GRUPO LLANTERO',
            'VILLANTAS',
            'AUTOZONE',
            'REFACCIONARIA VIERYA',
            'HERMINIA FAJARDO RUIZ',
            'MOTUL',
            'SEKURIT',
            'OXXO',
            'CYBERPAPELERIA VALLE',
            'URIEL',
            'MARIBEL MARTINEZ RAMIREZ',
            'CENTRO DE VERIFICACION',
            'GRUAS Y REFACCIONES DE SAN JUAN',
            'PRODYNAMICS',
            'NISSAN TOCHIGI',
            'CHEVROLET COACALCO',
            'LLANMART',
            'TALLER CEGONZA',
            'CHEVROLET DEL RIO',
            'RADIADORES ALVAREZ SP',
            'RADIADORES ALVAREZ ALADO',
            'SERVI AUTOMOTRIZ',
            'CLIMAS VALADEZ',
            'AUTOPOLIS',
            'CRISTALFACIL',
            'JASMAN SALAMANCA',
            'SPANICAR MEXICO, TEXCOCO'
        ];

        // Descripciones basadas en el documento
        $descripciones = [
            // Mantenimientos y servicios
            'AFINACION GENERAL',
            'AFINACION PREVENTIVA, 5 ACEITE 2 FILTROS',
            'SERVICIO, CAMBIO DE ACEITE, FILTROS Y BUJIAS',
            'AFINACION MAYOR CAMBIO ACEITE FILTROS BUJÍAS',
            'SERVICIO 10 MIL KMS',
            'SERVICIO 20 MIL KMS',
            'SERVICIO 150,000 KMS',
            'REVISIÓN Y CAMBIO DE ARNES Y LAMPARA LUCES FRONTALES',
            'LAVADO Y ENGRASADO DE CARROCERIA',
            'RECTIFICAR VOLANTE Y ACEITE DE TRASMISION',

            // Reparaciones
            'SE CAMBIO ORQUILLA SUPERIOR IZQUIERDA',
            'CAMBIO DE LIMPIAPARABRISAS',
            'CAMBIO DE BATERIA',
            'CAMBIO DE BALATAS TRASERAS',
            'RECTIFICADO DE DISCOS',
            'CAMBIO DE LLANTAS (4)',
            'CAMBIO DE LLANTAS (5)',
            'REPARACION DE CORTO SERVICIO A MARCHA Y ALTERNADOR',
            'CAMBIO DE DIFERENCIAL',
            'CAMBIO DE AMORTIGUADORES HORQUILAS TOPES BASES',
            'CAMBIO DE MANGUERA PRESIÓN DIRECCIÓN',
            'REPARACIÓN MOTOR',
            'CAMBIO DE CLUTCH',
            'CAMBIO VOLANTE CREMALLERA',
            'CAMBIO DE HORQUILLAS DELANTERAS',
            'CAMBIO DE BALEROS DELANTEROS',
            'CAMBIO DE SENSOR Y ARNÉS CIGÜEÑAL',
            'REPARACIÓN DE CABLEADO',
            'SOLDAR MOFLE',
            'CAMBIO DE BOMBA DE GASOLINA',
            'REPARACION CORTO EN COMPUTADORA',
            'CAMBIO DE BOMBA DE DIRECCION',
            'CAMBIO DE MAZA BALERO DELANTERA',
            'CAMBIO DE ROTULAS',
            'CAMBIO DE CREMALLERA',
            'CAMBIO CRISTAL PARABRISAS',
            'REPARACION DE LUCES DELANTERAS Y TRASERAS',
            'CAMBIO DE RADIADOR',
            'CAMBIO DE FOCOS',
            'CAMBIO DE PARRILLA DELANTERA',

            // Compras directas (insumos)
            'COMPRA DE LLANTAS',
            'COMPRA DE FOCO HALOGENO H4',
            'COMPRA DE ACEITE MOTOR',
            'COMPRA DE TALACHAS Y TIP TOP RADIAL',
            '4 FOCOS, 6 FOCOS, 4 FOCOS Y 1 SILICON',
            'COMPRA DE LIMPIAPARABRISAS',
            'COMPRA DE ACEITES',
            '3 LTS ACEITE DE MOTOR',
            '5 LTS ACEITE MOTOR',
            '34 COPIAS Y 3 EMICADO',
            'COMPRA DE AGUA PARA BASE',
            'COMPRA DE CARGADOR PARA CELULAR',
            '3 LT ACEITE P/MOTOR',
            '2 ACEITE AK 25W5',
            'CUBETAS DE ACEITE VALVOLINE',
            'BUJIAS IRIDIUM',
            'FILTRO DE ACEITE HILUX',
            'FILTRO DE AIRE HILUX',
            'FILTRO GASOLINA HILUX',
            'BUJIAS PLATIUM',

            // Siniestros
            'REPARACION DE DAÑOS POR SINIESTRO',
            'REEMPLAZO DE CRISTAL PARABRISAS',
            'REPARACION DE FACIA DELANTERA',
            'REPARACION DE COFRE',
            'REPARACION DE FOCOS DAÑADOS',
            'REPARACION DE SALPICADERA',
            'REPARACION DE FARO DELANTERO',
            'REPARACION DE DEFENSA DELANTERA',
        ];

        // Notas basadas en el documento
        $notas = [
            'REPORTA SUP JORGE',
            'REPORTA SUP ELISEO',
            'REPORTA SUP ANGEL EDUARDO',
            'REPORTA SUP CARLOS',
            'REPORTA PATRULLERO JOSE LUIS VALTIERRA',
            'REPORTA SANTIAGO',
            'Atendió seguros Axxa',
            'Fuimos responsables',
            'NO fuimos responsables',
            'Se hizo garantía de batería',
            'Continua en taller en espera de refacciones',
            'Se requiere cambiar chicote de freno',
            'Se detectan muelles estrelladas',
            'Se va checar si la rueda hace ruido',
            'Entrega llanta de refacción',
            'Se parchan don llantas',
            'Se le quedo parada la camioneta',
            'Se impacta en la parte trasera',
            'Se subió de frente a la glorieta',
            'Le cayó una piedra en Querétaro',
            'Da marcha pero no arranca',
            'Se solicita grúa',
            'Se traslada al taller',
            'Se ingresa nuevamente a la agencia',
            'Se estrelló el cristal parabrisas',
            'Se rompió faro derecho',
            'Daños mínimos',
            'Daños materiales en ambas unidades',
            'Se queda dormido reporta',
            'Se le revienta una llanta',
            'Se le quedan prendidas las luces',
            'Se le jaloneaba',
            'Viene gobernada a 150-165 KM',
            'Pudo ser por gasolina contaminada',
            'Se entrega unidad a México',
            'Se da de alta',
            'Se rota unidad en Silao',
            'Se instala el GPS en oficina',
            'Se da de baja temporal',
            'Se solicita reporte a planta',
            'Se hace responsable de los daños',
            'Se requiere ajuste en balatas',
            'Se requiere cambio de chicote',
            'Se requiere revisión de caja de válvulas',
            'Se requiere reparación completa',
        ];

        // Generar registros para cada unidad
        foreach ($unidades as $unidad) {
            // Determinar cuántos registros crear para esta unidad (entre 3 y 15)
            $cantidadRegistros = rand(3, 15);

            for ($i = 0; $i < $cantidadRegistros; $i++) {
                // Generar fecha aleatoria en los últimos 2 años
                $fechaInicio = now()->subYears(2);
                $fechaFin    = now();
                $fechaHora   = $this->generarFechaAleatoria($fechaInicio, $fechaFin);

                // Seleccionar datos aleatorios
                $tipo        = Arr::random($tipos);
                $proveedor   = Arr::random($proveedores);
                $descripcion = Arr::random($descripciones);

                // Generar costo aleatorio según el tipo
                $costo = $this->generarCostoAleatorio($tipo);

                // Generar kilometraje aleatorio (entre 10,000 y 250,000)
                $kilometraje = rand(10000, 250000);

                // Probabilidad de 20% de que tenga garantía
                $garantia = rand(1, 100) <= 20;

                // Probabilidad de 30% de que tenga notas
                $nota = rand(1, 100) <= 30 ? Arr::random($notas) : null;

                // Crear el registro
                Compra::create([
                    'unidad_id' => $unidad->id,
                    'fecha_hora' => $fechaHora,
                    'tipo' => $tipo,
                    'descripcion' => $descripcion,
                    'proveedor' => $proveedor,
                    'costo' => $costo,
                    'kilometraje' => $kilometraje,
                    'garantia' => $garantia,
                    'notas' => $nota,
                ]);
            }
        }

        $this->command->info('Se han generado registros de compras para ' . $unidades->count() . ' unidades.');
    }

    /**
     * Generar una fecha aleatoria entre dos fechas
     */
    private function generarFechaAleatoria($fechaInicio, $fechaFin)
    {
        $timestampInicio    = $fechaInicio->timestamp;
        $timestampFin       = $fechaFin->timestamp;
        $timestampAleatorio = rand($timestampInicio, $timestampFin);

        return date('Y-m-d H:i:s', $timestampAleatorio);
    }

    /**
     * Generar costo aleatorio según el tipo de servicio
     */
    private function generarCostoAleatorio($tipo)
    {
        switch ($tipo) {
            case 'Insumo':
                return rand(50, 500) + (rand(0, 99) / 100);

            case 'Refacción':
                return rand(200, 5000) + (rand(0, 99) / 100);

            case 'Servicio Menor':
                return rand(300, 2000) + (rand(0, 99) / 100);

            case 'Mantenimiento':
                return rand(500, 3000) + (rand(0, 99) / 100);

            case 'Reparación':
                return rand(1000, 8000) + (rand(0, 99) / 100);

            case 'Afinación':
                return rand(800, 4000) + (rand(0, 99) / 100);

            case 'Cambio de Llantas':
                return rand(4000, 15000) + (rand(0, 99) / 100);

            case 'Servicio Mayor':
                return rand(5000, 20000) + (rand(0, 99) / 100);

            case 'Hojalatería y Pintura':
                return rand(2000, 10000) + (rand(0, 99) / 100);

            case 'Siniestro':
                return rand(3000, 25000) + (rand(0, 99) / 100);

            case 'Verificación':
                return rand(200, 800) + (rand(0, 99) / 100);

            case 'Accesorios':
                return rand(500, 3000) + (rand(0, 99) / 100);

            case 'Compra Directa':
                return rand(100, 1000) + (rand(0, 99) / 100);

            default:
                return rand(100, 5000) + (rand(0, 99) / 100);
        }
    }
}