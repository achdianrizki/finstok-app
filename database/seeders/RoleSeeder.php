<?php

namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $managerRole = Role::create([
            'name' => 'manager',
            
        ]);

        $financeRole = Role::create([
            'name' => 'finance',
        ]);

        $adminRole = Role::create([
            'name' => 'admin',
        ]);

        $userOwner = User::create([
            'name' => 'manager',
            'username' => 'manager',
            'email' => 'manager@manager.com',
            'password' => bcrypt('password')
        ]);

        // $userFinance = User::create([
        //     'name' => 'finance',
        //     'email' => 'finance@finance.com',
        //     'password' => bcrypt('password')
        // ]);

        // $userAdmin = User::create([
        //     'name' => 'admin',
        //     'email' => 'admin@admin.com',
        //     'password' => bcrypt('password')
        // ]);

        $supplier = Supplier::create([
            'supplier_code' => 'SUP001',
            'name' => 'Julian',
            'contact' => 'PT.JULIANA',
            'discount1' => null,
            'discount2' => null,
            'phone' => '08123456789',
            'fax_nomor' => null,
            'address' => 'Jl. Supplier No. 1',
            'city' => 'Jakarta',
            'province' => 'DKI Jakarta',
            'payment_term' => null,
            'status' => true,
        ]);

        $supplier = Supplier::create([
            'supplier_code' => 'SUP002',
            'name' => 'Ferupa',
            'contact' => 'PT.FERUPA',
            'discount1' => 25,
            'discount2' => null,
            'phone' => '08123456789',
            'fax_nomor' => null,
            'address' => 'Jl. Supplier No. 1',
            'city' => 'Jakarta',
            'province' => 'DKI Jakarta',
            'payment_term' => null,
            'status' => true,
        ]);

        $userOwner->assignRole($managerRole);
        // $userFinance->assignRole($financeRole);
        // $userAdmin->assignRole($adminRole);
    }
}
