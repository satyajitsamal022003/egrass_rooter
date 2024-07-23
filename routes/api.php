<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Public routes
Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);
Route::get('/activate', [ApiAuthController::class, 'activate']);

// Protected routes with auth:sanctum middleware
Route::middleware('auth:sanctum')->group(function () {
    // Add your protected routes here
    Route::get('user', function (Request $request) {
        return $request->user();
    });

    // Change password route
    Route::post('change-password', [ApiAuthController::class, 'changePassword']);

    // logout route
    Route::post('logout', [ApiAuthController::class, 'logout']);

    
    Route::get('editprofile/{id}', [ApiAuthController::class, 'getProfile']);
    Route::post('updateprofile', [ApiAuthController::class, 'updateProfile']);
});
