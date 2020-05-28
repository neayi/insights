<?php


namespace App\Src\UseCases\Domain;


use App\Src\UseCases\Domain\Ports\OrganizationRepository;

class Organization
{
    private $id;
    private $name;
    private $pathPicture;
    private $address;

    public function __construct(string $id, string $name, string $pathPicture, Address $address)
    {
        $this->id = $id;
        $this->name = $name;
        $this->pathPicture = $pathPicture;
        $this->address = $address;
    }

    public function id():string
    {
        return $this->id;
    }

    public function create(string $ext = 'jpg')
    {
        app(OrganizationRepository::class)->add($this);

        if($this->pathPicture !== "") {
            $picture = new Picture($this->pathPicture);
            $picture->resize('app/public/organizations/' . $this->id . '.' . $ext);
        }
    }

    public function toArray()
    {
        return array_merge([
            'uuid' => $this->id,
            'name' => $this->name,
            'path_picture' => $this->pathPicture,
        ], $this->address->toArray());
    }
}
