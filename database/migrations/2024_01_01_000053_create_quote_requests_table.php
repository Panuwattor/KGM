<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quote_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('company_name');
            $table->string('contact_name');
            $table->string('email');
            $table->string('phone');
            $table->text('product_details');
            $table->integer('quantity');
            $table->text('notes')->nullable();
            $table->string('attachment')->nullable();
            $table->enum('status', ['pending', 'quoted', 'accepted', 'rejected', 'closed'])->default('pending');
            $table->decimal('quoted_price', 10, 2)->nullable();
            $table->string('quote_pdf')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamp('quoted_at')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quote_requests');
    }
};
