<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    return view('home');
})->name('home');
Route::get('/registrasi_keluarga', function () {
    return view('registrasi_keluarga');
})->name('registrasi');

Route::get('/dashboard', function () {
    return view('home');
})->name('dashboard');

Route::get('/form_registrasi', function () {
    return view('form_registrasi');
})->name('form_registrasi');

Route::get('/pembayaran', function () {
    return view('pembayaran');
})->name('pembayaran');;

Route::get('/house', function () {
    return view('house');
})->name('house');

// API Routes
Route::get('/api/house', function () {
    try {
        $response = Http::get('http://127.0.0.1:8000/api/houses');
        return $response->json();
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to fetch houses'], 500);
    }
});

Route::get('/api/occupant', function () {
    try {
        $response = Http::get('http://127.0.0.1:8000/api/occupants');
        return $response->json();
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to fetch occupants'], 500);
    }
});
