<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->enum('type', ['recurring', 'single']);
            $table->double('amount', 9, 3);
            $table->timestamps();
        });

        Schema::create('budget_instances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('budget_id')->index();
            $table->double('start_amount', 9, 3);
            $table->double('end_amount', 9, 3);
            $table->date('start_date');
            $table->date('end_date')->nullable();
        });

        Schema::create('spendings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description');
            $table->double('amount', 9, 3);
            $table->integer('category_id')->nullable()->index();
            $table->integer('budget_id')->nullable()->index();
            $table->integer('budget_instance')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('spending_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
