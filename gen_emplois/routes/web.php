<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfesseurController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\SalleController;
use App\Http\Controllers\TimeTableController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);


Route::resource("professeurs", ProfesseurController::class);
Route::resource("modules", ModuleController::class);
Route::resource("classes", ClasseController::class);
Route::resource("salles", SalleController::class);


Route::prefix('/dashboard')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/timetables', [AdminController::class, 'showTimeTables'])->name('admin.timetables');
    Route::post('/generate-timetable', [TimeTableController::class, 'generate'])->name('admin.generate');
    Route::get('/timetables/export/{classe}', [AdminController::class, 'export'])->name('admin.timetables.export');
});
