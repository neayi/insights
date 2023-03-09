<?php

use Illuminate\Database\Migrations\Migration;

class MigrateDepartmentNumber extends Migration
{
    public function up()
    {
        $contexts = \App\Src\Context\Infrastructure\Model\ContextModel::all();
        foreach($contexts as $context){
            try {
                if (!isset($context->deparment_number) || $context->deparment_number == "") {
                    $geoData = app(\App\Src\Shared\Gateway\GetDepartmentFromPostalCode::class)->execute($context->postal_code);
                }
                $context->coordinates = $geoData['coordinates'];
                $context->department_number = $geoData['department_number'];
                $context->save();
            }catch (\Throwable $e){
                \Illuminate\Support\Facades\Log::emergency($e->getTraceAsString());
                report($e);
            }
        }
    }

    public function down()
    {
        // nothing to do
    }
}
