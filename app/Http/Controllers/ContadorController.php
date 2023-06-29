<?php

namespace App\Http\Controllers;
use App\Models\Contador;
use App\Models\Dmeter;

use Illuminate\Http\Request;
use Carbon\Carbon;

class ContadorController extends Controller
{

    public function mostrarContadores()
    {
        $contadores = Contador::all();

        return view('home', ['contadores' => $contadores]);
    }

  


  
}
