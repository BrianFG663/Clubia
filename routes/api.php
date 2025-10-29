<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::post('/webhook-mp', [PaymentController::class, 'webhookMP']);
