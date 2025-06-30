<?php

namespace Database\Seeders;

use App\Models\Payment_type;
use App\Models\PaymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $tipos = ['Efectivo','Tarjeta de crédito','Tarjeta de débito','Transferencia bancaria','Pago online',];

        foreach ($tipos as $nombre) {
            PaymentType::firstOrCreate(['nombre' => $nombre]);
        }
    }
}

