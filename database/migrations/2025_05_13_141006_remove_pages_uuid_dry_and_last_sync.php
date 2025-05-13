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
        Schema::table('pages', function (Blueprint $table){
            $table->dropColumn('dry');
            $table->dropColumn('last_sync');
            $table->dropColumn('uuid');
        });

        // Remove NULL pages
        DB::statement(<<<SQL
            DELETE FROM pages WHERE wiki IS NULL;
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
