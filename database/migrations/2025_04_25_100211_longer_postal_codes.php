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
        Schema::table('contexts', function (Blueprint $table){
            $table->string('postal_code', 32)->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contexts', function (Blueprint $table){
            $table->string('postal_code', 10)->nullable()->default(null)->change();
        });
    }
};
