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
            'name' => 'SALSA BOLD EYELINER',
            'code' => '10001',
            'purchase_price' => 16216.21,
            'unit' => 'pcs',
            'stock' => 0,
            'description' => 'SALSA BOLD EYELINER',
            'category_id' => 1,
            
        ]);

        Item::create([
            'name' => 'SALSA NAIL POLISH 8ML NUDE 1',
            'code' => '10002',
            'purchase_price' => 27477,
            'unit' => 'set',
            'stock' => 0,
            'description' => 'SALSA NAIL POLISH 8ML NUDE 1',
            'category_id' => 1,
            
        ]);

        Item::create([
            'name' => 'SALSA NAIL POLISH 8ML NUDE 2',
            'code' => '10003',
            'purchase_price' => 27477,
            'unit' => 'set',
            'stock' => 0,
            'description' => 'SALSA NAIL POLISH 8ML NUDE 2',
            'category_id' => 1,
            
        ]);

        Item::create([
            'name' => 'SALSA NAIL POLISH 8 ML GLAMOUR',
            'code' => '10004',
            'purchase_price' => 27477,
            'unit' => 'set',
            'stock' => 0,
            'description' => 'SALSA NAIL POLISH 8 ML GLAMOUR',
            'category_id' => 1,
            
        ]);

        Item::create([
            'name' => 'SALSA KUTEX PEEL POLISH COFFEE',
            'code' => '10005',
            'purchase_price' => 19633,
            'unit' => 'pcs',
            'stock' => 0,
            'description' => 'SALSA KUTEX PEEL POLISH COFFEE',
            'category_id' => 1,
            
        ]);

        Item::create([
            'name' => 'SALSA KUTEX PEEL POLISH PINK ON',
            'code' => '10006',
            'purchase_price' => 19633,
            'unit' => 'pcs',
            'stock' => 0,
            'description' => 'SALSA KUTEX PEEL POLISH PINK ON',
            'category_id' => 1,
            
        ]);

        Item::create([
            'name' => 'SALSA KUTEX PEEL POLISH FLORIS',
            'code' => '10007',
            'purchase_price' => 19633,
            'unit' => 'pcs',
            'stock' => 0,
            'description' => 'SALSA KUTEX PEEL POLISH FLORIS',
            'category_id' => 1,
            
        ]);

        Item::create([
            'name' => 'SALSA NAIL POLISH 6 ML BENING (HS) 121',
            'code' => '10008',
            'purchase_price' => 4810,
            'unit' => 'pcs',
            'stock' => 0,
            'description' => 'SALSA NAIL POLISH 6 ML BENING (HS) 121',
            'category_id' => 1,
            
        ]);

        Item::create([
            'name' => 'SALSA KERATIN HAIR SERUM',
            'code' => '10034',
            'purchase_price' => 16216,
            'unit' => 'pcs',
            'stock' => 0,
            'description' => 'SALSA KERATIN HAIR SERUM',
            'category_id' => 1,
            
        ]);

        Item::create([
            'name' => 'SALSA GROWTH HAIR SERUM',
            'code' => '10035',
            'purchase_price' => 16216,
            'unit' => 'pcs',
            'stock' => 0,
            'description' => 'SALSA GROWTH HAIR SERUM',
            'category_id' => 1,
            
        ]);
    }
}
