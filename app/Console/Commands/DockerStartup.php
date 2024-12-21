<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DockerStartup extends Command
{
    protected $signature = 'app:docker-startup';

    protected $description = 'Handle the startup of the Docker container.';

    protected $hidden = true;

    private function createDefaultUser()
    {
        $password = Str::random(length: 32);

        $superAdminRole = Role::query()
            ->where('name', '=', 'superadmin')
            ->firstOrFail();

        /** @var User */
        $user = User::create([
            'name' => 'SuperAdmin',
            'email' => 'superadmin@checkpoint',
            'password' => Hash::make($password),
        ]);

        $user->assignRole($superAdminRole);

        $this->components->info('The default user was created with the following credentials:');
        print "         -> Email: superadmin@checkpoint\n";
        print "         -> Password: {$password}\n\n";
        print "         \x1b[33mThese details will not be shown again\x1b[0m\n";
    }

    public function handle()
    {
        /** @var int */
        $userCount = User::count();

        if ($userCount === 0) {
            $this->createDefaultUser();
        }
    }
}
