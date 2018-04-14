<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpendingCategory extends Model
{
    public $table = 'spending_categories';
    protected $guarded = ['id'];
    public $timestamps = true;
}
