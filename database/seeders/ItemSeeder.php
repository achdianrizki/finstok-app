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
            'purchase_price' => 8000,
            'selling_price' => 10000,
            'unit' => 'pcs',
            'stock' => 0,
            'description' => 'Liptint Glossy 12 GRM',
            'category_id' => 1,
            'warehouse_id' => 1,
        ]);
    }
}
