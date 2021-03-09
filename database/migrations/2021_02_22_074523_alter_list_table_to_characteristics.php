<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterListTableToCharacteristics extends Migration
{
    public function up()
    {
        Schema::rename('list', 'characteristics');
        Schema::table('characteristics', function (Blueprint $table){
            $table->integer('page_id')->nullable()->default(null);
            $table->string('page_label', 200)->nullable()->default(null);
            $table->string('pretty_page_label', 200)->nullable()->default(null);
            $table->json('opt')->nullable()->default(null);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('characteristics', function (Blueprint $table){
            $table->dropColumn('page_id');
            $table->dropColumn('page_label');
            $table->dropColumn('pretty_page_label');
            $table->dropColumn('opt');
            $table->dropTimestamps();
        });
        Schema::rename('characteristics', 'list');
    }
}
