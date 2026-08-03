use App\Http\Controllers\StudentController;

Route::middleware('auth')->group(function () {
    Route::resource('students', StudentController::class)->except(['index']);
});
