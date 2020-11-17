<?php 

namespace App\Services;

use App\Models\Category;
use App\Traits\Search as Search;
class CategoryService
{
    protected $join = ['parent', 'children'];

    public function findById(String $id)
    {
       return Category::with($this->join)->where('id', $id)->first();
    }

    public function fetch($payload = [])
    {
        return Search::generate(Category::class, $payload);
    }

    public function store(Array $payload)
    {
        $category = Category::create($payload);
        return $category;
    }

    public function update(Array $payload, String $id)
    {
        $category = Category::where('id', $id)->first();
        return $category->update($payload);
    }

    public function delete(String $id)
    {
        return Category::find($id)->delete();
    }

    public function permanentDelete(String $id)
    {
        return Category::where('id', $id)->withTrashed()->forceDelete();
    }

    public function restore(String $id)
    {
        return Category::where('id', $id)->withTrashed()->restore();
    }
}
