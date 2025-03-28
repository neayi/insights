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
        
        DB::statement(<<<SQL
            UPDATE contexts 
            SET 
                longitude = JSON_EXTRACT(coordinates, "$[0]"), 
                latitude = JSON_EXTRACT(coordinates, "$[1]")
            WHERE
                coordinates IS NOT NULL AND JSON_VALID(coordinates) AND JSON_LENGTH(coordinates) = 2;
        SQL);

        Schema::table('contexts', function (Blueprint $table){
            $table->dropColumn('coordinates');
        });
    }

    public function down()
    {
        Schema::table('contexts', function (Blueprint $table){
            $table->dropColumn('country');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
            $table->json('coordinates')->nullable();
        });
    }
};
