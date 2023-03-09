<?php


namespace App\Src\Context\Infrastructure\Model;


use Illuminate\Database\Eloquent\Model;

class PageModel extends Model
{
    protected $table = 'pages';

    protected $fillable = ['dry'];
}
