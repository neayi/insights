<?php

namespace App;

use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'firstname', 'lastname', 'email', 'password', 'uuid', 'organization_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function adminlte_image()
    {
        return 'http://dev.core.tripleperformance.com:8008/vendor/adminlte/dist/img/AdminLTELogo.png';
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
