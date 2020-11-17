<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('merchant_id');
            $table->string('transaction_code')->unique();
            $table->string('name')->nullable();
            $table->string('table_number')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['UNPAID', 'PAID', 'CANCELED'])->default('UNPAID');
            $table->float('total_price_product')->default(0);
            $table->float('total_price')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('transactions');
    }
}
