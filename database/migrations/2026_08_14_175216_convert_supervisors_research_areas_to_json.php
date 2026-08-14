<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('supervisors', function (Blueprint $table) {
            $table->json('research_areas_new')->nullable()->after('research_areas');
        });

        DB::table('supervisors')->orderBy('id')->chunk(50, function ($supervisors) {
            foreach ($supervisors as $supervisor) {
                $tags = array_values(array_filter(
                    array_map('trim', explode(',', $supervisor->research_areas ?? ''))
                ));

                DB::table('supervisors')
                    ->where('id', $supervisor->id)
                    ->update(['research_areas_new' => json_encode($tags)]);
            }
        });

        Schema::table('supervisors', function (Blueprint $table) {
            $table->dropColumn('research_areas');
        });

        DB::statement('ALTER TABLE supervisors RENAME COLUMN research_areas_new TO research_areas');
    }

    public function down(): void
    {
        Schema::table('supervisors', function (Blueprint $table) {
            $table->text('research_areas_old')->nullable()->after('research_areas');
        });

        DB::table('supervisors')->orderBy('id')->chunk(50, function ($supervisors) {
            foreach ($supervisors as $supervisor) {
                $tags = json_decode($supervisor->research_areas ?? '[]', true) ?: [];

                DB::table('supervisors')
                    ->where('id', $supervisor->id)
                    ->update(['research_areas_old' => implode(', ', $tags)]);
            }
        });

        Schema::table('supervisors', function (Blueprint $table) {
            $table->dropColumn('research_areas');
        });

        DB::statement('ALTER TABLE supervisors RENAME COLUMN research_areas_old TO research_areas');
    }
};
