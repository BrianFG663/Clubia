<?php

namespace App\Http\Controllers;

use App\Models\SubActivity;
use Illuminate\Http\Request;

class SubActividadController extends Controller
{
    
    public function traerSocios(Request $request)
    {        
        $subactividad = SubActivity::with('partners')->find($request->id);
        if (!$subactividad || $subactividad->partners->isEmpty()) 
            {
            return response()->json(['mensaje' => false]);
            } 
            
        return response()->json([
            'mensaje' => true,
            'subactividad' => $subactividad,
            'socios' => $subactividad->partners
        ]);
    }

    public function bajaSocio(Request $request)
    {
        $socioId = $request->input('id');
        $subactividadId = $request->input('subactividad_id');
        $subactividad = SubActivity::findOrFail($subactividadId);
        $subactividad->partners()->detach($socioId);

        return response()->json(['mensaje' => true]);
    }

    public function buscarSubactvidad(Request $request)
        {
            $query = trim($request->input('filtro'));

            $subactividades = SubActivity::withCount('partners')
                ->where('nombre', 'like', "%{$query}%")
                ->orWhereHas('activity', function ($q) use ($query) {
                    $q->where('nombre', 'like', "%{$query}%");
                })
                ->limit(20)
                ->get();

            if ($subactividades->isEmpty()) {
                return response()->json([
                    'mensaje' => false,
                    'message' => 'No se encontraron subactividades.'
                ]);
            }

            $resultado = $subactividades->map(function ($sub) {
                return [
                    'id'             => $sub->id,
                    'nombre'         => $sub->nombre,
                    'actividad'      => $sub->activity->nombre,
                    'monto'          => $sub->monto,
                    'cantidad_socios' => $sub->partners_count,
                ];
            });

            return response()->json([
                'mensaje'         => true,
                'subactividades'  => $resultado,
            ]);
        }

}

