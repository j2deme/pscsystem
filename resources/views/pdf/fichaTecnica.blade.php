@php
    $fotoPath = public_path($docs->arch_foto);
    $tipo = $user->solicitudAlta->tipo_empleado;
    if($tipo == null)
        $tipo = 'oficina';

    if ($tipo === 'armado') {
        $documentosObligatorios = [
            ['label' => 'Solicitud/CV', 'name' => 'arch_solicitud_empleo'],
            ['label' => 'INE', 'name' => 'arch_ine'],
            ['label' => 'NSS', 'name' => 'arch_nss'],
            ['label' => 'CURP', 'name' => 'arch_curp'],
            ['label' => 'RFC', 'name' => 'arch_rfc'],
            ['label' => 'Acta de Nacimiento', 'name' => 'arch_acta_nacimiento'],
            ['label' => 'Comprobante de Estudios', 'name' => 'arch_comprobante_estudios'],
            ['label' => 'Comprobante de Domicilio', 'name' => 'arch_comprobante_domicilio'],
            ['label' => 'Carta de Recomendación Laboral', 'name' => 'arch_carta_rec_laboral'],
            ['label' => 'Carta de Recomendación Personal', 'name' => 'arch_carta_rec_personal'],
            ['label' => 'Cartilla Militar', 'name' => 'arch_cartilla_militar'],
            ['label' => 'Antidoping', 'name' => 'arch_antidoping'],
            ['label' => 'Carta de No Antecedentes Penales', 'name' => 'arch_carta_no_penales'],
            ['label' => 'Contrato', 'name' => 'arch_contrato'],
            ['label' => 'Fotografía (Reciente)', 'name' => 'arch_foto'],
        ];
    } else {
        $documentosObligatorios = [
            ['label' => 'Solicitud/CV', 'name' => 'arch_solicitud_empleo'],
            ['label' => 'INE', 'name' => 'arch_ine'],
            ['label' => 'NSS', 'name' => 'arch_nss'],
            ['label' => 'CURP', 'name' => 'arch_curp'],
            ['label' => 'RFC', 'name' => 'arch_rfc'],
            ['label' => 'Acta de Nacimiento', 'name' => 'arch_acta_nacimiento'],
            ['label' => 'Comprobante de Estudios', 'name' => 'arch_comprobante_estudios'],
            ['label' => 'Comprobante de Domicilio', 'name' => 'arch_comprobante_domicilio'],
            ['label' => 'Carta de Recomendación Laboral', 'name' => 'arch_carta_rec_laboral'],
            ['label' => 'Carta de Recomendación Personal', 'name' => 'arch_carta_rec_personal'],
            ['label' => 'Contrato', 'name' => 'arch_contrato'],
            ['label' => 'Fotografía (Reciente)', 'name' => 'arch_foto'],
        ];
    }
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 9.5px;
        }
        .header, .section-title {
            background-color: #ddd;
            font-weight: bold;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        td, th {
            border: 1px solid #000;
            padding: 4px;
        }
        .small {
            font-size: 10px;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin: 6px 0;
        }
        .foto {
            border: 1px solid #000;
            width: 70px;
            height: 70px;
        }
    </style>
</head>
<body>

    <table>
        <tr>
            <td rowspan="2" style="width: 70px; heigth: 70px;">
                @if($user->empresa == 'PSC')
                    <img src="{{public_path('/images/psc.png')}}" alt="PSC" height="70" width="70">
                @elseif ($user->empresa == 'Montana')
                    <img src="{{public_path('/images/montana.png')}}" alt="Montana" height="70" width="70">
                @elseif($user->empresa == 'SPYT')
                    <img src="{{public_path('/images/spyt.png')}}" alt="SPYT" height="70" width="70">
                @else
                @endif
            </td>
            <td colspan="2" class="header">
                @if($user->empresa == 'PSC')
                    Private Security Contractors de México, S.A. de C.V.
                    @elseif ($user->empresa == 'Montana')
                    Suministros Comerciales Montana, S.A. de C.V.
                    @elseif($user->empresa == 'SPYT')
                    Sociedad de Protección y Traslado, S.A. de C.V.
                    @else
                @endif
            </td>
        </tr>
        <tr>
            </td>
            <td>Código:<br><strong>P-RH-25</strong></td>
        </tr>
        <tr>
            <td>Fecha de emisión:</td>
            <td>Fecha de revisión:</td>
            <td>Revisión:<br>1</td>
            <td></td>
        </tr>
    </table>

    <div class="title">FICHA TÉCNICA</div>

    <table>
    <tr>
        <td colspan="3">Fecha de Ingreso</td>
        <td colspan="3">Número de expediente</td>
        <td colspan="2">PSC/</td>
        <td rowspan="4" style="width: 120px; height: 120px;">

            @if(file_exists($fotoPath))
                <img src="{{ $fotoPath }}" style="width: 120px; height: 120px; object-fit: cover;">
            @else
                <div style="width: 120px; height: 120px; text-align: center; line-height: 120px;">Sin foto</div>
            @endif
        </td>
    </tr>
    <tr class="section-title">
        <td colspan="8">Datos Personales</td>
    </tr>
    <tr>
        <td colspan="2">Apellido Paterno</td>
        <td colspan="2">Apellido Materno</td>
        <td colspan="2">Nombre(s)</td>
        <td>Edad</td>
        <td>Estatura</td>
    </tr>
    <tr>
        <td colspan="2">{{ $user->solicitudAlta->apellido_paterno }}</td>
        <td colspan="2">{{ $user->solicitudAlta->apellido_materno }}</td>
        <td colspan="2">{{ $user->solicitudAlta->nombre }}</td>
        <td>{{ $user->solicitudAlta->edad }}</td>
        <td>{{ $user->solicitudAlta->estatura }}</td>
    </tr>
    <tr>
        <td>Peso</td>
        <td>Estado Civil</td>
        <td colspan="3">Lugar de Nacimiento</td>
        <td colspan="3">Fecha de Nacimiento</td>
        <td class="no-border"></td>
    </tr>
    <tr>
        <td>{{ $user->solicitudAlta->peso }}</td>
        <td>{{ $user->solicitudAlta->estado_civil }}</td>
        <td colspan="3">{{ $user->solicitudAlta->lugar_nacimiento }}</td>
        <td colspan="3">{{ $user->solicitudAlta->fecha_nacimiento }}</td>
        <td class="no-border"></td>
    </tr>
</table>
    <table>
    <tr class="section-title">
        <td colspan="6">Domicilio</td>
    </tr>
    <tr>
        <td>Calle</td>
        <td>No.</td>
        <td>Colonia</td>
        <td>Municipio</td>
        <td colspan="2">Estado</td>
    </tr>
    <tr>
        <td>{{ $user->solicitudAlta->domicilio_calle ?? '' }}</td>
        <td>{{ $user->solicitudAlta->domicilio_numero ?? '' }}</td>
        <td>{{ $user->solicitudAlta->domicilio_colonia ?? '' }}</td>
        <td>{{ $user->solicitudAlta->domicilio_ciudad ?? '' }}</td>
        <td colspan="2">{{ $user->solicitudAlta->domicilio_estado ?? '' }}</td>
    </tr>
</table>


    <table>
        <tr class="section-title"><td colspan="6">Información de Documentos</td></tr>
        <tr>
            <td>No. IMSS</td><td>CURP</td><td>No. IFE</td><td>Licencia de conducir</td><td>Banco</td><td>Crédito INFONAVIT</td>
        </tr>
        <tr>
            <td>{{$user->solicitudAlta->nss}}</td><td>{{$user->solicitudAlta->curp}}</td><td></td><td>NO</td><td>NO</td><td>{{$user->solicitudAlta?->infonavit?: 'N/A'}}</td>
        </tr>
        <tr>
            <td>Carta de NO Penales</td><td>RFC</td><td colspan="4"></td>
        </tr>
        <tr><td>NO</td><td>{{$user->solicitudAlta->rfc}}</td><td colspan="4"></td></tr>
    </table>

    <table>
        <tr class="section-title"><td colspan="4">Teléfonos de Contacto</td></tr>
        <tr>
            <td>Celular personal</td><td>Casa / Recados</td>
            <td>En caso de emergencias llamar a</td><td>Teléfono</td>
        </tr>
        <tr><td>{{$user->solicitudAlta->telefono}}</td><td></td><td></td><td>0</td></tr>
    </table>

    <table>
        <tr class="section-title"><td colspan="5">Datos Familiares</td><td class="section-title" colspan="2">Escolaridad</td></tr>
        <tr>
            <td>Parentesco</td>
            <td>Apellido Paterno</td>
            <td>Apellido Materno</td>
            <td>Nombre(s)</td>
            <td>Fecha</td>
            <td colspan="2">(último grado de estudios)</td>
        </tr>
        @for ($i = 0; $i < 6; $i++)
        <tr>
            <td></td><td></td><td></td><td></td><td>N/A</td><td colspan="2">@if($i==0) N/A @endif</td>
        </tr>
        @endfor
    </table>

    <table>
        <tr>
            <td class="section-title">Salud</td>
            <td colspan="4">
                Alergias: <span style="color: red">NO</span> &nbsp;&nbsp;
                Diabetes: <span style="color: red">NO</span> &nbsp;&nbsp;
                Hipertensión: <span style="color: red">NO</span> &nbsp;&nbsp;
                Tratamiento: <span style="color: red">NO</span> &nbsp;&nbsp;
                Antecedentes: <span style="color: red">NO</span>
            </td>
        </tr>
    </table>

    <table>
        <tr class="section-title"><td colspan="5">Datos del Servicio Asignado</td></tr>
        <tr>
            <td>Servicio</td><td>Puesto</td><td>Posición</td><td colspan="2">Sueldo Mensual</td>
        </tr>
        <tr>
            <td></td><td>{{$user->rol}}</td><td>0</td><td colspan="2">{{$user->solicitudAlta->sueldo_mensual}}</td>
        </tr>
    </table>

    <table>
        <tr class="section-title"><td>Conocimientos y habilidades</td></tr>
        <tr><td style="height: 40px;"></td></tr>
    </table>

    <table>
        <tr class="section-title"><td>Señas particulares</td></tr>
        <tr><td style="height: 40px;"></td></tr>
    </table>
</body>
</html>
