<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory, Uuid;
    protected $primaryKey = 'id';

    public $incrementing = false;

    // protected $table = 'images';

    protected $fillable = ['original_name','base_name','file_name', 'extension', 'path', 'size', 'uploaded_by', 'meta_uploaded_by'];

    protected $casts = [
        'meta_uploaded_by' => 'json'
    ];

    protected $appends = [
        'url', 
        // 'size_ori'
    ];

    public function getUrlAttribute()
    {   
        return asset($this->path);
    }

    // public function getSizeOriAttribute()
    // {
    //     return Storage::size('public/'.$this->path);
    // }
}