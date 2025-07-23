<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sipare;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
class SipareController extends Controller
{

    public function form()
{
    return view('auxadmin.sipareForm');
}

public function upload(Request $request)
{
    $request->validate([
        'pdf_spyt' => 'required|mimes:pdf',
        'pdf_psc' => 'required|mimes:pdf',
        'pdf_montana' => 'required|mimes:pdf',
    ]);

    $currentMonth = Carbon::now()->startOfMonth();

    $exists = Sipare::where('mes', $currentMonth)->exists();

    if ($exists) {
        return back()->with('error', 'Ya se subió un archivo SIPARE este mes.');
    }

    $folder = 'sipares/pdf/' . now()->format('Y-m-d');

    $sipare = Sipare::create([
        'pdf_spyt' => $request->file('pdf_spyt')->store($folder, 'public'),
        'pdf_psc' => $request->file('pdf_psc')->store($folder, 'public'),
        'pdf_montana' => $request->file('pdf_montana')->store($folder, 'public'),
        'mes' => $currentMonth,
    ]);

    return back()->with('success', 'Archivos SIPARE subidos correctamente.');
}
}
