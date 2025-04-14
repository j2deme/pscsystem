<?php

namespace App\Http\Controllers;

use App\Models\SolicitudAlta;
use Illuminate\Http\Request;

class SupervisorController extends Controller
{
    public function nuevoUsuarioForm(){
        return view('supervisor.nuevoUsuarioForm');
    }

    public function guardarInfo(Request $request)
    {
        //dd($request->all());
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'apellido_paterno' => 'required|string|max:255',
                'apellido_materno' => 'required|string|max:255',
                'fecha_nacimiento' => 'required|date',
                'curp' => 'required|string|max:255',
                'nss' => 'required|string|max:255',
                'edo_civil' => 'required|string',
                'rfc' => 'required|string|max:255',
                'telefono' => 'required|string|max:255',
                'calle' => 'required|string|max:255',
                'num_ext' => 'required|integer',
                'colonia' => 'required|string|max:255',
                'ciudad' => 'required|string|max:255',
                'estado' => 'required|string|max:255',
                'rol' => 'required|string|max:255',
                'punto' => 'required|string|max:255',
                'empresa' => 'required|string',
                'email' => 'required|email|unique:solicitud_altas,email',
            ]);

            $solicitud = new SolicitudAlta();
            $solicitud->solicitante = auth()->user()->name;
            $solicitud->nombre = $request->name;
            $solicitud->apellido_paterno = $request->apellido_paterno;
            $solicitud->apellido_materno = $request->apellido_materno;
            $solicitud->fecha_nacimiento = $request->fecha_nacimiento;
            $solicitud->curp = $request->curp;
            $solicitud->nss = $request->nss;
            $solicitud->estado_civil = $request->edo_civil;
            $solicitud->rfc = $request->rfc;
            $solicitud->telefono = $request->telefono;
            $solicitud->domicilio_calle = $request->calle;
            $solicitud->domicilio_numero = $request->num_ext;
            $solicitud->domicilio_colonia = $request->colonia;
            $solicitud->domicilio_ciudad = $request->ciudad;
            $solicitud->domicilio_estado = $request->estado;
            $solicitud->rol = $request->rol;
            $solicitud->punto = $request->punto;
            $solicitud->empresa = $request->empresa;
            $solicitud->email = $request->email;
            $solicitud->estatura = '0.0';
            $solicitud->peso = '0.0';
            $solicitud->status = 'En Proceso';
            $solicitud->observaciones = 'Solicitud en revisión';

            $solicitud->save();

            //session(['user_id' => $solicitud->id]);

            return view('supervisor.subirArchivosForm', compact('solicitud'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al guardar la solicitud: ' . $e->getMessage());
        }
    }

    public function subirArchivosForm(){
        return view('supervisor.subirArchivosForm');
    }
}
