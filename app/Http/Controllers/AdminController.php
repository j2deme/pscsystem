<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}
