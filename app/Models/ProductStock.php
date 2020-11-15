<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class ProductStock extends Model
{
    use HasFactory, Uuid;

    protected $table = 'product_stocks';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'stock',
    ];
}
