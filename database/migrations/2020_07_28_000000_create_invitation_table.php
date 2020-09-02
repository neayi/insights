<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationTable extends Migration
{
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->uuid('organization_id');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email');
            $table->text('hash');
            $table->timestamp('send_at');
            $table->timestamp('join_organization_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invitations');
    }
}
