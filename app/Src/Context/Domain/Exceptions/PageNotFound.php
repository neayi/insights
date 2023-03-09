<?php


namespace App\Src\Context\Domain\Exceptions;


use App\Src\Shared\Exceptions\NotFound;

class PageNotFound extends NotFound
{
    CONST error = 'page_not_found';
}
