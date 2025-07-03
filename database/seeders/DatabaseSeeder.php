<?php

namespace Database\Seeders;

use App\Models\MemberType;
use App\Models\Payment_type;
use App\Models\SaleDetail;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
        StateSeeder::class,  
        ShieldSeeder::class,       
        RolesSeeder::class,    
        UserSeeder::class,   
        PaymentTypeSeeder::class,    
        CategorySeeder::class,
        InstitutionSeeder::class,
        SupplierSeeder::class,
       /* MemberTypeSeeder::class, */
       /* MemberTypeUserSeeder::class,*/
        ActivitySeeder::class,
        SubActivitySeeder::class,
       /* SubActivityUserSeeder::class, */
        SaleSeeder::class,
        ProductSeeder::class,
        SaleDetailSeeder::class,
       /* InvoiceSeeder::class, */
       /* PaymentSeeder::class, */
       /* PaymentInvoiceSeeder::class, */
        OrderSeeder::class,
        OrderDetailSeeder::class,
    ]);
    }
}
