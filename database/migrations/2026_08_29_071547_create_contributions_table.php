<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->text('description');
            $table->unsignedTinyInteger('percentage')->nullable();
            $table->timestamps();
            $table->unique(['course_project_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contributions');
    }
};
