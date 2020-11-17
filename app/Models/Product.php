<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Image;
use App\Models\Category;

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
        'category_ids',
        'merchant_id',
        'promo_ids',
        'image_ids',
        'weight',
        'height',
        'length',
        'size',
        'type'
    ];

    protected $casts = [
        'category_ids' => 'json',
        'promo_ids' => 'json',
        'image_ids' => 'json'
    ];

    protected $appends = ['images', 'categories'];

    public function getImagesAttribute()
    {   
        $images = Image::whereIn('id', $this->image_ids)->get();
        return $images;
    }

    public function getCategoriesAttribute()
    {   
        $categories = Category::whereIn('id', $this->category_ids)->get();
        return $categories;
    }

    public function stock()
    {
        return $this->hasOne('App\Models\ProductStock', 'product_id');
    }

    public function merchant()
    {
        return $this->belongsTo('App\Models\Merchant');
    }
}