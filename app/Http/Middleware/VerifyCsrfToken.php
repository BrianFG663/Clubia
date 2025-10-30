<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Rutas que Laravel NO verificará para CSRF.
     * 
     * @var array
     */
    protected $except = [
        'webhook-mp', // <-- Esta es la línea que estamos agregando
    ];
}
