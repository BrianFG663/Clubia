<?php

namespace App\Http\Controllers;

use App\Models\SubActivity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function subActividades(Request $request){

        $actividad = $request->input('actividad');
        $subActividades = SubActivity::where('activity_id', $actividad)->get();
        
        if($subActividades->isEmpty()) {
            return response()->json(['mensaje' => false]);
        }else {
            return response()->json(['mensaje' => true, 'subActividades' => $subActividades]);
        }
    }
}
