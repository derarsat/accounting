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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Currency::class);
            $table->foreignIdFor(\App\Models\Trader::class);
            $table->foreignIdFor(\App\Models\Branch::class);
            $table->float("amount");
            $table->float("rate");
            $table->string("earned");
            $table->float("currency_was");
            $table->float("currency_became");
            $table->float("current_account_was");
            $table->float("current_account_became");
            $table->string("status");
            $table->string("type");
            $table->string("old_type");
            $table->boolean("returned");
            $table->foreignIdFor(\App\Models\Invoice::class, "returned_invoice_id")->nullable();
            $table->timestamps();
        });

        Schema::create('invoice_products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Currency::class);
            $table->foreignIdFor(\App\Models\ProductVariant::class);
            $table->foreignIdFor(\App\Models\Category::class);
            $table->foreignIdFor(\App\Models\Trader::class);
            $table->foreignIdFor(\App\Models\Branch::class);
            $table->foreignIdFor(\App\Models\Invoice::class);
            $table->foreignIdFor(\App\Models\Quantity::class);
            $table->float("amount");
            $table->float("rate");
            $table->float("quantity");
            $table->float("extra_quantity");
            $table->string("earned");
            $table->string("invoice_type");
            $table->foreignIdFor(\App\Models\Invoice::class, "returned_invoice_id")->nullable();
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
        Schema::dropIfExists('invoices');
    }
};
