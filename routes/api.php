<?php

use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\Api\SurvivorController;
use App\Http\Controllers\API\TradeController;
use Illuminate\Http\Request;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/reports', [ReportController::class, 'index']);
Route::post("/survivors", [SurvivorController::class, 'createSurvivor']);
Route::get("/survivors/{survivor_id}", [SurvivorController::class, 'getSurvivor']);
Route::post("/survivors/createContaminationSurvivor", [SurvivorController::class, 'createContaminationSurvivor']);
Route::patch("/survivors/{survivor_id}/last_location", [SurvivorController::class, 'updateLastLocationSurvivor']);
Route::post("/trades", [TradeController::class, 'createTrade']);
