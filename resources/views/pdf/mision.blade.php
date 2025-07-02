@php use Illuminate\Support\Str; @endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud de Vacaciones</title>
    <style>
        @page {
            margin: 0;
            size: 1280px 720px;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100vh;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            background-color: white;
        }

        .portada {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 2rem;
        }

        .logo {
            width: 300px;
            height: auto;
            margin-bottom: 2rem;
        }

        h1 {
            font-size: 36pt;
            color: #333;
            margin: 0;
        }

        .subtitulo {
            font-size: 18pt;
            color: #555;
            margin-top: 1rem;
        }

        .fechas {
            font-size: 20pt;
            font-weight: bold;
            color: #222;
            margin: 1rem 0;
        }

        .tipo-servicio {
            font-size: 24pt;
            font-weight: bold;
            color: #0066cc;
            margin-top: 2rem;
            text-transform: uppercase;
        }
        .center-text {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 10pt;
            margin-left: 100px;
        }

        .left-text {
            text-align: left;
            font-size: 12pt;
            line-height: 1.4;
            margin-left: 100px;
        }

        .spacer {
            margin-bottom: 10pt;
            margin-left: 100px;
        }
    </style>
</head>
<body>
    <div class="portada">
        <img src="{{ public_path('images/psc.png') }}" class="logo" alt="Logo">

        <h1>RESUMEN DE MISIÓN</h1>
        <div class="subtitulo">{{ $mision->ubicacion }}</div>
        <div class="fechas">{{ $mision->fecha_inicio }} – {{ $mision->fecha_fin }}</div>
        <div class="tipo-servicio">Tipo de Servicio: {{ $mision->tipo_servicio }}</div>
    </div>

    <table class="titulo-con-logo">
        <tr>
            <td class="logo">
                    <img src="{{ public_path('images/psc.png') }}" width="70">
            </td>
                    <br>
            <td style="width: 80px;"></td>
        </tr>
    </table>

    <div>
        <div class="center-text">
            <h2>Notificación</h2>
        </div>

        <div class="left-text">
            <p class="spacer">
                Por favor de contactar nuestro centro de operaciones en cualquier momento para la aclaración de alguna duda o comentario.
            </p>

            <p>
                Correo: monitoreo@spyt.com.mx<br>
                Línea de emergencia: #<br>
                Líneas no emergencia: #<br><br>
                En cualquier momento de emergencia, si estás utilizando nuestra aplicación, utiliza el botón de pánico o<br>
                inmediatamente contacta nuestro centro de operaciones para asistencia.
            </p>
        </div>
    </div>

</body>
</html>
