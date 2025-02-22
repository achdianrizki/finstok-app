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
            'name' => 'Kosmetik'
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

        Item::create([
            'name' => 'BDL-PAPAYA SOAP 128 GR',
            'code' => 'BDL0002',
            'purchase_price' => 9099,
            'selling_price' => 9099,
            'unit' => 'pcs',
            'stock' => 0,
            'description' => 'BDL-PAPAYA SOAP 128 GR',
            'category_id' => 1,
            'warehouse_id' => 1,
        ]);

        Item::create([
            'name' => 'HIMALAYA PURIFYING NEEM FACE WASH 50ML',
            'code' => '8004827',
            'purchase_price' => 18986,
            'selling_price' => 18.986,
            'unit' => 'pcs',
            'stock' => 0,
            'description' => 'HIMALAYA PURIFYING NEEM FACE WASH 50ML',
            'category_id' => 1,
            'warehouse_id' => 1,
        ]);

        Item::create([
            'name' => 'Salsa Eyebrow 2 In 1 isi 12',
            'code' => 'SALSA020',
            'purchase_price' => 4144,
            'selling_price' => 4144,
            'unit' => 'pcs',
            'stock' => 0,
            'description' => 'Salsa Eyebrow 2 In 1 isi 12',
            'category_id' => 1,
            'warehouse_id' => 1,
        ]);

        Item::create([
            'name' => 'BIOAQUA Rose Yeast Elastic & Tender Spray 150ml ',
            'code' => 'AQ0025',
            'purchase_price' => 27026,
            'selling_price' => 27026,
            'unit' => 'pcs',
            'stock' => 0,
            'description' => 'BIOAQUA Rose Yeast Elastic & Tender Spray 150ml ',
            'category_id' => 1,
            'warehouse_id' => 1,
        ]);
    }
}
