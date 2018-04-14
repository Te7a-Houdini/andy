<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spending extends Model
{
    public $table = 'spendings';
    protected $guarded = ['id'];
    public $timestamps = true;
}
