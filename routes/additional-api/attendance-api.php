<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttendanceController;
use Illuminate\Http\Request;

Route::get('attendance', function () {
    return response(['data' => 'success'], 200);
});

Route::get('attendance/store', [AttendanceController::class, 'store_attendance']);
Route::get('get-image', function (Request $request) {
    return asset($request->image);
});
