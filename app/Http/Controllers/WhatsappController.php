<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsAppController extends Controller
{
    public function sendTemplate(Request $request)
    {
        $phoneNumberId = config('services.whatsapp.phone_number_id'); 
        $accessToken = config('services.whatsapp.access_token');     
        $to = '54344615672121'; // formato E.164 
        $templateName = 'mensualidad'; 
        $languageCode = 'es_ES'; 

        $url = "https://graph.facebook.com/v17.0/{$phoneNumberId}/messages";

        $payload = [
            "messaging_product" => "whatsapp",
            "to" => $to,
            "type" => "template",
            "template" => [
                "name" => $templateName,
                "language" => ["code" => $languageCode],
                // si tu plantilla tiene variables:
                "components" => [
                [
                    "type" => "header",
                    "parameters" => [
                        [
                            "type" => "text",
                            "text" => "Brian" // reemplazá con el nombre de la persona ({{1}})
                        ]
                    ]
                ],
                [
                    "type" => "body",
                    "parameters" => [
                        [
                            "type" => "text",
                            "text" => "5000" // {{1}} -> monto a pagar
                        ],
                        [
                            "type" => "text",
                            "text" => "https://tulinkdepago.com/abc123" // {{2}} -> enlace de pago
                        ]
                    ]
                ]
            ]
        ]
        ];

        $resp = Http::withToken($accessToken)
                    ->post($url, $payload);

        return $resp->json();
    }
}

