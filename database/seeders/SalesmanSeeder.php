<?php

namespace Database\Seeders;

use App\Models\Salesman;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalesmanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Salesman::create([
            'name' => 'Ahmand',
            'phone' => '093284372',
            'address' => 'Jl. Palangkaraya',
        ]);
    }
}
