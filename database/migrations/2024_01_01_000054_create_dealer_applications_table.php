<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dealer_applications', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('contact_name');
            $table->string('email');
            $table->string('phone');
            $table->text('address');
            $table->string('province');
            $table->string('business_type')->nullable();
            $table->text('description')->nullable();
            $table->string('tax_id')->nullable();
            $table->enum('status', ['new', 'reviewing', 'approved', 'rejected'])->default('new');
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dealer_applications');
    }
};
