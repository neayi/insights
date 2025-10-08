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
        Schema::table('users', function (Blueprint $table){
            $table->dropColumn('organization_id');
            $table->dropColumn('join_organization_at');
        });

        DB::statement('DROP TABLE invitations;');
        DB::statement('DROP TABLE organizations;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
