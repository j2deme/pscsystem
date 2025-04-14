<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupervisorController extends Controller
{
    public function nuevoUsuarioForm(){
        return view('supervisor.nuevoUsuarioForm');
    }
}
