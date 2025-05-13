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
        // Populates country
        DB::statement(<<<SQL
            UPDATE user_characteristics uc
                INNER JOIN users u ON uc.user_id = u.id
            SET uc.created_at = u.updated_at, uc.updated_at = u.updated_at
            WHERE uc.created_at IS NULL OR uc.updated_at IS NULL;
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
