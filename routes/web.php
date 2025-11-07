<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StripeController;
use Illuminate\Routing\Middleware\ThrottleRequests;

Route::get('/', function () {
    return view('welcome');
});



