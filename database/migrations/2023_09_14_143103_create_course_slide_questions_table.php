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
        Schema::create('course_slide_questions', function (Blueprint $table) {
            $table->id('questionid');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('course_id');
            $table->text('questiontext');
            $table->text('answertext1')->nullable();
            $table->text('answertext2')->nullable();
            $table->text('answertext3')->nullable();
            $table->text('answertext4')->nullable();
            $table->decimal('sort');
            $table->integer('correct_answer');
            $table->text('youtubevid')->nullable();
            $table->integer('slideid');
            $table->integer('question_type')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_slide_questions');
    }
};
