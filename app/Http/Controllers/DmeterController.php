<?php

namespace App\Http\Controllers;
use App\Models\Dmeter;
use App\Models\Contador;
use App\Models\ContadorMeterResume;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DmeterController extends Controller
{

    /**
     * 1. A partir de una fecha, compruebo si existe registros en la base datos de la fecha indicada para los contadores.
     * 2. Si existe recogo los datos con getMeterContador y lo almaceno en un array
     * 2.1 De no existir datos para un contador, llamo a la funcion calcularDatos para que genere los datos de tal fecha.
     */
    public function apiDate(Request $request)
    {

        if(!$request->has('fecha')){
            return false;
        }

        $fecha = $request->input('fecha');

        // Obtener los contadores existentes desde la base de datos
         $contadores = Contador::pluck('id')->toArray();
         $datos = array();
         $resumenDatos = array();

         foreach ($contadores as $contadorId) {
            $datosContador = $this->getMeterContador($fecha,$contadorId);

            if (count($datosContador) == 0) {
                //Si no existe los datos, generarlos
                if($this->calcularDatos($fecha, $contadorId))
                    $datosContador =  $this->getMeterContador($fecha,$contadorId);
            }

            foreach ($datosContador as $dato) {
                $datos[] = [
                    'datetime' => $dato['datetime'],
                    'power' => floatval($dato['power']),
                    'energy' => floatval($dato['energy']),
                    'contador_id' => $dato['contador_id'],
                ];
            }

            $resumenDatos[] = ContadorMeterResumeController::getResumeContador($fecha,$contadorId);

        }

        return response()->json(['data' => $datos,'resume' => $resumenDatos], 200);
    }


    
     /**
      * Función que devuelve los datos de medición de un contador almacenado en la base datos
      */
      protected function getMeterContador($fecha, $contadorId)
     { 
        $fecha = Carbon::createFromFormat('d/m/Y', $fecha)->startOfDay();

        $datosContador = Dmeter::where('contador_id', $contadorId)
        ->whereDate('datetime', $fecha->format('Y-m-d'))
        ->get()
        ->toArray();

        return $datosContador;
     }

 


     /**
      * Función que realiza los calculos de las mediciones de una fecha determinada para un contador
      * y lo almacena en la base de datos.
      */
      protected function calcularDatos($fecha, $contadorId)
      {

        $fechaInicio = Carbon::createFromFormat('d/m/Y', $fecha)->startOfDay();
        $fechaFin = Carbon::createFromFormat('d/m/Y', $fecha)->endOfDay();

        $energyAnterior = 0; //Energia del minuto anterior
        $powerAnterior = 0; //Potencia del minuto anterior
        $sumaPotencia = 0;

        for ($time = $fechaInicio; $time <= $fechaFin; $time->addMinute()) {
            //Los primeros 15 minutos de cada hora no se insertaran datos
            if ($time->minute < 15 || $time->minute >= 60) {

                //Aunque es necesario obtener los datos de la energia de los próximos 5 minutos
                //manteniendo el power del último dato existente
                if($time->minute < 5 && $time->hour != 0){
                    $energy = $energyAnterior + ($powerAnterior / 60);

                    $datos[] = [
                        'datetime' => $time->toDateTimeString(),
                        'power' => $powerAnterior,
                        'energy' => $energy,
                        'created_at' => Carbon::now(),
                        'contador_id' => $contadorId,
                    ];

                    $energyAnterior = $energy;
                }

                continue;
            }
        
            $power = mt_rand(0, 1000) / 10;
            $energy = $energyAnterior + ($power / 60);

            $datos[] = [
                'datetime' => $time->toDateTimeString(),
                'power' => $power,
                'energy' => $energy,
                'created_at' => Carbon::now(),
                'contador_id' => $contadorId,
            ];

            //Para luego calcular los datos promedio
            $sumaPotencia += floatval($power);
         
            $energyAnterior = $energy;
            $powerAnterior = $power;

        }
        
        if(Dmeter::insert($datos)){//una vez insertados los calculos insertamos los datos resumidos
 
            $datosResume = array();
            $datosResume['datetime'] =  Carbon::createFromFormat('d/m/Y', $fecha)->startOfDay();
            $datosResume['contador_id'] = $contadorId;
            $datosResume['avgPower'] =  $sumaPotencia / count($datos);
            $datosResume['total_energy'] = $energy;
            $datosResume['created_at'] = Carbon::now();

            if(ContadorMeterResume::insert($datosResume))
                return true;
            else
                return false; 
                
        } else{
            return false; 
        }

      }
      
}
