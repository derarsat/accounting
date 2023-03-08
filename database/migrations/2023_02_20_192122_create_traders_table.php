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
            $table->float("purchased")->nullable();
            $table->float("sold")->nullable();
            $table->float("earned")->nullable();
            $table->float("to_pay")->nullable();
            $table->float("to_collect")->nullable();
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
