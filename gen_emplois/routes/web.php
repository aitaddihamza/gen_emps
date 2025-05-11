<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfesseurController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\SalleController;
use App\Http\Controllers\TimeTableController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfController;

Route::get('/', function () {
    if(auth()->check()){
        $userRole = auth()->user()->role;
        if($userRole == 'admin'){
            return redirect('/admin/dashboard');
        } else {
            return redirect('/prof');
        }
    }
   return redirect('/login');
    
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource("professeurs", ProfesseurController::class)->middleware(['auth', 'verified', 'adminaccess']);
Route::resource("modules", ModuleController::class)->middleware(['auth', 'verified', 'adminaccess']);
Route::resource("classes", ClasseController::class)->middleware(['auth', 'verified', 'adminaccess']);
Route::resource("salles", SalleController::class)->middleware(['auth', 'verified', 'adminaccess']);


Route::prefix('/admin/dashboard')->middleware(['auth', 'verified', 'adminaccess'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/timetables', [AdminController::class, 'showTimeTables'])->name('admin.timetables');
    Route::post('/generate-timetable', [TimeTableController::class, 'generate'])->name('admin.generate');
    Route::get('/timetables/export/{classe}', [AdminController::class, 'export'])->name('admin.timetables.export');
});

Route::prefix('/prof/')->controller(ProfController::class)->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', 'index')->name('prof.index');
    Route::get('/export/', 'export')->name('prof.timetable.export');
});

require __DIR__.'/auth.php';
