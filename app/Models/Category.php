<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    protected $primaryKey = 'id';

    public $incrementing = false;

    // protected $table = 'categories';

    protected $fillable = ['name', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo('App\Models\Category', 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany('App\Models\Category',  'parent_id','id');
    }
}