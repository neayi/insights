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
        Schema::table('locales_config', function (Blueprint $table){
            $table->smallInteger('forum_taggroup_farming')->after('forum_api_key')->nullable()->default(null);
            $table->smallInteger('forum_taggroup_cropping')->after('forum_taggroup_farming')->nullable()->default(null);
        });

        // Populates FR values
        DB::statement(<<<SQL
            UPDATE locales_config
            SET
                forum_taggroup_farming = 5,
                forum_taggroup_cropping = 12
            WHERE
                code = 'fr';
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('locales_config', function (Blueprint $table){
            $table->dropColumn('forum_taggroup_farming');
            $table->dropColumn('forum_taggroup_cropping');
        });
    }
};
