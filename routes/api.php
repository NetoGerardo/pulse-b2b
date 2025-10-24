<?php

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

//NEW

Route::post('/webhook/leads/store', [App\Http\Controllers\Api\LeadsWebhookController::class, 'store']);

Route::post('/webhook/container/store', [App\Http\Controllers\Api\LeadsWebhookController::class, 'storeContainer']);




Route::post('/wp/leads/store', [App\Http\Controllers\Api\LeadController::class, 'store']);

Route::post('/planilha/leads/store', [App\Http\Controllers\Api\LeadController::class, 'storePlanilhas']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
