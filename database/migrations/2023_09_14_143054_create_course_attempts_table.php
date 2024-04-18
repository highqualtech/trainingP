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
        Schema::create('course_attempts', function (Blueprint $table) {
            $table->id('courseattemptid');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('course_id');
             $table->integer('user_id');
             $table->integer('quesion_id');
             $table->integer('answer_id');
             $table->string('coursekey');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_attempts');
    }
};
