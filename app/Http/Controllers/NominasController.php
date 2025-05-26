<?php

namespace App\Http\Controllers;
use App\Models\SolicitudAlta;
use App\Models\SolicitudBajas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NominasController extends Controller
{
    public function antiguedades(){
        return view('nominas.antiguedades');
    }

    public function verBajas(){
        $bajas = SolicitudBajas::where('estatus', 'Aceptada')
            ->where('por', 'Renuncia')
            ->whereDate('fecha_baja', '>=', Carbon::today('America/Mexico_City')->subDays(5))
            ->paginate(10);
        return view('nominas.verBajas', compact('bajas'));
    }

    public function nuevasAltas(){
        $solicitudes = SolicitudAlta::where('status', 'Aceptada')
            ->whereDate('fecha_ingreso', '>=', Carbon::today('America/Mexico_City')->subDays(5))//si se requiere respetar a toda la quincena
            ->get();
        return view('nominas.nuevasAltas', compact('solicitudes'));
    }
}
