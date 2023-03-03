<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('traders', function (Blueprint $table) {
            $table->id();
            $table->string("name")->unique();
            $table->string("phone")->unique();
            $table->string("address")->nullable();
            $table->float("he_sold_us")->nullable();
            $table->float("we_sold_him")->nullable();
            $table->float("we_earned_from_him")->nullable();
            $table->float("we_owe_him")->nullable();
            $table->float("he_owes_us")->nullable();
            $table->float("current_account")->nullable();
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
        Schema::dropIfExists('traders');
    }
};
