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
            'name' => 'Jack',
            'phone' => '0384789347',
            'address' => 'Jl Pahlawan',
            'type' => 'General Trade',
        ]);

        Buyer::create([
            'name' => 'John',
            'phone' => '03243478',
            'address' => 'Jl Ahmad Yani',
            'type' => 'Modern Trade',
        ]);

        Buyer::create([
            'name' => 'Max',
            'phone' => '03487423987',
            'address' => 'Jl Kebenruntungan',
            'type' => 'Modern Trade',
        ]);

        Buyer::create([
            'name' => 'Ahmad',
            'phone' => '042747623',
            'address' => 'Jl Cijeruk',
            'type' => 'General Trade',
        ]);
    }
}
