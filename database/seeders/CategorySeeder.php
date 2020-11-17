<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoryNames = ['Makanan', 'Minuman', 'Snack'];
        $i = 0;
        foreach ($categoryNames as $name) {
            $category[$i] = Category::create([
                'name' => $name
            ]);

            $i++;
        }

        $category = Category::create([
            'name' => 'Makanan Ringan',
            'parent_id' => $category[0]['id']
        ]);
    }
}
