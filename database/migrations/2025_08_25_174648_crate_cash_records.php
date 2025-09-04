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
        Schema::create('cash_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->decimal('total', 20, 6);
            $table->date('fecha');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_records');
    }
};
