<?php

use App\Http\Controllers\API\V1\ParcelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->name('v1.')->middleware('auth:sanctum')->group(function () {
    Route::prefix('parcels')->name('parcels.')->controller(ParcelController::class)->group(function () {
        Route::post('/', 'store')->name('store')->middleware('ability:parcels-create');
        Route::patch('{parcel}/cancel', 'cancel')->name('cancel')->middleware('ability:parcels-cancel');
        Route::get('pending', 'pending')->name('pending')->middleware('ability:parcels-pending');
        Route::get('my', 'my')->name('my')->middleware('ability:parcels-my');
        Route::patch('{parcel}/assign', 'assign')->name('assign')->middleware('ability:parcels-assign');
    });
});
