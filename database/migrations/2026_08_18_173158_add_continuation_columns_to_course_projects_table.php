<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_projects', function (Blueprint $table) {
            $table->boolean('is_open_for_continuation')->default(false)->after('screenshot_paths');
            $table->foreignId('continued_from_id')->nullable()->after('is_open_for_continuation')
                ->constrained('course_projects')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('course_projects', function (Blueprint $table) {
            $table->dropForeign(['continued_from_id']);
            $table->dropColumn(['is_open_for_continuation', 'continued_from_id']);
        });
    }
};