<?php

namespace Modules\Laralite\Database\Seeders\InitialSeeds;

use Illuminate\Database\Seeder;
use Modules\Laralite\Models\Settings;

class SettingsSeeder extends seeder
{
    public function run()
    {
        Settings::firstOrCreate(['id' => 1], [
            'active' => 1,
            'settings' => [
                'currency' => [
                    'value' => 'USD',
                    'currency_symbol' => '$',
                ],
                'feeActive' =>  false,
                'feeAmount' => null,
                'siteLogo' => 'KDHlQw6uQy2GzFiUY3iexCo6kpGfj0AAju3P7XeR.png',
                'buttonPrimaryColor' => '#36B890',
                'buttonSecondaryColor' => '#EE531C',
                'textPrimaryColor' => '#1EEF70',
                'textHighlightColor' => '#D7EF3B'
            ]
        ]);
    }

}