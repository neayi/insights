<?php


namespace App\Src\UseCases\Infra\Sql\Model;


use Illuminate\Database\Eloquent\Model;

class InteractionModel extends Model
{
    protected $table = 'interactions';

    protected $fillable = ['follow', 'applause', 'done', 'page_id', 'value'];

    protected $casts = ['value' => 'array', 'start_done_at' => 'datetime'];

    public function page()
    {
        return $this->belongsTo(PageModel::class, 'page_id', 'page_id');
    }

}
