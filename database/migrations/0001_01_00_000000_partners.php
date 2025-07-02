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
         Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('dni')->unique();
            $table->string('email')->unique();
            $table->foreignId('state_id')->constrained()->onDelete('cascade');
            $table->date('fecha_nacimiento');
            $table->string('direccion');
            $table->string('ciudad');
            $table->string('telefono')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('menor');
            $table->boolean('jefe_grupo');
            $table->integer('responsable_id');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
