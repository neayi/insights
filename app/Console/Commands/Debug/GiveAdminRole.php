<?php

namespace App\Console\Commands\Debug;

use App\User;
use Illuminate\Console\Command;

class GiveAdminRole extends Command
{
    protected $signature = 'debug:give-admin-role {email}';

    protected $description = 'Give admin role to the user (email) given';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();
        $user->assignRole(['admin']);
    }
}
