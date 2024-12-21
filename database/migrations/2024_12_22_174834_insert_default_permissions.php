<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $permissions = [
        'manage authorities',
        'manage bans',
        'manage remotes',
        'manage users',
        'manage roles',
        'manage permissions',
        'view authorities',
        'view bans',
        'view remotes',
        'view users',
        'view roles',
        'view permissions',
    ];

    private $roles = [
        'moderator' => [
            'manage bans',
            'view authorities',
            'view bans',
            'view remotes',
        ],

        'admin' => [
            'manage authorities',
            'manage bans',
            'manage remotes',
            'view authorities',
            'view bans',
            'view remotes',
        ],

        'superadmin' => [],
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissionIDs = [];

        foreach ($this->permissions as $permission) {
            $id = DB::table('permissions')->insertGetId([
                'name' => $permission,
                'guard_name' => 'web',
            ]);

            $permissionIDs[$permission] = $id;
        }

        foreach ($this->roles as $role => $permissions) {
            $id = DB::table('roles')->insertGetId([
                'name' => $role,
                'guard_name' => 'web',
            ]);

            foreach ($permissions as $permission) {
                DB::table('role_has_permissions')->insert([
                    'permission_id' => $permissionIDs[$permission],
                    'role_id' => $id,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('permissions')->truncate();
    }
};
