<?php


namespace App\Src\UseCases\Domain\Shared\Model;


interface HasMemento
{
    public function memento():Memento;
}
