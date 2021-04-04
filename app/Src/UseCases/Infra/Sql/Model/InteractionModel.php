<?php


namespace App\Src\UseCases\Infra\Sql\Model;


use Illuminate\Database\Eloquent\Model;

class InteractionModel extends Model
{
    protected $table = 'interactions';

    protected $fillable = ['follow', 'applause', 'done', 'page_id', 'value'];

    protected $casts = ['value' => 'array'];
}
