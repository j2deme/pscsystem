<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Historial de Incapacidades</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
            font-size: 18px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Historial de Incapacidades</h1>
        <p>Fecha de Generación: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nombre del Usuario</th>
                <th>Motivo</th>
                <th>Tipo Incapacidad</th>
                <th>Ramo Seguro</th>
                <th>Días</th>
                <th>Fecha Inicio</th>
                <th>Folio</th>
            </tr>
        </thead>
        <tbody>
            @forelse($incapacidades as $index => $incapacidad)
                <tr>
                    <td>{{ $index + 1 }}</td>

                    <td>{{ $incapacidad['user_name'] }}</td>

                    <td>{{ $incapacidad['motivo'] }}</td>
                    <td>{{ $incapacidad['tipo_incapacidad'] }}</td>
                    <td>{{ $incapacidad['ramo_seguro'] }}</td>
                    <td>{{ $incapacidad['dias_incapacidad'] }}</td>

                    <td>{{ \Carbon\Carbon::parse($incapacidad['fecha_inicio'])->format('d/m/Y') }}</td>

                    <td>{{ $incapacidad['folio'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">No se encontraron incapacidades para el reporte.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
