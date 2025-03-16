<?php

use App\Http\Controllers\ClasseController;
use App\Http\Controllers\ProfesseurController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\SalleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::resource("professeurs", ProfesseurController::class);
Route::resource("modules", ModuleController::class);
Route::resource("classes", ClasseController::class);
Route::resource("salles", SalleController::class);
