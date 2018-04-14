<?php

namespace App\Console\Commands;

use App\Budget;
use App\Spending;
use App\SpendingCategory;
use Illuminate\Console\Command;

class Spend extends Command
{
    use \App\Console\Commands\Command;

    protected $description = 'Add a new spending';
    protected $signature = 'spend {amount}';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->validate([
            'amount' => 'numeric',
        ]);

        $amount = $this->argument('amount');
        $description = $this->ask('On what?', '');
        $category = $this->choice('Category?', array_merge([0 => 'NO'], SpendingCategory::all()->pluck('name')->toArray()), 0);
        $budget = $this->choice('Budget?', array_merge([0 => 'NO'], Budget::all()->pluck('name')->toArray()), 0);

        $category = $category != 'NO' ? SpendingCategory::whereName($category)->first() : null;
        $budget = $budget != 'NO' ? Budget::whereName($budget)->first() : null;

        Spending::create([
            'amount' => $amount,
            'description' => $description,
            'category_id' => $category ? $category->id : null,
            'budget_id' => $budget ? $budget->id : null,
        ]);

        if ($budget) {
            $this->updateBudget($budget, $amount);
        }
    }

    /**
     * @param $budget
     * @param $amount
     */
    private function updateBudget($budget, $amount)
    {
        $latestInstance = $budget->instances()->orderBy('id', 'desc')->first();

        if ($latestInstance && (
                $latestInstance->end_date || ($budget->type == 'recurring' && $latestInstance->start_date->format('y-m') != now()->format('y-m')))
        ) {
            $latestInstance->update([
                'end_date' => now()->toDateString()
            ]);

            $latestInstance = $budget->instances()->create([
                'start_date' => now()->startOfMonth()->toDateString(),
                'start_amount' => $latestInstance->end_amount + $budget->amount,
                'end_amount' => $latestInstance->end_amount + $budget->amount,
            ]);
        }

        if (! $latestInstance) {
            $latestInstance = $budget->instances()->create([
                'start_date' => now()->startOfMonth()->toDateString(),
                'start_amount' => $budget->amount,
                'end_amount' => $budget->amount,
            ]);
        }

        $latestInstance->decrement('end_amount', $amount);

        if ($latestInstance->end_amount > 0 && $latestInstance->end_amount < ($budget->amount * 0.2)) {
            $this->warning('About to reach maximum in: '.$budget->name);
        }

        if ($latestInstance->end_amount == 0) {
            $this->alert('You have nothing left in your budget for: '.$budget->name);
        }

        if ($latestInstance->end_amount < 0) {
            $this->alert('You are spending from the budget of next cycle: '.$budget->name);
        }

        $this->line('');

        $this->line(
            sprintf('Budget is <fg=black;bg=yellow>%s</>, remaining <fg=black;bg=yellow>%s</>',
                $latestInstance->start_amount, $latestInstance->end_amount
            )
        );

        $this->line('');

        $this->line(
            sprintf('Today is <fg=black;bg=yellow>%s</>', now()->toDateString())
        );
    }
}
