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
        Schema::create('course_to_users', function (Blueprint $table) {
            $table->id('courseattemptid');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('course_id');
            $table->integer('user_id');
            $table->datetime('sent')->nullable();
            $table->integer('sentby')->nullable();
            $table->integer('completed')->default(0);
            $table->string('coursekey');
            $table->datetime('datecompleted')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_to_users');
    }
};
