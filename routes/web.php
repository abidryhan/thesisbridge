<?php

use App\Http\Controllers\CourseProjectController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\SupervisorMatchController;
use App\Http\Controllers\ThesisGroupController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('course-projects', CourseProjectController::class)->only(['index']);

Route::middleware('auth')->group(function () {
    Route::resource('students', StudentController::class)->except(['index']);
    Route::resource('supervisors', SupervisorController::class)->except(['index']);
    Route::resource('course-projects', CourseProjectController::class)->except(['index', 'show']);
    Route::resource('thesis-groups', ThesisGroupController::class);
    Route::resource('proposals', ProposalController::class)->only(['create', 'store', 'show']);
    Route::resource('thesis-groups.milestones', MilestoneController::class)->only(['create', 'store']);
    Route::resource('thesis-groups.milestones.documents', DocumentController::class)->only(['index', 'create', 'store']);
    Route::resource('thesis-groups.meetings', MeetingController::class)->only(['index', 'create', 'store']);
    Route::resource('thesis-groups.milestones.feedback', FeedbackController::class)->only(['index', 'create', 'store']);
    Route::patch('thesis-groups/{thesis_group}/supervisor', [ThesisGroupController::class, 'chooseSupervisor'])
        ->name('thesis-groups.choose-supervisor');
    Route::post('/proposals/{proposal}/resubmit', [ProposalController::class, 'resubmit'])->name('proposals.resubmit');
    Route::post('/proposals/{proposal}/start-review', [ProposalController::class, 'startReview'])->name('proposals.start-review');
    Route::post('/proposals/{proposal}/approve', [ProposalController::class, 'approve'])->name('proposals.approve');
    Route::post('/proposals/{proposal}/request-revision', [ProposalController::class, 'requestRevision'])->name('proposals.request-revision');
    Route::post('/proposals/{proposal}/reject', [ProposalController::class, 'reject'])->name('proposals.reject');   

    Route::get('thesis-groups/{thesis_group}/supervisor-matches', [SupervisorMatchController::class, 'index'])
        ->name('thesis-groups.supervisor-matches');
});

Route::resource('course-projects', CourseProjectController::class)->only(['show']);

require __DIR__.'/auth.php';
