<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            DB::unprepared('
                SET @dbname = DATABASE();
                SET @tablename = "admins";
                SET @columnname = "profile_image";
                SET @preparedStatement = (SELECT IF(
                    (
                        SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
                        WHERE TABLE_SCHEMA = @dbname
                        AND TABLE_NAME = @tablename
                        AND COLUMN_NAME = @columnname
                    ) > 0,
                    "SELECT 1",
                    CONCAT("ALTER TABLE ", @tablename, " ADD ", @columnname, " VARCHAR(255) NULL")
                ));
                PREPARE alterIfNotExists FROM @preparedStatement;
                EXECUTE alterIfNotExists;
                DEALLOCATE PREPARE alterIfNotExists;
            ');
        } catch (\Exception $e) {
            // Column might already exist, continue
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::unprepared('
                SET @dbname = DATABASE();
                SET @tablename = "admins";
                SET @columnname = "profile_image";
                SET @preparedStatement = (SELECT IF(
                    (
                        SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
                        WHERE TABLE_SCHEMA = @dbname
                        AND TABLE_NAME = @tablename
                        AND COLUMN_NAME = @columnname
                    ) > 0,
                    CONCAT("ALTER TABLE ", @tablename, " DROP COLUMN ", @columnname),
                    "SELECT 1"
                ));
                PREPARE alterIfExists FROM @preparedStatement;
                EXECUTE alterIfExists;
                DEALLOCATE PREPARE alterIfExists;
            ');
        } catch (\Exception $e) {
            // Column might not exist, continue
        }
    }
};
