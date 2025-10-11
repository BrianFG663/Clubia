<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartnerLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('partner.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('partner')->attempt($request->only('email', 'password'))) {
            return redirect()->intended('/panel-socio');
        }

        return back()->withErrors([
            'email' => 'Credenciales inválidas.',
        ]);
    }
}
