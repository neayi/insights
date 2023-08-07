<?php


namespace App\Src\UseCases\Domain\Shared\Model;


abstract class Dto implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return $this->serialize(get_object_vars($this));
    }

    private function serialize(array $properties)
    {
        $params = [];
        foreach ($properties as $key => $property){
            if(is_object($property)){
                $params[$this->camelToSnake($key)] = $this->serialize(get_object_vars($property));
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

    public function toArray():array
    {
        return $this->serialize(get_object_vars($this));
    }
}
