<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('original_name', 100);
            $table->string('base_name', 100);
            $table->string('file_name', 100);
            $table->string('extension', 100)->nullable();
            $table->float('size')->nullable();
            $table->string('path', 100)->nullable();
            $table->foreignUuid('uploaded_by')->nullable();
            $table->json('meta_uploaded_by')->nullable();
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
        Schema::dropIfExists('images');
    }
}
