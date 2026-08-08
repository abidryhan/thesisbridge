<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->json('tech_stack');
            $table->json('team_members');
            $table->enum('term', ['Spring', 'Summer', 'Fall']);
            $table->integer('year');
            $table->string('course_name');
            $table->string('github_link')->nullable();
            $table->string('demo_link')->nullable();
            $table->json('screenshot_paths')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_projects');
    }
};
