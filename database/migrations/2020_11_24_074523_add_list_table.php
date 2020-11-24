<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

class AddListTable extends Migration
{
    public function up()
    {
        Schema::create('list', function (Blueprint $table){
            $table->id();
            $table->uuid('uuid');
            $table->string('code', 250);
            $table->string('type', 50);
            $table->integer('priority')->nullable()->default(null);
        });

        $farmingType[] = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'code' => 'grandes-cultures',
            'type' => 'type_farming',
            'priority' => 10,
        ];

        $farmingType[] = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'code' => 'polyculture-elevage',
            'type' => 'type_farming',
            'priority' => 20,
        ];

        $farmingType[] = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'code' => 'arboriculture',
            'type' => 'type_farming',
            'priority' => 30,
        ];

        $farmingType[] = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'code' => 'cultures-legumieres',
            'type' => 'type_farming',
            'priority' => 40,
        ];

        $farmingType[] = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'code' => 'viticulture',
            'type' => 'type_farming',
            'priority' => 50,
        ];

        $farmingType[] = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'code' => 'cultures-tropicales',
            'type' => 'type_farming',
            'priority' => 60,
        ];

        $farmingType[] = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'code' => 'horticulture',
            'type' => 'type_farming',
            'priority' => 70,
        ];

        foreach($farmingType as $farming) {
            DB::table('list')->insert($farming);
        }
    }

    public function down()
    {
        Schema::drop('list');
    }
}
