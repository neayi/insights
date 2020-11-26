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
            $table->boolean('main')->default(false);
            $table->string('icon', 100)->nullable()->default(null);
        });

        $farmingType[] = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'code' => 'grandes-cultures',
            'type' => 'type_farming',
            'priority' => 10,
            'main' => true,
            'icon' => 'Grandes-cultures.svg',
        ];

        $farmingType[] = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'code' => 'polyculture-elevage',
            'type' => 'type_farming',
            'priority' => 20,
            'main' => true,
            'icon' => 'Polyculture-elevage.svg',
        ];


        $farmingType[] = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'code' => 'viticulture',
            'type' => 'type_farming',
            'priority' => 30,
            'main' => true,
            'icon' => 'Viticulture.svg',
        ];

        $farmingType[] = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'code' => 'arboriculture',
            'type' => 'type_farming',
            'priority' => 40,
            'main' => true,
            'icon' => 'Arboriculture.svg',
        ];

        $farmingType[] = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'code' => 'maraichage',
            'type' => 'type_farming',
            'priority' => 50,
            'main' => true,
            'icon' => 'Maraichage.svg',
        ];

        $farmingType[] = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'code' => 'elevage-bovin',
            'type' => 'type_farming',
            'priority' => 60,
            'main' => true,
            'icon' => 'Elevage-bovin.svg',
        ];



        $farmingType[] = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'code' => 'elevage-caprin',
            'type' => 'type_farming',
            'priority' => 50,
            'main' => false,
            'icon' => 'Elevage-caprin.svg',
        ];

        $farmingType[] = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'code' => 'elevage-ovin',
            'type' => 'type_farming',
            'priority' => 60,
            'main' => false,
            'icon' => 'Elevage-ovin.svg',
        ];

        $farmingType[] = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'code' => 'elevage-porcin',
            'type' => 'type_farming',
            'priority' => 70,
            'main' => false,
            'icon' => 'Elevage-porcin.svg',
        ];

        $farmingType[] = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'code' => 'aviculture',
            'type' => 'type_farming',
            'priority' => 80,
            'main' => false,
            'icon' => 'Aviculture.svg',
        ];

        $farmingType[] = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'code' => 'elevage-equin',
            'type' => 'type_farming',
            'priority' => 90,
            'main' => false,
            'icon' => 'Elevage-equin.svg',
        ];

        $farmingType[] = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'code' => 'apiculture',
            'type' => 'type_farming',
            'priority' => 100,
            'main' => false,
            'icon' => 'Apiculture.svg',
        ];

        $farmingType[] = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'code' => 'horticulture',
            'type' => 'type_farming',
            'priority' => 70,
            'main' => false,
            'icon' => 'Horticulture-PPAM.svg',
        ];

        $farmingType[] = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'code' => 'cultures-tropicales',
            'type' => 'type_farming',
            'priority' => 110,
            'main' => false,
            'icon' => 'Cultures-tropicales.svg',
        ];

        $farmingType[] = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'code' => 'sylviculture',
            'type' => 'type_farming',
            'priority' => 120,
            'main' => false,
            'icon' => 'Sylviculture.svg',
        ];

        $farmingType[] = [
            'uuid' => \Ramsey\Uuid\Uuid::uuid4(),
            'code' => 'autre',
            'type' => 'type_farming',
            'priority' => 130,
            'main' => false,
            'icon' => 'Other.svg',
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
