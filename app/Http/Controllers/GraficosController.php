<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudAlta;
use App\Models\SolicitudBajas;
use App\Models\Incapacidad;
use App\Models\RiesgoTrabajo;
use Carbon\Carbon;
use App\Models\User;

class GraficosController extends Controller
{
    public function index(Request $request)
{
     Carbon::setLocale('es');
        setlocale(LC_TIME, 'es_ES.UTF-8');

    $punto = $request->input('punto');
    $fechaInicio = $request->input('fecha_inicio') ?? Carbon::now()->startOfYear();
    $fechaFin = $request->input('fecha_fin') ?? Carbon::now()->endOfYear();

    $meses = collect(range(1, 12))->map(function ($mes) {
         return ucfirst(Carbon::create()->month($mes)->translatedFormat('M'));
    });

    // Recolectar por mes
    $altasPorMes = SolicitudAlta::selectRaw('MONTH(created_at) as mes, COUNT(*) as total')
        ->when($punto, fn($q) => $q->whereHas('usuario', fn($sub) => $sub->where('punto', $punto)))
        ->whereBetween('created_at', [$fechaInicio, $fechaFin])
        ->groupBy('mes')
        ->pluck('total', 'mes');

    $bajasPorMes = SolicitudBajas::selectRaw('MONTH(created_at) as mes, COUNT(*) as total')
        ->when($punto, fn($q) => $q->whereHas('user', fn($sub) => $sub->where('punto', $punto)))
        ->whereBetween('created_at', [$fechaInicio, $fechaFin])
        ->groupBy('mes')
        ->pluck('total', 'mes');

    $incapPorMes = Incapacidad::selectRaw('MONTH(created_at) as mes, COUNT(*) as total')
        ->when($punto, fn($q) => $q->whereHas('user', fn($sub) => $sub->where('punto', $punto)))
        ->whereBetween('created_at', [$fechaInicio, $fechaFin])
        ->groupBy('mes')
        ->pluck('total', 'mes');

    $riesgosPorMes = RiesgoTrabajo::selectRaw('MONTH(created_at) as mes, COUNT(*) as total')
        ->when($punto, fn($q) => $q->whereHas('user', fn($sub) => $sub->where('punto', $punto)))
        ->whereBetween('created_at', [$fechaInicio, $fechaFin])
        ->groupBy('mes')
        ->pluck('total', 'mes');


    $altas = collect(range(1, 12))->map(fn($m) => $altasPorMes[$m] ?? 0);
    $bajas = collect(range(1, 12))->map(fn($m) => $bajasPorMes[$m] ?? 0);
    $incapacidades = collect(range(1, 12))->map(fn($m) => $incapPorMes[$m] ?? 0);
    $riesgos = collect(range(1, 12))->map(fn($m) => $riesgosPorMes[$m] ?? 0);

    return view('auxadmin.index', compact('meses', 'altas', 'bajas', 'incapacidades', 'riesgos'));
}


}
