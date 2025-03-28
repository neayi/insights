<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('contexts', function (Blueprint $table){
            $table->string('country', 2)->nullable()->default(null);
            $table->decimal('latitude', 10, 8)->nullable()->default(null);
            $table->decimal('longitude', 11, 8)->nullable()->default(null);
        });

        // Populates splitted coordinates
        DB::statement(<<<SQL
            UPDATE contexts 
            SET 
                longitude = JSON_EXTRACT(coordinates, "$[0]"), 
                latitude = JSON_EXTRACT(coordinates, "$[1]")
            WHERE
                coordinates IS NOT NULL AND JSON_VALID(coordinates) AND JSON_LENGTH(coordinates) = 2;
        SQL);

        // Populates country
        DB::statement(<<<SQL
            UPDATE contexts c
                INNER JOIN users u ON u.context_id = c.id AND u.wiki = 'fr'
            SET c.country = 'FR'
            WHERE country IS NULL AND department_number IS NOT NULL;
        SQL);

        // Nullify empty country, postal_code and department_number
        DB::statement(<<<SQL
            UPDATE contexts SET country = NULL WHERE country = '';
        SQL);
        DB::statement(<<<SQL
            UPDATE contexts SET postal_code = NULL WHERE postal_code = '';
        SQL);
        DB::statement(<<<SQL
            UPDATE contexts SET department_number = NULL WHERE department_number = '';
        SQL);

        Schema::table('contexts', function (Blueprint $table){
            $table->dropColumn('coordinates');
        });
    }

    public function down()
    {
        Schema::table('contexts', function (Blueprint $table){
            $table->json('coordinates')->nullable();
        });

        DB::statement(<<<SQL
            UPDATE contexts 
            SET coordinates = JSON_ARRAY(longitude, latitude)
            WHERE
                latitude IS NOT NULL AND longitude IS NOT NULL; 
        SQL);

        Schema::table('contexts', function (Blueprint $table){
            $table->dropColumn('country');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });
    }
};
