<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thesis_group_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->date('deadline');
            $table->enum('deliverable_type', ['Document', 'Presentation', 'Code Repository']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('milestones');
    }
};
