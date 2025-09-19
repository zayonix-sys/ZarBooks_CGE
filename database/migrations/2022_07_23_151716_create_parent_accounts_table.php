<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParentAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parent_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('account_group');
            $table->string('title');
            $table->boolean('status')->default(true);
            $table->boolean('is_cash_book')->default(false);
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
        Schema::dropIfExists('parent_accounts');
    }
}
