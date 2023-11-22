<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MatsuyamaWeatherDataController;

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

Route::get('/', [MatsuyamaWeatherDataController::class, 'top']);
Route::post('/', [MatsuyamaWeatherDataController::class, 'weatherDataStore'])->name('weather.data.store');