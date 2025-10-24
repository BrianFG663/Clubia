<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Middleware\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    /**
     * Los proxies confiables (ngrok, etc.).
     *
     * @var array|string|null
     */
    protected $proxies = '*'; // confiar en todos los proxies

    /**
     * Cabeceras proxy confiables para detectar host, esquema, IP, etc.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_FOR
                       | Request::HEADER_X_FORWARDED_HOST
                       | Request::HEADER_X_FORWARDED_PROTO
                       | Request::HEADER_X_FORWARDED_PORT;
}

