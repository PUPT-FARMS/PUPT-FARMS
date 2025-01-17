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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('courses_files_id');
            $table->unsignedBigInteger('user_login_id');
            $table->unsignedBigInteger('folder_name_id');
            $table->string('sender');
            $table->timestamps();

            $table->foreign('courses_files_id')->references('courses_files_id')->on('courses_files')->onDelete('cascade');
            $table->foreign('user_login_id')->references('user_login_id')->on('user_login')->onDelete('cascade');
            $table->foreign('folder_name_id')->references('folder_name_id')->on('folder_name')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
