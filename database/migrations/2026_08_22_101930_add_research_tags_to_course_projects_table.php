<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_projects', function (Blueprint $table) {
            $table->json('research_tags')->nullable()->after('tech_stack');
        });
    }

    public function down(): void
    {
        Schema::table('course_projects', function (Blueprint $table) {
            $table->dropColumn('research_tags');
        });
    }
};
