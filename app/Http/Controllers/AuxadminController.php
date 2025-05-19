<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudAlta;
use Carbon\Carbon;

class AuxadminController extends Controller
{
    public function nuevasAltas(){
        $altas = SolicitudAlta::where('estado', 'Aceptada')
            ->whereDate('fecha_ingreso', '>=', Carbon::today('America/Mexico_City')->subDays(4))
            ->get();
        return view('auxadmin.nuevasAltas', compact('altas'));
    }
}
