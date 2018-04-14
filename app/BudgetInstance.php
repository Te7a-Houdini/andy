<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BudgetInstance extends Model
{
    public $table = 'budget_instances';
    protected $guarded = ['id'];
    protected $dates = ['start_date', 'end_date'];
    public $timestamps = false;
}
