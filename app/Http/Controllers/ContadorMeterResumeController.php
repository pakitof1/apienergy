<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContadorMeterResume;
use Carbon\Carbon;

class ContadorMeterResumeController extends Controller
{

    /**
     * Funcion que obteniene las mediciones resumidos de un contador.
     */
    public static function getResumeContador($fecha, $contadorId){

        $fecha = Carbon::createFromFormat('d/m/Y', $fecha)->startOfDay();

        $datosContador = ContadorMeterResume::where('contador_id', $contadorId)
        ->whereDate('datetime', $fecha->format('Y-m-d'))
        ->get()
        ->toArray();

  
        return $datosContador;

    }



}
