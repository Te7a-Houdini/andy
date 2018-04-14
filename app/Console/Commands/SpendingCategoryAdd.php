<?php

namespace App\Console\Commands;

use App\Budget;
use App\SpendingCategory;
use Illuminate\Console\Command;
use Illuminate\Validation\Rule;

class SpendingCategoryAdd extends Command
{
    use \App\Console\Commands\Command;

    protected $description = 'Add a new spending category';
    protected $signature = 'category:add {name}';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->validate([
            'name' => 'alpha_dash|'.Rule::unique('spending_categories', 'name'),
        ]);

        $name = $this->argument('name');

        SpendingCategory::create([
            'name' => $name,
        ]);
    }
}
