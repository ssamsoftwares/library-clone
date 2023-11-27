<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->unsignedBigInteger('lib_id')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('personal_number')->unique();
            $table->string('emergency_number')->nullable();
            $table->string('dob')->nullable();
            $table->string('course')->nullable();
            $table->string('current_address')->nullable();
            $table->string('permanent_address')->nullable();
            $table->string('subscription')->nullable();
            $table->string('payment')->nullable();
            $table->string('pending_payment')->nullable();
            $table->string('remark_singnature')->nullable();
            $table->string('hall_number')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('aadhar_number')->unique();
            $table->string('aadhar_front_img')->nullable();
            $table->string('aadhar_back_img')->nullable();
            $table->string('image')->nullable();
            $table->enum('status',['active','block'])->default('active');
            $table->unsignedBigInteger('created_by');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('users')
            ->onDelete('cascade');
            $table->foreign('manager_id')->references('id')->on('users')
            ->onDelete('cascade');
            $table->foreign('lib_id')->references('id')->on('libraries')
            ->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
