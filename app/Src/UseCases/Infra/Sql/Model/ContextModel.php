<?php


namespace App\Src\UseCases\Infra\Sql\Model;


use Illuminate\Database\Eloquent\Model;

class ContextModel extends Model
{
    protected $table = 'contexts';

    protected $fillable = ['description', 'postal_code', 'structure', 'sector'];
}
