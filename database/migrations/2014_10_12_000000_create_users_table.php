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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('normal_password')->nullable();
            $table->string('add_student_limit')->nullable();
            $table->enum('plan',['free','paid'])->comment('free,paid')->nullable()->default(null);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->enum('register_type', ['internal', 'external'])->nullable()->default('internal');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
