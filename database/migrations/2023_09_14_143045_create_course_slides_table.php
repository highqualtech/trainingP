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
        Schema::create('course_slides', function (Blueprint $table) {
            $table->id('courseslideid');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('course_id');
            $table->integer('slide_type')->default(0);
            $table->integer('slide_sort')->default(0);
            $table->longText('slidehtml');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_slides');
    }
};
