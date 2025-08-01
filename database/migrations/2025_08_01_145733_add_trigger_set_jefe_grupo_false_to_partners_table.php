<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER set_jefe_grupo_false_before
            BEFORE UPDATE ON partners
            FOR EACH ROW
            BEGIN
                IF OLD.responsable_id IS NULL AND NEW.responsable_id IS NOT NULL THEN
                    SET NEW.jefe_grupo = 0;
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS set_jefe_grupo_false_before');
    }
};
