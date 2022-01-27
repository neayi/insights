<?php

namespace App;

use App\Src\UseCases\Domain\Context\Dto\UserDto;
use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use App\Src\UseCases\Infra\Sql\Model\CharacteristicsModel;
use App\Src\UseCases\Infra\Sql\Model\ContextModel;
use App\Src\UseCases\Infra\Sql\Model\UserCharacteristicsModel;
use App\Src\UseCases\Infra\Sql\Model\UserSyncDiscourseModel;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements \Illuminate\Contracts\Auth\MustVerifyEmail
{
    use Notifiable, HasRoles, MustVerifyEmail, HasApiTokens, HasFactory;

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

    public function pictureUrl():?string
    {
        return $this->path_picture != "" ? asset('storage/'.str_replace('app/public/', '', $this->path_picture)) : null;
    }

    public function adminlte_desc()
    {
        $desc = $this->firstname.' '.$this->lastname;
        if($this->organization_id !== null){
            $organization = app(OrganizationRepository::class)->get($this->organization_id);
            $desc .= ' - organisme : '.$organization->name();
        }
        return $desc;
    }

    public function getAvatarUrlAttribute()
    {
        return $this->adminlte_image();
    }

    public function getBioAttribute()
    {
        return $this->context->description;
    }

    public function adminlte_profile_url()
    {
        return 'user/edit/profile';
    }

    public function fullname()
    {
        return $this->firstname.' '.$this->lastname;
    }

    public function sendEmailVerificationNotification()
    {
        $callback = '';
        if(session()->has('wiki_callback')){
            $callback = base64_encode(urldecode(session()->get('wiki_callback')));
        }

        if(session()->has('sso')){
            $sso = session()->get('sso');
            $sig = session()->get('sig');
            $callback = base64_encode(url('discourse/sso?sso='.$sso.'&sig='.$sig));
        }
        Mail::to($this->email)->send(new \App\Mail\Auth\VerifyEmail($this, $callback));
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

    public function addCharacteristics(array $characteristics)
    {
        foreach($characteristics as $characteristicUuid){
            $characteristic = CharacteristicsModel::where('uuid', (string)$characteristicUuid)->first();
            if(isset($characteristic)) {
                $this->characteristics()->save($characteristic);
            }
        }
    }

    public function syncCharacteristics(array $characteristics)
    {
        $characteristicsToSync = [];
        foreach($characteristics as $characteristicUuid){
            $characteristicModel = CharacteristicsModel::where('uuid', (string)$characteristicUuid)->first();
            if(isset($characteristicModel)) {
                $characteristicsToSync[] = $characteristicModel->id;
            }
        }
        $this->characteristics()->sync($characteristicsToSync);
    }

    public function toDto():UserDto
    {
        return new UserDto(
            $this->uuid,
            $this->firstname,
            $this->lastname,
            $this->email,
            $this->roles()->pluck('name')->toArray(),
            $this->pictureUrl(),
            [
                'discourse_id' => $this->discourse_id,
                'discourse_username' => $this->discourse_username
            ],
        );
    }

    public function askDiscourseSync()
    {
        $sync = UserSyncDiscourseModel::query()->where('user_id', $this->id)->first();
        if(!isset($sync)){
            $sync = new UserSyncDiscourseModel();
            $sync->user_id = $this->id;
            $sync->uuid = $this->uuid;
        }
        $sync->sync = false;
        $sync->save();
    }
}
