<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void{

        DB::unprepared('
            CREATE TRIGGER crear_parametros_por_institucion
            AFTER INSERT ON institutions
            FOR EACH ROW
            BEGIN
                INSERT INTO parameters (
                    institution_id,
                    umbral_facturas_cuotas_impagas,
                    umbral_facturas_subactividades_impagas,
                    created_at,
                    updated_at
                ) VALUES (
                    NEW.id,
                    NULL,
                    NULL,
                    NOW(),
                    NOW()
                );
            END'
        );
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS crear_parametros_por_institucion');
    }

};
