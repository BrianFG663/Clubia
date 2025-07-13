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
    public function up(): void
    {
        DB::unprepared("
            CREATE TRIGGER validar_edad_partner
                BEFORE INSERT ON partners
                FOR EACH ROW
                    BEGIN
                        IF TIMESTAMPDIFF(YEAR, NEW.fecha_nacimiento, CURDATE()) < 18 THEN
                            SET NEW.menor = 1;
                        ELSE
                            SET NEW.menor = 0;
                        END IF;
                    END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
