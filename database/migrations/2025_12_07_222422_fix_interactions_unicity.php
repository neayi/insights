<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modifie l'index UNIQUE sur les interactions => (user_id, page_id, wiki)
        DB::statement(<<<SQL
            ALTER TABLE `insights`.`interactions`
                DROP INDEX `unique_user_page`,
                ADD UNIQUE `unique_user_page` (`user_id`, `page_id`, `wiki`) USING BTREE;
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
