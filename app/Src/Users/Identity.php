<?php


namespace App\Src\Users;


class Identity
{
    private $id;
    private $email;
    private $firstname;
    private $lastname;
    private $picture;

    public function __construct(string $id, string $email, string $firstname, string $lastname, string $picture = null)
    {
        $this->id = $id;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->picture = $picture;
    }

    public function toArray()
    {
        $urlPicture = $this->picture != "" ? asset('storage/'.str_replace('app/public/', '', $this->picture)) : null;
        return [
            'uuid' => $this->id,
            'email' => $this->email,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'path_picture' => $this->picture,
            'url_picture' => $urlPicture,
        ];
    }
}
