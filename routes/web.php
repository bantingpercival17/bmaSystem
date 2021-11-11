<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__ . '/auth.php';
Route::get('/', [Controller::class, 'index']);
Route::get('/setup', [Controller::class, 'setup']);
Route::post('/setup', [Controller::class, 'setup_store']);

require __DIR__ . '/custom-route/administrator.php'; // Administrator Route
require __DIR__ . '/custom-route/administrative.php'; // Administrative Route
require __DIR__ . '/custom-route/accounting.php'; // Accounting Route
require __DIR__ . '/custom-route/teacher.php'; // Teacher Route
require __DIR__ . '/custom-route/executive.php'; // Teacher Route

