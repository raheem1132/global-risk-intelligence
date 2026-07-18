<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;

Route::get('/', function () {
    return view('welcome');
});

// Rute Dashboard Berita Premium
Route::get('/dashboard/news', [NewsController::class, 'getNews']);