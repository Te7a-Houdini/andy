<?php

namespace App\Console\Commands;

use App\Budget;
use App\SpendingCategory;
use Illuminate\Console\Command;
use Illuminate\Validation\Rule;

class SpendingCategoryList extends Command
{
    use \App\Console\Commands\Command;

    protected $description = 'List all spending categories';
    protected $signature = 'category:list';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $all = SpendingCategory::all(['name']);

        $this->table([], $all);
    }
}
