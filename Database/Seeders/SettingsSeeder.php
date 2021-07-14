<?php


namespace Modules\Laralite\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class SettingsSeeder extends seeder
{
    public function run()
    {
        DB::table('settings')->insert([
            'active' => 1,
            'settings' => [
                'currency' => '',
                'feeActive' =>  false,
                'feeAmount' => null,
                'siteLogo' => 'KDHlQw6uQy2GzFiUY3iexCo6kpGfj0AAju3P7XeR.png',
                'buttonPrimaryColor' => '#36B890',
                'buttonSecondaryColor' => '#EE531C',
                'textPrimaryColor' => '#1EEF70',
                'textHighlightColor' => '#D7EF3B']
        ]);
    }

}