<?php

namespace App\Console\Commands;

use App\Budget;
use Illuminate\Console\Command;
use Illuminate\Validation\Rule;

class BudgetList extends Command
{
    use \App\Console\Commands\Command;

    protected $description = 'List all budgets';
    protected $signature = 'budget:list';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $budgets = Budget::all(['name', 'amount', 'type']);

        $this->table([], $budgets);
    }
}
