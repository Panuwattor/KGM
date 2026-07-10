<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manufacturing_product_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('manufacturing_product_id');
            $table->string('image_path');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('manufacturing_product_id', 'mfg_product_images_fk')
                  ->references('id')->on('manufacturing_products')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manufacturing_product_images');
    }
};
