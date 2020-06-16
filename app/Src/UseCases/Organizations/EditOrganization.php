<?php


namespace App\Src\UseCases\Organizations;


use App\Exceptions\Domain\OrganizationNotFound;
use App\Src\UseCases\Domain\Address;
use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use Illuminate\Support\Facades\Validator;

class EditOrganization
{
    private $organizationRepository;

    public function __construct(OrganizationRepository $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
    }

    public function edit(string $organizationId, string $name, array $picture, array $address)
    {
        $organization = $this->organizationRepository->get($organizationId);
        if(!isset($organization)){
            throw new OrganizationNotFound();
        }
        $this->validateData($name, $picture, $address);

        list($pathPicture, $ext) = $this->handlePicture($picture);
        $pictureToSave = ['path' => $pathPicture, 'ext' => $ext];
        $address = new Address($address['city'], $address['address1'], $address['address2'], $address['pc']);
        $organization->update($name, $pictureToSave, $address);
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

    private function handlePicture(array $picture): array
    {
        $pathPicture = isset($picture['path_picture']) ? $picture['path_picture'] : '';
        $ext = isset($picture['original_name']) && strpos($picture['original_name'], '.') ? explode('.', $picture['original_name'])[1] : 'jpg';
        return [$pathPicture, $ext];
    }
}
