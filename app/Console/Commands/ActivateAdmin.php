<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ActivateAdmin extends Command
{
    protected $signature = 'admin:activate {username}';
    protected $description = 'Activate an admin account';

    public function handle()
    {
        $username = $this->argument('username');
        
        $admin = Admin::where('username', $username)->first();
        
        if (!$admin) {
            $this->error("Admin user with username '{$username}' not found.");
            return 1;
        }

        $admin->is_active = true;
        $admin->save();

        $this->info("Admin account '{$username}' has been activated successfully.");
        return 0;
    }
} 