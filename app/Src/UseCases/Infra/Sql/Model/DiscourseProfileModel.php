<?php

declare(strict_types=1);

namespace App\Src\UseCases\Infra\Sql\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class DiscourseProfileModel extends Model
{
    protected $table = 'discourse_profiles';

    protected $fillable = [
        'ext_id',
        'locale',
        'username',
        'synced_at',
    ];

    /*public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }*/
}
