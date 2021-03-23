<?php

namespace App\Src\UseCases\Domain\Organizations;

use App\Src\UseCases\Domain\Organizations\Model\Address;
use App\Src\UseCases\Domain\Organizations\Model\Organization;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class CreateOrganization
{
    public function create(string $name, array $picture, array $address)
    {
        $data = $this->validateData($name, $address, $picture);

        list($pathPicture, $ext) = $this->handlePicture($picture);

        $id = Uuid::uuid4()->toString();
        $address = new Address($data['city'], $data['address1'], $data['address2'], $data['pc']);
        $organization = new Organization($id, $name, $pathPicture, $address);
        $organization->create($ext);

        return $id;
    }

    private function validateData(string $name, array $address, array $picture): array
    {
        $rules = [
            'name' => 'string|required|min:2|max:255',
            'city' => 'string|required|min:2|max:100',
            'pc' => 'string|required|min:2|max:6',
            'address1' => 'string|required|min:2|max:255',
            'address2' => 'min:2|max:255|nullable',
            'mine_type' => 'nullable|in:image/jpeg,image/png,image/jpg'
        ];
        $data = array_merge(['name' => $name], $address, $picture);
        $validator = Validator::make($data, $rules, [], ['name' => 'Nom']);
        $validator->validate();
        return $data;
    }

    /**
     * @param array $picture
     * @return array
     */
    private function handlePicture(array $picture): array
    {
        $pathPicture = isset($picture['path_picture']) ? $picture['path_picture'] : '';
        $ext = isset($picture['original_name']) && strpos($picture['original_name'], '.') ? explode('.', $picture['original_name'])[1] : 'jpg';
        return [$pathPicture, $ext];
    }
}
