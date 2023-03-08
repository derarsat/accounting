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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Product::class);
            $table->foreignIdFor(\App\Models\Quantity::class);
            $table->foreignIdFor(\App\Models\Trader::class);
            $table->float("quantity_value");
            $table->float("extra_quantity")->nullable();
            $table->float("purchased");
            $table->float("min_price");
            $table->float("price");
            $table->string("image")->nullable();
            $table->string("barcode")->nullable();
            $table->string("identifier")->nullable();
            $table->string("weight_value")->nullable();
            $table->date("expire")->nullable();
            $table->float("extra_cost")->default(0);
            $table->boolean("tva")->default(false);
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
        Schema::dropIfExists('product_variants');
    }
};
