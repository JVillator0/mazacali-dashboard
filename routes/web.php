<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->to('/admin');
});


Route::get('/health-check', function () {
    return response()->json(['status' => 'ok']);
});
