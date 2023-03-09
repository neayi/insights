<?php


namespace App\Src\Context\Infrastructure\Model;


use App\Src\Context\Application\Dto\InteractionDto;
use App\User;
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function toDto():InteractionDto
    {
        return new InteractionDto($this->page_id, $this->follow, $this->done, $this->applause, $this->start_done_at);
    }
}
