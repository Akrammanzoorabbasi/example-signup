<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

Route::view('/signup', 'signup');
Route::view('/login', 'login');
Route::view('/forgot-password', 'forgot_password');
Route::view('/reset-password/{token}', 'reset_password')->name('password.reset');

