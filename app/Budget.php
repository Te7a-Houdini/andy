<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    public $table = 'budgets';
    protected $guarded = ['id'];
    public $timestamps = true;

    public function instances()
    {
        return $this->hasMany(BudgetInstance::class, 'budget_id');
    }

    public function remaining()
    {
        $spendings = Spending::whereBudgetId($this->id)
            ->when($this->type == 'recurring' && $this->frequency == 'monthly', function ($q) {
                return $q->whereBetween('created_at', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()]);
            })
            ->get();

        return $spendings->sum('amount');
    }
}
