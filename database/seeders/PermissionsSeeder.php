<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'C' => 'create',
            'D' => 'delete',
            'U' => 'update',
            'M' => 'media',
            'T' => 'tag'
        ];

        $modules = [
            'Area' => 'UM',
            'Season' => 'UM',
            'Survey' => 'CDUMT',
            'Locus' => 'CDUMT',
            'Ceramic' => 'CDUMT',
            'Stone' => 'CDUMT',
            'Lithic' => 'CDUMT',
            'Fauna' => 'CDUMT',
            'Metal' => 'CDUMT',
            'Glass' => 'CDUMT',
        ];

        foreach ($modules as $m => $perms) {
            $role = Role::create(['name' => $m . ' Manager']);
            $chars = str_split($perms);
            foreach ($chars as $char) {
                $p_name = $m . '-' . $permissions[$char];
                Permission::create(['name' => $p_name]);
                $role->givePermissionTo($p_name);
            }
        }

        $editor = User::findOrFail(2);
        $roles = [];

        foreach ($modules as $m => $perms) {
            array_push($roles, $m . ' Manager');
        }

        $editor->assignRole($roles);
    }
}
