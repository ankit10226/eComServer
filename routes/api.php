<?php

use Illuminate\Support\Facades\Route;

Route::get('/user-register', function () {
    // dd('hit');
    return response()->json(["status"=>true,"message"=>"test running"]);
});
