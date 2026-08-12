<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thesis_group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('milestone_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('logged_by_user_id')->constrained('users')->cascadeOnDelete();
            $table->date('meeting_date');
            $table->text('agenda');
            $table->text('outcomes');
            $table->text('next_action_items');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
