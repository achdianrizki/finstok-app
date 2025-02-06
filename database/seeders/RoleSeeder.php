<?php

namespace Database\Seeders;

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
            'email' => 'manager@manager.com',
            'password' => bcrypt('password')
        ]);

        $userFinance = User::create([
            'name' => 'finance',
            'email' => 'finance@finance.com',
            'password' => bcrypt('password')
        ]);

        $userAdmin = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password')
        ]);

        $userOwner->assignRole($managerRole);
        $userFinance->assignRole($financeRole);
        $userAdmin->assignRole($adminRole);
    }
}
