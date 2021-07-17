<?php


namespace App\Src\UseCases\Domain\Shared\Model;


abstract class Dto implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return $this->toArray(get_object_vars($this));
    }

    private function toArray(array $properties)
    {
        foreach ($properties as $key => $property){
            if(is_object($property)){
                $params[$this->camelToSnake($key)] = $this->toArray(get_object_vars($property));
            }else {
                $params[$this->camelToSnake($key)] = $property;
            }
        }
        return $params;
    }

    private function camelToSnake($input)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }
}
