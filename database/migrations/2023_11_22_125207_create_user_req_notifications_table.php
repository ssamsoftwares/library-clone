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
        Schema::create('user_req_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('regiReq_id');
            $table->enum('is_seen', ["unseen", "seen"])->default("unseen");
            $table->timestamps();

            $table->foreign('regiReq_id')->references('id')->on('registration_requests')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_req_notifications');
    }
};
