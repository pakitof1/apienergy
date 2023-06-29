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

        //hacer join con dmeter, y meter en variable contadores, el tola consumido y eso

        return view('home', ['contadores' => $contadores]);
    }

    /**
      * Función que devuelve los datos de un contador almacenados en la base datos
      */
      /*public static function getDatosContador($fecha, $contadorId)
     { 
        $fecha = Carbon::createFromFormat('d/m/Y', $fecha)->startOfDay();

        $datosContador = Contador::leftJoin('d_meter as meter', 'contadores.id', '=', 'meter.contador_id')
        ->leftJoin('contador_meter_resume as cmr', 'meter.contador_id', '=', 'cmr.contador_id')
        ->where('meter.contador_id', $contadorId)
        ->whereDate('meter.datetime', $fecha->format('Y-m-d'))
        ->get()
        ->toArray();

  
        return $datosContador;
     }
*/


  
}
