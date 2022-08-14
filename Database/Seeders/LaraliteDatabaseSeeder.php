<?php

namespace Modules\Laralite\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Laralite\Database\Seeders\InitialSeeds\AdminSeeder;
use Modules\Laralite\Database\Seeders\InitialSeeds\RolesSeeder;
use Modules\Laralite\Database\Seeders\InitialSeeds\SettingsSeeder;

class LaraliteDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(RolesSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(SettingsSeeder::class);
    }
}
