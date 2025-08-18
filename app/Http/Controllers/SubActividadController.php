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
}

