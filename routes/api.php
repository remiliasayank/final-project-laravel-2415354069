<?php

use App\Http\Controllers\Api\ServiceController;
Route::apiResource("services", ServiceController::class);
Route::patch("services/{service}/activate", [
 ServiceController::class,
 "activate",
]);
Route::patch("services/{service}/deactivate", [
 ServiceController::class,
 "deactivate",
]);
