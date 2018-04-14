<?php

namespace App\Console\Commands;

use App\Budget;
use Illuminate\Console\Command;
use Illuminate\Validation\Rule;

class BudgetAdd extends Command
{
    use \App\Console\Commands\Command;

    protected $description = 'Add a new budget';
    protected $signature = 'budget:add {name} {amount}';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->validate([
            'name' => 'alpha_dash|'.Rule::unique('budgets', 'name'),
            'amount' => 'numeric'
        ]);

        Budget::create([
            'name' => $this->argument('name'),
            'amount' => $this->argument('amount'),
            'type' => $this->choice('What is the budget type?', ['recurring', 'single']),
        ]);
    }
}
