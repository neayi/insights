<?php

declare(strict_types=1);

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
            $table->string('country_code')->nullable();
        });

        Schema::table('characteristics', function (Blueprint $table) {
            $table->string('country_code')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('country_code')->nullable();
        });

        PageModel::query()->update(['country_code' => 'FR']);
        User::query()->update(['country_code' => 'FR']);
        CharacteristicsModel::query()->update(['country_code' => 'FR']);
    }

    public function down()
    {
        Schema::table('pages', function (Blueprint $table){
            $table->dropColumn('country_code');
        });

        Schema::table('characteristics', function (Blueprint $table){
            $table->dropColumn('country_code');
        });

        Schema::table('users', function (Blueprint $table){
            $table->dropColumn('country_code');
        });

        PageModel::query()->update(['country_code' => '']);
        User::query()->update(['country_code' => '']);
        CharacteristicsModel::query()->update(['country_code' => '']);
    }
};
