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
        $to = '54344615672121'; // fijo
        $templateName = 'mensualidad'; 
        $languageCode = 'es'; 

        $name = $request->input('name');
        $amount = $request->input('amount');
        $link = $request->input('link');

        $url = "https://graph.facebook.com/v17.0/{$phoneNumberId}/messages";

        $payload = [
            "messaging_product" => "whatsapp",
            "to" => $to,
            "type" => "template",
            "template" => [
                "name" => $templateName,
                "language" => ["code" => $languageCode],
                "components" => [
                    [
                        "type" => "header",
                        "parameters" => [
                            ["type" => "text", "text" => $name]
                        ]
                    ],
                    [
                        "type" => "body",
                        "parameters" => [
                            ["type" => "text", "text" => $amount],
                            ["type" => "text", "text" => $link]
                        ]
                    ]
                ]
            ]
        ];

        $resp = Http::withToken($accessToken)->post($url, $payload);

        return $resp->json();
    }

}

