<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    protected $primaryKey = 'id';

    public $incrementing = false;

    // protected $table = 'transactions';

    protected $fillable = [
        'merchant_id',
        'transaction_code',
        'name',
        'table_number',
        'description',
        'status',
        'total_price_product',
        'total_price'
    ];

    public function detail_products()
    {
        return $this->hasMany('App\Models\TransactionDetail', 'transaction_id');
    }
}