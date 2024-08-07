<?php

use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\SurveyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\TeamController;

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
Route::get('/activate-invite-team', [TeamController::class, 'inviteteamActivate']);
Route::get('/activate-volunteer', [TeamController::class, 'volunteerActivate']);
Route::get('/pages/{id}', [PageController::class, 'getPageData']);
Route::get('/contact-us', [PageController::class, 'getcontactUsData']);
Route::post('/contact-us-form', [PageController::class, 'contactUsStore']);
Route::post('/donation-form', [PageController::class, 'donationDataStore']);
Route::post('/newsletter-sent', [NewsletterController::class, 'subscribenewsletter']);

// Protected routes with auth:api middleware
Route::middleware('auth:api')->group(function () {
    // Add your protected routes here
    Route::get('user', function (Request $request) {
        return $request->user();
    });

    //Forgot Password Api
    Route::post('forgot-password', [ApiAuthController::class, 'forgotPasswordSendMail']);
    Route::post('/reset-password/{userid}', [ApiAuthController::class, 'resetPassword']);

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

    //Team Api
    Route::get('/team/{userid}', [TeamController::class, 'index']);
    Route::post('team-add/', [TeamController::class, 'store']);
    Route::get('team-edit/{id}', [TeamController::class, 'edit']);
    Route::post('team-update', [TeamController::class, 'update']);
    Route::post('team-delete', [TeamController::class, 'destroy']);
    Route::post('invite-team', [TeamController::class, 'inviteTeam']);
    Route::get('list-members/{id}', [TeamController::class, 'listMember']);

    //Survey Api
    Route::get('/survey/{userid}', [SurveyController::class, 'index']);
    Route::post('survey-add/', [SurveyController::class, 'store']);
    Route::get('survey-edit/{id}', [SurveyController::class, 'edit']);
    Route::post('survey-update', [SurveyController::class, 'update']);
    Route::post('survey-delete', [SurveyController::class, 'destroy']);
    Route::post('survey-questionadd/{id}', [SurveyController::class, 'addSurveyQuestion']);
    Route::get('edit-survey-questions/{surveyid}/{id}', [SurveyController::class, 'editSurveyQuestion']);
    Route::post('update-survey-questions/{surveyid}/{id}', [SurveyController::class, 'updateSurveyQuestion']);
    Route::post('delete-survey-questions/{surveyid}/{id}', [SurveyController::class, 'deleteSurveyQuestion']);
    Route::get('survey-questions/{id}', [SurveyController::class, 'surveyQuestionsList']);
    Route::get('/feedback-questions-list/{userid}', [SurveyController::class, 'feedbackQuestionsList']);
    Route::post('/survey-reply', [SurveyController::class, 'surveyReply']);

    //Notification Api
    Route::get('/notifications/{userid}', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/status', [NotificationController::class, 'changeSingleNotifyStatus']);
    Route::post('/notifications/status/admin', [NotificationController::class, 'changeNotifyStatusAdmin']);

    //Dashboard Api
    Route::get('/dashboard/get-data/{userid}', [DashboardController::class, 'getDashData']);
});
