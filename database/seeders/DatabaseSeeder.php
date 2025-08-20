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
        InstitutionSeeder::class, 
        UserSeeder::class, 
        PartnerSeeder::class,  
        PaymentTypeSeeder::class,    
        CategorySeeder::class,
        ConditionSeeder::class,
        SupplierSeeder::class,
        MemberTypeSeeder::class,
        MemberTypePartnerSeeder::class,
        ActivitySeeder::class,
        SubActivitySeeder::class,
        PartnerSubActivitySeeder::class,
        SaleSeeder::class,
        ProductSeeder::class,
        SaleDetailSeeder::class,
        PaymentSeeder::class,
        OrderSeeder::class,
        OrderDetailSeeder::class,
        InvoiceSeeder::class,  
        PaymentInvoiceSeeder::class, 
        
    ]);
    }
}
