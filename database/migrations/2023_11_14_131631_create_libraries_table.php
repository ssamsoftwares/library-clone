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
        Schema::create('libraries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->text('manager_id')->nullable();
            $table->string('library_name');
            $table->longText('address');
            $table->longText('description')->nullable();
            $table->string('logo')->nullable();
            $table->enum('status',['approved','pending'])->default('pending');
            $table->enum('active_status',['active','block'])->default('active');
            $table->unsignedBigInteger('created_id')->nullable();

            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('users')
            ->onDelete('cascade');

            // $table->foreign('manager_id')->references('id')->on('users')
            // ->onDelete('cascade');

            $table->foreign('created_id')->references('id')->on('users')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libraries');
    }
};
