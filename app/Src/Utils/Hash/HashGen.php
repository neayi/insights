<?php


namespace App\Src\Utils\Hash;


interface HashGen
{
    public function hash(String $value):String;
}
