<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    // Verificación del token
   public function verify(Request $request)
    {
        $verify_token = env('WHATSAPP_VERIFY_TOKEN');

        $mode = $request->input('hub_mode');
        $token = $request->input('hub_verify_token');
        $challenge = $request->input('hub_challenge');

        if ($mode === 'subscribe' && $token === $verify_token) {
            return response($challenge, 200);
        }

        return response('Forbidden', 403);
    }


    // Verificar si se envió el mensaje
    public function handle(Request $request)
    {
        Log::info('Webhook recibido:', $request->all());
        return response('EVENT_RECEIVED', 200);
    }
}
