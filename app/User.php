<?php

namespace App;

use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use App\Src\UseCases\Infra\Sql\Model\CharacteristicsModel;
use App\Src\UseCases\Infra\Sql\Model\ContextModel;
use App\Src\UseCases\Infra\Sql\Model\UserCharacteristicsModel;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements \Illuminate\Contracts\Auth\MustVerifyEmail
{
    use Notifiable, HasRoles, MustVerifyEmail, HasApiTokens;

    protected $fillable = [
        'firstname', 'lastname', 'email', 'password', 'uuid', 'organization_id', "path_picture", "providers"
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'providers' => 'json',
        'wiki_stats' => 'json',
    ];

    public function adminlte_image()
    {
        $urlPicture = $this->path_picture != "" ? asset('storage/'.str_replace('app/public/', '', $this->path_picture)) : null;
        if(!isset($urlPicture) || $urlPicture === ""){
            $urlPicture = url('').'/'.config('adminlte.logo_img');
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

    public function adminlte_profile_url()
    {
        return 'user/edit/profile';
    }

    public function fullname()
    {
        return ucfirst($this->firstname).' '.ucfirst($this->lastname);
    }

    public function sendEmailVerificationNotification()
    {
        Mail::to($this->email)->send(new \App\Mail\Auth\VerifyEmail($this));
    }

    public function characteristics():BelongsToMany
    {
        return $this->belongsToMany(CharacteristicsModel::class,
            'user_characteristics',
            'user_id',
            'characteristic_id'
        )
            ->using(UserCharacteristicsModel::class);
    }

    public function context()
    {
        return $this->hasOne(ContextModel::class, 'id', 'context_id');
    }
}
