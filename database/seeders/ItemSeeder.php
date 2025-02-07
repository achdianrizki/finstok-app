<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Liptint'
        ]);

        Item::create([
            'name' => 'Liptint Glossy',
            'code' => '09090231',
            'price' => 10000,
            'stock' => 0,
            'category_id' => 1,
            'warehouse_id' => 1,
        ]);
    }
}
