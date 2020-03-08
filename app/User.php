<?php

namespace App;

use App\Casts\JsonCast;
use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'settings'          => 'array'
    ];

    public function users() {
        return $this->hasMany('App\Task', 'user_id');
    }

    public function projects() {
        return $this->hasMany('App\Project', 'user_id');
    }

    public function project() {
        return $this->hasOne('App\Project', 'user_id');
    }

    public function changeSettings(array $settings, bool $save = true)
    {
        if (!is_array($this->settings)) {
            $this->settings = [];
        }

        $this->settings = array_merge($this->settings, $settings);

        if ($save) {
            $this->save();
        }
    }

    public function removeSettings(array $settings, bool $save = true) {
        $settingsList = $this->settings;
        if (count($settings) > 0) {
            foreach ($settings as $key => $value) {
                if (array_key_exists($key, $this->settings)) {
                    unset($settingsList[$key]);
                }
            }
            $this->settings = $settingsList;
            if ($save) {
                $this->save();
            }
        }
    }

    public function remove() {
        DB::delete("DELETE FROM `oauth_access_tokens` WHERE `oauth_access_tokens`.`user_id` = ?", [$this->id]);
        $this->delete();
    }
}
