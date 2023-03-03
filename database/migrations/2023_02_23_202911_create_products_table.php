<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("name")->unique();
            $table->string("material")->nullable();
            $table->string("location")->nullable();
            $table->string("weight")->nullable();
            $table->integer("alert_when_remaining")->nullable();
            $table->float("total_earnings")->nullable();
            $table->float("total_pieces_sold")->nullable();
            $table->foreignIdFor(\App\Models\Category::class);
            $table->foreignIdFor(\App\Models\Branch::class);
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
        Schema::dropIfExists('products');
    }
};
