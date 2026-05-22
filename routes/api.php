<?php

use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\CustomerController;

Route::apiResource("customers", CustomerController::class);
Route::apiResource("services", ServiceController::class);
Route::patch("services/{service}/activate", [
 ServiceController::class,
 "activate",
]);
Route::patch("services/{service}/deactivate", [
 ServiceController::class,
 "deactivate",
]);
