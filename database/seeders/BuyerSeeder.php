<?php

namespace Database\Seeders;

use App\Models\Buyer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BuyerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Buyer::create([
            'name' => 'PT Sentosa Jaya Abadi',
            'contact' => 'Jack',
            'phone' => '0384789347',
            'address' => 'Jl Pahlawan',
            'NPWP' => '2342346',
            'type' => 'General Trade',
        ]);

        Buyer::create([
            'name' => 'PT Mitra Global Sejahtera',
            'contact' => 'John',
            'phone' => '03243478',
            'address' => 'Jl Ahmad Yani',
            'type' => 'Modern Trade',
        ]);

        Buyer::create([
            'name' => 'PT Sukses Makmur Bersama',
            'contact' => 'Max',
            'phone' => '03487423987',
            'address' => 'Jl Keberuntungan',
            'NPWP' => '832478',
            'type' => 'Modern Trade',
        ]);

        Buyer::create([
            'name' => 'PT Indo Perkasa Mandiri',
            'contact' => 'Ahmad',
            'phone' => '042747623',
            'address' => 'Jl Cijeruk',
            'type' => 'General Trade',
        ]);
    }
}
