<?php 

namespace App\Services;

use App\Models\Product;
use App\Models\ProductStock;
use App\Traits\Search as Search;
class ProductService
{
    protected $join = ['stock', 'merchant'];

    public function findById(String $id)
    {
       return Product::with($this->join)->where('id', $id)->first();
    }

    public function fetch($payload = [])
    {
        return Search::generate(Product::class, $payload);
    }

    public function store(Array $payload)
    {
        $product = Product::create($payload);

        if($payload['is_stockable'])
        {
            ProductStock::create([
                'product_id' => $product['id'],
                'stock' => $payload['stock']
            ]);
        }

        return $product;
    }

    public function update(Array $payload, String $id)
    {
        $product = Product::where('id', $id)->first();
        return $product->update($payload);
    }

    public function delete(String $id)
    {
        return Product::find($id)->delete();
    }

    public function permanentDelete(String $id)
    {
        return Product::where('id', $id)->withTrashed()->forceDelete();
    }

    public function restore(String $id)
    {
        return Product::where('id', $id)->withTrashed()->restore();
    }

    public function updateStock(Array $payload, String $productId)
    {
        $stock = ProductStock::where('product_id', $productId)->update([
            'stock' => $payload['stock']
        ]);

        return $stock;
    }
}
