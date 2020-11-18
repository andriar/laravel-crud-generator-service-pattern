<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;
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
            $table->json('category_ids')->default(new Expression('(JSON_ARRAY())'));
            $table->foreignUuid('merchant_id')->nullable();
            $table->json('promo_ids')->default(new Expression('(JSON_ARRAY())'));
            $table->json('image_ids')->default(new Expression('(JSON_ARRAY())'));

            //unsigned nya salahhhh om ekwkkw
            $table->float('weight')->default(0);
            $table->float('height')->default(0);
            $table->float('length')->default(0);
            $table->float('size')->default(0);
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
