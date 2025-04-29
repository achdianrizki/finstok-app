<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Facades\File;
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

        // Item::truncate();
        $json = File::get('database/json/data_barang.json');
        $items = json_decode($json);
        foreach ($items as $item) {
            Item::create([
                'name' => $item->name,
                'code' => $item->code,
                'purchase_price' => $item->purchase_price,
                'unit' => $item->unit,
                'stock' => $item->stock,
                'description' => $item->description ?? "-",
                'category_id' => 1,
            ]);
        }

        // $json_item_supplier = File::get('database/json/data_barang_supplier.json');
        // $items_supplier = json_decode($json_item_supplier);
        // foreach ($items_supplier as $item) {
        //     \DB::table('item_supplier')->insert([
        //     'id' => $item->id,
        //     'item_id' => $item->item_id,
        //     'supplier_id' => $item->supplier_id,
        //     ]);
        // }
    }
}
