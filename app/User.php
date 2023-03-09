<?php

namespace App;

use App\Src\Context\Application\Dto\UserDto;
use App\Src\Context\Infrastructure\Model\CharacteristicsModel;
use App\Src\Context\Infrastructure\Model\ContextModel;
use App\Src\UseCases\Domain\Ports\OrganizationRepository;
use App\Src\UseCases\Infra\Sql\Model\UserCharacteristicsModel;
use App\Src\UseCases\Infra\Sql\Model\UserSyncDiscourseModel;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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

    public function adminlte_desc()
    {
        $desc = $this->firstname.' '.$this->lastname;
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

    /**
     * Accessors
     * @see https://laravel.com/docs/8.x/eloquent-mutators#defining-an-accessor
     */

    public function getBioAttribute()
    {
        if (empty($this->context) || empty($this->context->description))
            return '';

        return $this->context->description;
    }

    /**
     * Return the title followed by the structure in parenthesis
     */
    public function getTitleAttribute()
    {
        if (empty($this->context) || empty($this->context->sector))
            return '';

        $title = $this->context->sector;
        if (!empty($this->context->structure))
            $title .= ' (' . $this->context->structure . ')';

        return $title;
    }

    /**
     * Get the discourse_username for discourse
     *
     * @param  string  $value
     * @return string
     */
    public function getDiscourseUsernameAttribute($value)
    {
        if (empty($value))
            return trim(substr(Str::of($this->fullname)->slug('.'), 0, 20), '.');

        return $value;
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullnameAttribute()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function fullname()
    {
        return $this->getFullNameAttribute();
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
        return new UserDto($this->uuid, $this->firstname, $this->lastname, $this->discourse_username);
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
