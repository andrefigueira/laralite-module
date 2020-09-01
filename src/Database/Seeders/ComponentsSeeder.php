<?php

namespace Modules\Laralite\Database\Seeders;

use Illuminate\Database\Seeder;

class ComponentsSeeder extends Seeder
{
    private $components = [
        [
            'name' => 'Content',
            'slug' => 'content',
        ],
        [
            'name' => 'Carousel',
            'slug' => 'carousel',
        ],
        [
            'name' => 'Accordion',
            'slug' => 'accordion',
        ],
        [
            'name' => 'Location',
            'slug' => 'location',
        ],
        [
            'name' => 'Contact',
            'slug' => 'contact',
        ],
        [
            'name' => 'Login',
            'slug' => 'login',
        ],
        [
            'name' => 'Onboarding',
            'slug' => 'onboarding',
        ],
        [
            'name' => 'Signup',
            'slug' => 'signup',
        ],
        [
            'name' => 'MyAccount',
            'slug' => 'myaccount',
        ],
        [
            'name' => 'ResetPassword',
            'slug' => 'resetpassword',
        ],
        [
            'name' => 'EditPersonalDetails',
            'slug' => 'editpersonaldetails',
        ],
        [
            'name' => 'MyAccountStartSaving',
            'slug' => 'myaccountstartsaving',
        ],
    ];

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->components as $name => $slug) {
            DB::table('components')->insert([
                'name' => $name,
                'slug' => $slug,
            ]);
        }
    }
}
