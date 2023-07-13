<?php

declare(strict_types=1);

use App\Src\UseCases\Infra\Sql\Model\InteractionModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Src\UseCases\Infra\Sql\Model\PageModel;
use App\Src\UseCases\Infra\Sql\Model\CharacteristicsModel;
use App\User;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('wiki')->nullable();
            $table->uuid()->nullable();
            $table->dropUnique('pages_page_id_unique');
            $table->unique(['page_id', 'wiki']);
        });

        Schema::table('characteristics', function (Blueprint $table) {
            $table->string('wiki')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('wiki')->nullable();
        });

        Schema::table('interactions', function (Blueprint $table) {
            $table->string('wiki')->nullable();
        });

        PageModel::query()->update(['wiki' => 'fr']);
        User::query()->update(['wiki' => 'fr']);
        CharacteristicsModel::query()->update(['wiki' => 'fr']);
        InteractionModel::query()->update(['wiki' => 'fr']);
    }

    public function down()
    {
        Schema::table('pages', function (Blueprint $table){
            $table->dropColumn('wiki');
            $table->dropColumn('uuid');
        });

        Schema::table('characteristics', function (Blueprint $table){
            $table->dropColumn('wiki');
        });

        Schema::table('users', function (Blueprint $table){
            $table->dropColumn('wiki');
        });

        Schema::table('interactions', function (Blueprint $table){
            $table->dropColumn('wiki');
        });
    }
};
