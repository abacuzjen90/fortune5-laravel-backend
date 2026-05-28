<?php

use Illuminate\Support\Facades\Route;

Route::get('/restricted', function () {
    return view('restricted');
})->name('restricted.page');

Route::get('/', function () {
    return view('restricted');
});


