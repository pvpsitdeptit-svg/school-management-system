<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-auth', function () {
    return view('test_auth');
});

Route::get('/test-attendance', function () {
    return view('test_attendance');
});

Route::get('/test-exams', function () {
    return view('test_exams');
});
