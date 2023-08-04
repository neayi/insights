<?php

declare(strict_types=1);

namespace App\Src\UseCases\Infra\Sql\Model;


use App\Src\UseCases\Domain\Context\Dto\InteractionDto;
use App\User;
use Illuminate\Database\Eloquent\Model;

class InteractionModel extends Model
{
    protected $table = 'interactions';

    protected $fillable = ['follow', 'applause', 'done', 'page_id', 'value', 'wiki'];

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
