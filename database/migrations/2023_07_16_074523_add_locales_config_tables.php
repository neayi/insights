<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('locales_config', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5);
            $table->string('wiki_url');
            $table->string('wiki_api_url');
            $table->string('forum_url');
            $table->string('forum_api_url');
            $table->string('forum_api_secret');
            $table->string('forum_api_key');
            $table->string('lang_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('locales_config');
    }
};
