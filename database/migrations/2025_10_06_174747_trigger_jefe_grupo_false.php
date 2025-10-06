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
            DELIMITER $$

            CREATE TRIGGER trg_actualizar_jefe_grupo
            AFTER UPDATE ON partners
            FOR EACH ROW
            BEGIN
                DECLARE done INT DEFAULT 0;
                DECLARE jefe_dni VARCHAR(50);

                DECLARE cur CURSOR FOR
                    SELECT dni FROM partners WHERE jefe_grupo = 1;

                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

                OPEN cur;
                leer_jefes: LOOP
                    FETCH cur INTO jefe_dni;
                    IF done = 1 THEN
                        LEAVE leer_jefes;
                    END IF;

                    IF NOT EXISTS (SELECT 1 FROM partners WHERE responsable_id = jefe_dni) THEN
                        UPDATE partners
                        SET jefe_grupo = 0
                        WHERE dni = jefe_dni;
                    END IF;
                END LOOP;

                CLOSE cur;
            END$$

            DELIMITER ;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_actualizar_jefe_grupo;');
    }
};
