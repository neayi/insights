<?php


namespace App\Src\Utils\Hash;


use Illuminate\Support\Facades\Hash;

class HashGenReal implements HashGen
{
    public function hash(String $value): String
    {
        return Hash::make($value);
    }

}
