<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use App\Traits\Uuid;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens, Uuid, SoftDeletes, HasRoles;

    protected $primaryKey = 'id';

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone_number', 'merchant_id', 'is_active', 'password', 'last_login'
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
        'last_login' => 'datetime',
    ];

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {   
        return "{$this->first_name} {$this->last_name}";
    }

    public function merchant()
    {
        return $this->hasOne('App\Models\Merchant', 'id', 'merchant_id');
    }
}
