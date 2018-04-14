<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Budget::create([
            'name' => 'monthly',
            'type' => 'recurring',
            'amount' => 1000
        ]);

        \App\Budget::create([
            'name' => 'sports2018',
            'type' => 'single',
            'amount' => 1000
        ]);

        \App\SpendingCategory::create([
            'name' => 'restaurants-cafes',
        ]);

        \App\SpendingCategory::create([
            'name' => 'side-projects',
        ]);
    }
}
