<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('payment_links', function (Blueprint $table) {
        $table->id();
        $table->foreignId('partner_id')->constrained()->onDelete('cascade');
        $table->string('link_mercado_pago');
        $table->string('preference_id')->nullable();
        $table->decimal('monto_total', 10, 2);
        $table->enum('estado', ['pendiente', 'pagado'])->default('pendiente');
        $table->string('periodo'); 
        $table->timestamp('fecha_generacion')->useCurrent();
        $table->timestamp('fecha_pago')->nullable();
        $table->timestamps();
    });
}




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_links');
    }
};
