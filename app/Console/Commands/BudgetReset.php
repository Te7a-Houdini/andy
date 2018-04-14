<?php

namespace App\Console\Commands;

use App\Budget;
use Illuminate\Console\Command;
use Illuminate\Validation\Rule;

class BudgetReset extends Command
{
    use \App\Console\Commands\Command;

    protected $description = 'Reset a given budget';
    protected $signature = 'budget:reset';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $budget = Budget::wherename($this->choice('Budget?', Budget::all()->pluck('name')->toArray()))->first();

        if ($budget->type == 'recurring') {
            return $this->error('Recurring budgets cannot be reset.');
        }

        if (! $latestInstance = $budget->instances()->orderBy('id', 'desc')->first()) {
            return $this->info('Done');
        }

        $remainingAmount = 0;
        if ($latestInstance->end_amount) {
            $answer = $this->choice("Budget has {$latestInstance->end_amount} remaining, would you like to add that to the new budget or forget?", [
                'Add that', 'Forget It'
            ]);

            $remainingAmount = $answer == 'Add that' ? $latestInstance->end_amount : 0;
        }

        $latestInstance->update([
            'end_date' => now()->toDateString()
        ]);

        $latestInstance = $budget->instances()->create([
            'start_date' => now()->startOfMonth()->toDateString(),
            'start_amount' => $remainingAmount + $budget->amount,
            'end_amount' => $remainingAmount + $budget->amount,
        ]);

        $this->info("Budget was reset, balance now is {$latestInstance->start_amount}");
    }
}
