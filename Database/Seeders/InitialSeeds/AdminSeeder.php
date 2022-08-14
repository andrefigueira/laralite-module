<?php


namespace Modules\Laralite\Database\Seeders\InitialSeeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Laralite\Models\Roles;
use Modules\Laralite\Models\User;


class AdminSeeder extends seeder
{
    public function run()
    {
        $user = User::firstOrCreate(['email' => 'admin@laralite.com'],[
            'name' => 'admin',
            'password' => Hash::make('admin123'),
        ]);

        $user->assignRole(
            Roles::firstWhere('name', '=', 'admin'),
            Roles::firstWhere('name', '=',  'scanner')
        );
        $user->save();
    }

}