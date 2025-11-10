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
        // Merge les interactions dupliquées (user_id + page_id)
        DB::statement(<<<SQL
            UPDATE interactions i1
                INNER JOIN interactions i2 ON i1.id < i2.id AND i1.user_id = i2.user_id AND i1.page_id = i2.page_id
            SET i1.applause = GREATEST(i1.applause, i2.applause),
                i1.follow = GREATEST(i1.follow, i2.follow),
                i1.done = GREATEST(i1.done, i2.done),
                i1.start_done_at = IFNULL(i1.start_done_at, i2.start_done_at),
                i1.value = GREATEST(i1.value, i2.value)
            WHERE i1.user_id IS NOT NULL;
        SQL);
        // Supprime les interactions en doublon
        DB::statement(<<<SQL
            DELETE i2
            FROM interactions i1
                INNER JOIN interactions i2 ON i1.id < i2.id AND i1.user_id = i2.user_id AND i1.page_id = i2.page_id
            WHERE i1.user_id IS NOT NULL;
        SQL);
        // Pose un index UNIQUE sur les interactions (user_id + page_id)
        DB::statement(<<<SQL
            CREATE UNIQUE INDEX unique_user_page ON interactions (user_id, page_id);
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement(<<<SQL
            ALTER TABLE `interactions` DROP INDEX `unique_user_page`;
        SQL);
    }
};
