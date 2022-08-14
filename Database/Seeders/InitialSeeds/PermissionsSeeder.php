<?php

namespace Modules\Laralite\Database\Seeders\InitialSeeds;

use Illuminate\Database\Seeder;
use Modules\Laralite\Models\Permissions;
use Modules\Laralite\Models\Roles;

class PermissionsSeeder extends seeder
{
    public function run()
    {
        Permissions::firstOrCreate(['name' => 'scanning'], [
            'guard_name' => 'api'
        ]);
    }

}