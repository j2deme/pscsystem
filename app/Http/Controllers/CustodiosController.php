<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustodiosController extends Controller
{
    public function nuevaMisionForm(){
        return view('custodios.nuevaMisionForm');
    }


}
