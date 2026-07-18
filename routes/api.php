<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;

// Endpoint untuk data berita (GNews API) dan Sentiment Analysis sesuai spesifikasi proyek
Route::get('/news', [NewsController::class, 'getNews']);