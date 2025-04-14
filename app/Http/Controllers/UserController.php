<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function crearUsuario(){
        return view('admi.crearUsuario');
    }

    public function registrarUsuario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'punto' => $request->punto,
            'empresa' => $request->empresa,
            'estatus' => 'activo',
            'fecha_ingreso' => date('Y-m-d'),
        ]);

        return redirect()->route('admin.verUsuarios')->with('success', '¡Usuario creado exitosamente!');
    }
}
