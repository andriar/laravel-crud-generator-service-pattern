<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->float('selling_price')->default(0);
            $table->float('base_price')->default(0);
            $table->float('final_price')->default(0);
            $table->boolean('is_stockable')->default(false);
            $table->boolean('is_visible')->default(false);
            $table->json('categories')->nullable();
            $table->foreignUuid('merchant_id')->nullable();
            $table->json('promos')->nullable();
            $table->json('images')->nullable();

            //unsigned nya salahhhh om ekwkkw
            $table->float('weight')->unsigned();
            $table->float('height')->unsigned();
            $table->float('length')->unsigned();
            $table->float('size')->unsigned();
            $table->string('type')->nullable();
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
        Schema::dropIfExists('products');
    }
}
