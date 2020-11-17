<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Merchant;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merchant = Merchant::first();
        $category = Category::where('name', 'Minuman')->first();

        $product = Product::create([
            'merchant_id' => $merchant['id'],
            'name' => 'Kopi Arabika Kelir',
            'base_price' => 8000,
            'selling_price' => 10000,
            'final_price' => 10000,
            'is_stockable' => true,
            'weight' => 0,
            'height' => 0,
            'length' => 0,
            'size' => 0,
            'category_ids' => ([
                $category['id']
            ])
        ]);

        $productStock = ProductStock::create([
            'product_id' => $product['id'],
            'stock' => 100
        ]);

        $product = Product::create([
            'merchant_id' => $merchant['id'],
            'name' => 'Kopi Robusta Kelir',
            'base_price' => 7000,
            'selling_price' => 90000,
            'final_price' => 90000,
            'is_stockable' => true,
            'weight' => 0,
            'height' => 0,
            'length' => 0,
            'size' => 0,
        ]);

        $productStock = ProductStock::create([
            'product_id' => $product['id'],
            'stock' => 200
        ]);
    }
}
