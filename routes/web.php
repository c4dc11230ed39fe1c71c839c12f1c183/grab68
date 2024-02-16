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
        Route::get('ty-gia-68-market/{type}', 'getTyGia68MarketPrice')->where('type', 'market|emoney|usdt')->name('ty-gia-68-price');
        Route::get('ty-gia-68-gold', 'getTyGia68GoldPrice')->name('ty-gia-68-gold');
        Route::get('ty-gia-68-nice', 'getTyGia68NicePrice')->name('ty-gia-68-nice');
        Route::get('ty-gia-68-vcb', 'getTyGia68VcbPrice')->name('ty-gia-68-vcb');
    });
