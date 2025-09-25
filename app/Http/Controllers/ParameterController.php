<?php

namespace App\Http\Controllers;

use App\Models\Parameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ParameterController extends Controller
{
    
    public function buscarParametros(Request $request){
        
        Log::info($request->institucion);
        $parametros = Parameter::where('institution_id', $request->institucion)->first();

        return response()->json(['mensaje' => true, 'parametros' => $parametros]);
    }

    public function cambiarParametroSocial(Request $request){
        Log::info('aaaaaaaaaaaa'.$request->institucion);
        Log::info('parametro'.$request->parametro);

        $parametros = Parameter::where('institution_id', $request->institucion)->first();

        if($parametros){
            if($request->parametro == 'null'){
                $parametros->umbral_facturas_cuotas_impagas =  null;
                $parametros->save();
                Log::info('entra');
            }else{
                $parametros->umbral_facturas_cuotas_impagas =  $request->parametro;
                $parametros->save();
            }

            return response()->json(['mensaje' => true]);   
        }else{
            return response()->json(['mensaje' => false]);
        }
    }
    
    public function cambiarParametroActividad(Request $request){
        Log::info('aaaaaaaaaaaa'.$request->institucion);
        Log::info('parametro'.$request->parametro);
        Log::info('Tipo de $request->parametro: ' . gettype($request->parametro));

        $parametros = Parameter::where('institution_id', $request->institucion)->first();

        if($parametros){
            if($request->parametro == 'null'){
                $parametros->umbral_facturas_subactividades_impagas =  null;
                $parametros->save();
                Log::info('entra');
            }else{
                $parametros->umbral_facturas_subactividades_impagas =  $request->parametro;
                $parametros->save();
            }

            return response()->json(['mensaje' => true]);   
        }else{
            return response()->json(['mensaje' => false]);
        }
    }
}
