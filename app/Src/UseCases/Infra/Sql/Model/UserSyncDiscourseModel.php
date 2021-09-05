<?php


namespace App\Src\UseCases\Infra\Sql\Model;


use App\User;
use Illuminate\Database\Eloquent\Model;

class UserSyncDiscourseModel extends Model
{
    protected $table = 'users_sync_discourse';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
