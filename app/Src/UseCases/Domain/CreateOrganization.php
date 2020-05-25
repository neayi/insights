<?php

namespace App\Src\UseCases\Domain;

use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class CreateOrganization
{
    public function create(string $name, string $pathPicture, array $address)
    {
        $data = $this->validateData($name, $address);

        $id = Uuid::uuid4()->toString();
        $address = new Address($data['city'], $data['address1'], $data['address2'], $data['pc']);
        $organization = new Organization($id, $name, $pathPicture, $address);
        $organization->create();

        return $id;
    }

    private function validateData(string $name, array $address): array
    {
        $rules = [
            'name' => 'string|required|min:2|max:255',
            'city' => 'required|min:2|max:100',
            'pc' => 'required|min:2|max:6',
            'address1' => 'required|min:2|max:255',
            'address2' => 'min:2|max:255',
        ];
        $data = array_merge(['name' => $name], $address);
        $validator = Validator::make($data, $rules, [], ['name' => 'Nom']);
        $validator->validate();
        return $data;
    }
}
