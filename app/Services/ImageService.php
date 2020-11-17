<?php 

namespace App\Services;

use App\Models\Image;
use App\Traits\Search as Search;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    protected $join = [];

    public function findById(String $id)
    {
       return Image::with($this->join)->where('id', $id)->first();
    }

    public function fetch($payload = [])
    {
        return Search::generate(Image::class, $payload);
    }

    public function store($payload)
    {
        $name = $payload->getClientOriginalName();
        $size = filesize($payload);
        $storage = Storage::disk('public')->put('images/', $payload);
        $info = pathinfo($storage);

        $data = [
            "original_name" => $name,
            "base_name" => $info['basename'],
            "file_name" => $info['filename'],
            "extension" => $info["extension"],
            "path" => $info["dirname"].'/'.$info["basename"],
            "size" => $size,
            "uploaded_by" => auth()->user()->id,
            "meta_uploaded_by" => auth()->user()
        ];
        $image = Image::create($data);

        return $image;
    }

    public function delete(String $id)
    {
        $image = Image::where('id', $id)->first();
        $exists = Storage::exists('public/'.$image['path']);

        if($exists){
            Storage::delete('public/'.$image['path']);
        }
        return  $image->delete();
    }
}