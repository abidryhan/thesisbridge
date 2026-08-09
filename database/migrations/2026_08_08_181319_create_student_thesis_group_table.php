<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_thesis_group', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('thesis_group_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['student_id', 'thesis_group_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_thesis_group');
    }
};
