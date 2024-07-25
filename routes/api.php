<?php

use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\PageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\RoleController;

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

// Public routes
Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);
Route::get('/activate', [ApiAuthController::class, 'activate']);
Route::get('/pages/{id}', [PageController::class, 'getPageData']);

// Protected routes with auth:api middleware
Route::middleware('auth:api')->group(function () {
    // Add your protected routes here
    Route::get('user', function (Request $request) {
        return $request->user();
    });

    // Change password route
    Route::post('change-password', [ApiAuthController::class, 'changePassword']);

    // Logout route
    Route::post('logout', [ApiAuthController::class, 'logout']);

    Route::get('editprofile/{id}', [ApiAuthController::class, 'getProfile']);
    Route::post('updateprofile', [ApiAuthController::class, 'updateProfile']);

    //Blog Api
    Route::get('/blogs/{userid}', [BlogController::class, 'index']);
    Route::post('blogs-add/', [BlogController::class, 'store']);
    Route::get('blogs-edit/{id}', [BlogController::class, 'edit']);
    Route::post('blogs-update', [BlogController::class, 'update']);
    Route::post('blogs-delete', [BlogController::class, 'destroy']);
    Route::post('blogs-status', [BlogController::class, 'updateStatus']);
    Route::post('blog-category-add/', [BlogController::class, 'saveBlogcategory']);
    
    //Event Api
    Route::get('/event/{userid}', [EventController::class, 'index']);
    Route::post('event-add/', [EventController::class, 'store']);
    Route::get('event-edit/{id}', [EventController::class, 'edit']);
    Route::post('event-update', [EventController::class, 'update']);
    Route::post('event-delete', [EventController::class, 'destroy']);
    Route::post('event-status', [EventController::class, 'updateStatus']);

    //Role List Api
    Route::get('/rolelist/{userid}', [RoleController::class, 'index']);

    //Member Api
    Route::get('/member/{userid}', [MemberController::class, 'index']);
    Route::post('member-add/', [MemberController::class, 'store']);
    Route::get('member-edit/{id}', [MemberController::class, 'edit']);
    Route::post('member-update', [MemberController::class, 'update']);
    Route::post('member-delete', [MemberController::class, 'destroy']);

});
