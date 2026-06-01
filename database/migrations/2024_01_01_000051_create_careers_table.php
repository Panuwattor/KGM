<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('department')->nullable();
            $table->enum('type', ['full_time', 'part_time', 'contract'])->default('full_time');
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->text('benefits')->nullable();
            $table->string('location')->nullable();
            $table->integer('vacancies')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamp('closes_at')->nullable();
            $table->timestamps();
        });

        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('career_id')->nullable();
            $table->string('position_applied');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->date('birthdate')->nullable();
            $table->string('resume_path')->nullable();
            $table->text('cover_letter')->nullable();
            $table->enum('status', ['new', 'reviewing', 'interviewed', 'hired', 'rejected'])->default('new');
            $table->text('admin_note')->nullable();
            $table->timestamps();

            $table->foreign('career_id')->references('id')->on('careers')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('careers');
    }
};
