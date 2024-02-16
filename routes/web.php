<?php

use App\Http\Controllers\EndPointTestController;
use App\Http\Controllers\Grab68Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->away('https://google.com');
});

Route::controller(EndPointTestController::class)
    ->prefix('endpoint-test')
    ->as('endpoint-test.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('json', function (Request $request) {
            return response()->json($request->server());
        })->name('json');
    });

Route::controller(Grab68Controller::class)
    ->prefix('grab')
    ->as('grab.')
    ->group(function () {
        Route::get('endpoint-test-guzzle', 'testEndpointWithGuzzle')->name('endpoint-test-guzzle');
        Route::get('ty-gia-68-market-price', 'getTyGia68MarketPrice')->name('ty-gia-68-market-price');
        Route::get('ty-gia-68-e-currency-price', 'getTyGia68eCurrencyPrice')->name('ty-gia-68-e-currency-price');
    });
