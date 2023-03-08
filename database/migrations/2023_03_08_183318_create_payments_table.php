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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Currency::class);
            $table->foreignIdFor(\App\Models\Trader::class);
            $table->foreignIdFor(\App\Models\Branch::class);
            $table->float("amount");
            $table->float("rate");
            $table->string("type");
            $table->float("currency_was");
            $table->float("currency_became");
            $table->float("current_account_was");
            $table->float("current_account_became");
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
        Schema::dropIfExists('payments');
    }
};
