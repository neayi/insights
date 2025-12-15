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
        Schema::table('locales_config', function (Blueprint $table){
            $table->string('wiki_restapi_url', 191)->after('wiki_api_url');
        });

        // Populates FR values
        DB::statement(<<<SQL
            UPDATE locales_config
            SET
                wiki_restapi_url = REPLACE(wiki_api_url, 'api.php', 'rest.php')
            WHERE 1;
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locales_config', function (Blueprint $table){
            $table->dropColumn('wiki_restapi_url');
        });
    }
};
