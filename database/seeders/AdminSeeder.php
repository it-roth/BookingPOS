<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing admins
        DB::table('admins')->truncate();

        // Create a new admin with simple credentials
        Admin::create([
            'username' => 'admin',
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }
}
