<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    protected $primaryKey = 'id';

    public $incrementing = false;

    // protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'selling_price',
        'base_price',
        'final_price',
        'is_stockable',
        'is_visible',
        'categories',
        'merchant_id',
        'promos',
        'images',
        'weight',
        'height',
        'length',
        'size',
        'type'
    ];

    protected $casts = [
        'categories' => 'json',
        'promos' => 'json',
        'images' => 'json'
    ];

    public function stock()
    {
        return $this->hasOne('App\Models\ProductStock', 'product_id');
    }

    public function merchant()
    {
        return $this->belongsTo('App\Models\Merchant');
    }
}