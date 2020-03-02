<?php

use Illuminate\Database\Seeder;

class ComponentsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('components')->insert([
            'name' => 'Content',
            'slug' => 'content',
        ]);
    }
}
