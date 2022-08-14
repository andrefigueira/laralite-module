<?php

namespace Modules\Laralite\Database\Seeders\InitialSeeds;

use Illuminate\Database\Seeder;
use Modules\Laralite\Models\Permissions;
use Modules\Laralite\Models\Roles;

class RolesSeeder extends seeder
{
    public function run()
    {
        $role = Roles::firstOrCreate(['name' => 'admin'], [
            'guard_name' => 'api'
        ]);

        $role->givePermissionTo(Permissions::firstWhere('name', '=', 'scanning'));
        $role->save();

        Roles::firstOrCreate(['name' => 'scanner'], [
            'guard_name' => 'api'
        ]);
    }

}