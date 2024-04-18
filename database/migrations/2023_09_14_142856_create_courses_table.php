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
        Schema::create('courses', function (Blueprint $table) {
            $table->id('courseid');
            $table->timestamps();
            $table->softDeletes();
            $table->string('course_title')->nullable();
            $table->string('course_description')->nullable();
            $table->string('course_certificate')->nullable();
            $table->string('document')->nullable();
            $table->string('instructor')->nullable();
            $table->string('keypoint1')->nullable();
            $table->string('keypoint2')->nullable();
            $table->string('keypoint3')->nullable();
            $table->string('keypoint4')->nullable();
            $table->string('keypoint5')->nullable();
            $table->integer('coursettpe')->default(0);
            $table->decimal('passrate',11,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
