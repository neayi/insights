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

    public function name():string
    {
        return $this->name;
    }

    public function create(string $ext = 'jpg')
    {
        if($this->pathPicture !== "") {
            $picture = new Picture($this->pathPicture);
            $picture->resize('app/public/organizations/' . $this->id . '.' . $ext);
            $this->pathPicture = 'app/public/organizations/' . $this->id . '.' . $ext;
        }

        app(OrganizationRepository::class)->add($this);
    }

    public function update(string $name, array $picture, Address $address)
    {
        $this->name = $name;
        $this->address = $address;
        if($picture['path'] !== "") {
            $logo = new Picture($picture['path']);
            $ext = $picture['ext'];
            $logo->resize('app/public/organizations/' . $this->id . '.' . $ext);
            $this->pathPicture = 'app/public/organizations/' . $this->id . '.' . $ext;
        }
        app(OrganizationRepository::class)->update($this);
    }

    public function toArray()
    {
        $urlPicture = $this->pathPicture != "" ? asset('storage/'.str_replace('app/public/', '', $this->pathPicture)) : null;
        return array_merge([
            'uuid' => $this->id,
            'name' => $this->name,
            'path_picture' => $this->pathPicture,
            'url_picture' => $urlPicture,
        ], $this->address->toArray());
    }
}
