<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class TransactionDetail extends Model
{
    use HasFactory, Uuid;
    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $table = 'transaction_details';

    protected $fillable = [
        'transaction_id',
        'detail_id',
        'qty',
        'meta',
        'type'
    ];

    protected $casts = [
        'meta' => 'json'
    ];


    protected $appends = ['name', 'final_price', 'total_price'];

    public function getNameAttribute()
    {   
        return $this->meta['name'];
    }

    public function getFinalPriceAttribute()
    {   
        return $this->meta['final_price'];
    }

    public function getTotalPriceAttribute()
    {   
        return $this->meta['final_price'] * $this->qty;
    }
}
