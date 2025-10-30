<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function subirLogo(Request $request)
    {
        try {
            $request->validate([
                'imagen' => 'required|image|max:2048',
            ]);
        } catch (ValidationException $e) {
            return back()->with('error', 'Formato de archivo no soportado, por favor seleccione una imagen.');
        }

        $archivo = $request->file('imagen');
        $extension = $archivo->getClientOriginalExtension();
        $nombre = 'logo.' . $extension;
        $ruta = 'imagenes/' . $nombre;

        $extensiones = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'bmp'];
        foreach ($extensiones as $ext) {
            $posible = 'imagenes/logo.' . $ext;
            if (Storage::disk('public')->exists($posible)) {
                Storage::disk('public')->delete($posible);
            }
        }

        $archivo->storeAs('imagenes', $nombre, 'public');

        return back()->with('mensaje', 'Logo cambiado correctamente');
    }
}
