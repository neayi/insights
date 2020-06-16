<?php

namespace App;

use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    protected $fillable = [
        'firstname', 'lastname', 'email', 'password', 'uuid', 'organization_id', "path_picture"
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function adminlte_image()
    {
        $urlPicture = $this->path_picture != "" ? asset('storage/'.str_replace('app/public/', '', $this->path_picture)) : null;
        if($urlPicture === ""){
            $urlPicture = 'http://dev.core.tripleperformance.com:8008/vendor/adminlte/dist/img/AdminLTELogo.png';
        }
        return $urlPicture;
    }

    public function adminlte_desc()
    {
        $desc = ucfirst($this->firstname).' '.ucfirst($this->lastname);
        if($this->organization_id !== null){
            $organization = app(OrganizationRepository::class)->get($this->organization_id);
            $desc .= ' - organisme : '.$organization->name();
        }
        return $desc;
    }
}
