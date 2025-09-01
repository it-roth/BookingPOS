<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetAdmin extends Command
{
    protected $signature = 'admin:reset';
    protected $description = 'Reset admin account to default settings';

    public function handle()
    {
        $admin = Admin::where('username', 'admin')->first();
        
        if (!$admin) {
            $admin = new Admin();
            $admin->username = 'admin';
            $admin->name = 'Administrator';
            $admin->email = 'admin@admin.com';
            $admin->role = 'admin';
        }

        $admin->password = Hash::make('123');
        $admin->is_active = true;
        $admin->save();

        $this->info("Admin account has been reset successfully.");
        $this->info("Username: admin");
        $this->info("Password: 123");
        
        return 0;
    }
} 