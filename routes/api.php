<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LinksController;

#routes that does not require authentication and method are in auth controller
Route::controller(AuthController::class)->group(function () {
    //register route 
    Route::post('/register', 'register')->name('user.register');
    Route::post('/login', 'login')->name('user.login');
});

#routes needs user to be authenticated
Route::middleware(['auth:sanctum'])->group(function () {
    //logging out currrent user
    Route::post('/logout', [AuthController::class, 'logout'])->name('user.logout');
    //links handling 
    Route::controller(LinksController::class)->group(function () {
        Route::post('/store', 'storeLink')->name('link.store');
        Route::delete('/link/{id}', 'destroy')->name('link.destroy');
        Route::get('/links' , 'index')->name('links.index');
    });
});
