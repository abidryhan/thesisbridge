<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_project_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['course_project_id', 'student_id']);
        });

        // Best-effort backfill of existing team_members
        $studentsByName = DB::table('students')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->select('students.id as student_id', 'students.user_id', 'users.name as user_name')
            ->get()
            ->keyBy(fn ($row) => strtolower(trim($row->user_name)));

        $studentsByUserId = DB::table('students')->pluck('id', 'user_id');

        DB::table('course_projects')->orderBy('id')->chunk(50, function ($projects) use ($studentsByName, $studentsByUserId) {
            foreach ($projects as $project) {
                $linkedStudentIds = [];

                if (isset($studentsByUserId[$project->user_id])) {
                    $linkedStudentIds[] = $studentsByUserId[$project->user_id];
                }

                $names = json_decode($project->team_members ?? '[]', true) ?: [];
                foreach ($names as $name) {
                    $match = $studentsByName->get(strtolower(trim($name)));
                    if ($match) {
                        $linkedStudentIds[] = $match->student_id;
                    }
                }

                foreach (array_unique($linkedStudentIds) as $studentId) {
                    DB::table('course_project_student')->insertOrIgnore([
                        'course_project_id' => $project->id,
                        'student_id' => $studentId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_project_student');
    }
};