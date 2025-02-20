<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Warehouse;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(WarehouseSeeder::class);
        $this->call(ItemSeeder::class);
        $this->call(BuyerSeeder::class);
        $this->call(SalesmanSeeder::class);
    }
}
