<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\ParsingDataTypeController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('/headerNavBar', [ApiController::class, 'headerNavBar'])->name('headerNavBar');
Route::get('/contacts', [ApiController::class, 'contacts'])->name('contacts');
Route::get('/newsPage', [ApiController::class, 'newsPage'])->name('newsPage');
Route::get('/news', [ApiController::class, 'news'])->name('news');

Route::prefix('v1')->group(function (): void {
    Route::prefix('/parsing')->group(function (): void {
        Route::post('/{type}', [ParsingDataTypeController::class, 'store']);

        Route::get('/{type}', [ParsingDataTypeController::class, 'index']);

        Route::get('/table/{type}', [ParsingDataTypeController::class, 'table']);
        Route::post('/chart/{type}', [ParsingDataTypeController::class, 'chartStore']);
        Route::get('/chart/{type}', [ParsingDataTypeController::class, 'chart']);

        Route::get('/type-chart/{type}', [ParsingDataTypeController::class, 'typeChart']);
        Route::get('/countries/all', [ParsingDataTypeController::class, 'countries']);

        Route::get('/new-type-chart/{type}', [ParsingDataTypeController::class, 'newTypeChart']);
    });
});
