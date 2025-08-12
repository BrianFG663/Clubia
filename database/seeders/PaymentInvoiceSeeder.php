<?php

namespace Database\Seeders;

use App\Models\PaymentInvoice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentInvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentInvoice::factory(20)->create();
    }
}
