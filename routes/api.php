<?php

use App\Http\Controllers\AntrenmanProgramsController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public (korumasız) rotalar
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/login',[AuthController::class,'loginResponse'])->name('login');
// Auth gerektiren rotalar
Route::middleware(['auth:api'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/program',[AntrenmanProgramsController::class,'create'])->name('create-program')->middleware('role:ANTRENOR,ADMIN');
    Route::get('/programs',[AntrenmanProgramsController::class,'list'])->name('list-programs')->middleware('role:ANTRENOR,ADMIN');
    Route::get('/program/{id}',[AntrenmanProgramsController::class,'get'])->name('get-program')->middleware('role:ANTRENOR,SPORCU,ADMIN');
    Route::put('/program/{id}',[AntrenmanProgramsController::class,'update'])->name('update-program')->middleware('role:ANTRENOR,ADMIN');
    Route::delete('/program/{id}',[AntrenmanProgramsController::class,'delete'])->name('delete-program')->middleware('role:ANTRENOR,ADMIN');
    Route::post('/program/assign/{id}',[AntrenmanProgramsController::class,'assign'])->name('assign-program')->middleware('role:ANTRENOR,ADMIN');
});

