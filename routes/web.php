<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

Route::any('/', function () {
    return response([
        'dt' => Carbon::now(),
        'app' => 'Party App',
        'version' => '1.0.0.0'
    ], 200);
});
