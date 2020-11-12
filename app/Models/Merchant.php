<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Merchant extends Model
{
    use HasFactory, Uuid;
    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'name', 'merchant_code', 'phone_number', 'email', 'is_active', 'merchant_id'
    ];

    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

    public function merchants()
    {
        return $this->hasMany('App\Models\Merchant');
    }

    public function main_merchant()
    {
        return $this->hasOne('App\Models\Merchant', 'id', 'merchant_id');
    }
}
