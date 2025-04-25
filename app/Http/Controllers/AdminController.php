<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SolicitudAlta;
use App\Models\User;

class AdminController extends Controller
{
    public function verUsuarios(){
        $users = User::all();
        return view('admi.verUsuarios', compact('users'));
    }

    public function tableroSupervisores(){
        return view('admi.tableroSupervisores');
    }

    public function verSolicitudesAltas(){

        $solicitudes = SolicitudAlta::where('status', 'En Proceso')
            ->where('observaciones', 'Solicitud enviada a Administrador.')
            ->get();

        return view('admi.verSolicitudesAltas', compact('solicitudes'));
    }

    public function editarUsuarioForm($id){
        $user = User::find($id);
        return view('admi.editarUsuarioForm', compact('user'));
    }
}
