use App\Http\Controllers\StudentController;
use App\Http\Controllers\SupervisorController;

Route::middleware('auth')->group(function () {
    Route::resource('students', StudentController::class)->except(['index']);
    Route::resource('supervisors', SupervisorController::class)->except(['index']);
});
