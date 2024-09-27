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
use App\Http\Controllers\Api\PollingAgentController;
use App\Http\Controllers\Api\ElectionCampaignController;
use App\Http\Controllers\Api\BulkEmailController;
use App\Http\Controllers\Api\ElectionResultController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\BulkSmsController;
use App\Http\Controllers\Api\CanvassingController;
use App\Http\Controllers\Api\SocialmediaController;
use App\Http\Controllers\Api\UpcomingElectionController;

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
Route::get('/getcampaign', [ApiAuthController::class, 'getcampaign']); //satyajit
Route::post('register/get-slug/{slug}', [ApiAuthController::class, 'getslug']); //satyajit
Route::get('register/check-email/{email}', [ApiAuthController::class, 'checkemail']); //satyajit
Route::get('/getsenatorial-states/{stateid}', [ApiAuthController::class, 'getsenatorialstates']); //satyajit
Route::get('/getfederal-constituency/{stateid}', [ApiAuthController::class, 'getfederalconstituency']); //satyajit
Route::get('/getlocal-constituency/{stateid}', [ApiAuthController::class, 'getlocalconstituency']); //satyajit
Route::post('/login', [ApiAuthController::class, 'login']);
Route::get('/activate', [ApiAuthController::class, 'activate']);
Route::get('/activate-invite-team', [TeamController::class, 'inviteteamActivate']);
Route::get('/activate-volunteer', [TeamController::class, 'volunteerActivate']);
Route::get('/pages/{id}', [PageController::class, 'getPageData']);

//News and news details
Route::get('/news-details/{slug}', [NewsController::class, 'newsdetails']);
Route::get('/news', [NewsController::class, 'news']);
Route::post('/updateandnews-subscription', [NewsController::class, 'latestupdateandnews']);

//Forgot Password Api
Route::post('forgot-password', [ApiAuthController::class, 'forgotPasswordSendMail']);

Route::post('/reset-password/{userid}', [ApiAuthController::class, 'resetPassword']);

//Home Page CMS
Route::get('/get-homedata', [PageController::class, 'getHomePageData']);
Route::get('/get-all-blogs', [PageController::class, 'getallblogs']);

// Stay Update with Us
Route::post('/stay-update/with-us', [PageController::class, 'stayupdatewithus']);

//About Us Page CMS
Route::get('/get-aboutdata', [PageController::class, 'getAboutUsPageData']);
Route::get('/get-allparties', [PageController::class, 'allparties']);

// Contact Us Page
Route::get('/contact-us', [PageController::class, 'getcontactUsData']);
Route::post('/contact-us-form', [PageController::class, 'contactUsStore']);

//Newsletter
Route::post('/newsletter-sent', [NewsletterController::class, 'subscribenewsletter']);

// Blog Cms Page
Route::get('/get-blogs', [BlogController::class, 'getAllBlogs']);
Route::get('/categories', [BlogController::class, 'getBlogCategories']);
Route::get('/latest-blogs', [BlogController::class, 'getLatestBlogs']);
Route::get('/popular-blogs', [BlogController::class, 'getPopularBlogs']);
Route::get('/recent-blogs', [BlogController::class, 'getRecentBlogs']);
Route::get('/related-blogs/{id}', [BlogController::class, 'getRelatedBlogs']);
Route::get('/blog-details/{id}', [BlogController::class, 'getBlogDetails']);

//Site Setting Data
Route::get('/get-sitedata', [PageController::class, 'getSiteData']);

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

    Route::get('getprofile', [ApiAuthController::class, 'getProfiledata']);
    Route::get('editprofile/{id}', [ApiAuthController::class, 'getProfile']);
    Route::post('updateprofile', [ApiAuthController::class, 'updateProfile']);

    //Blog Api
    Route::get('/blogs', [BlogController::class, 'index']);
    Route::post('blogs-add/', [BlogController::class, 'store']);
    Route::get('blogs-edit/{id}', [BlogController::class, 'edit']);
    Route::get('blogs-category/', [BlogController::class, 'blogscategory']);
    Route::post('blogs-update', [BlogController::class, 'update']);
    Route::post('blogs-delete', [BlogController::class, 'destroy']);
    Route::post('blogs-status', [BlogController::class, 'updateStatus']);
    Route::post('blog-category-add/', [BlogController::class, 'saveBlogcategory']);

    //Event Api
    Route::get('event/', [EventController::class, 'index']);
    Route::post('event-add/', [EventController::class, 'store']);
    Route::get('event-edit/{id}', [EventController::class, 'edit']);
    Route::post('event-update', [EventController::class, 'update']);
    Route::post('event-delete', [EventController::class, 'destroy']);
    Route::post('event-status', [EventController::class, 'updateStatus']);

    //Role List Api
    Route::get('/rolelist', [RoleController::class, 'index']);

    //Member Api
    Route::get('member/', [MemberController::class, 'index']);
    Route::get('add-member/', [MemberController::class, 'addmember']);
    Route::get('member/get-senatorialstates/{stateid}', [MemberController::class, 'getsenatorialstates']);
    Route::get('member/get-lga/{stateid}', [MemberController::class, 'getlga']);
    Route::get('member/get-ward/{lgaid}', [MemberController::class, 'getward']);
    Route::get('member/get-pollingunit/{wardid}', [MemberController::class, 'getpu']);
    Route::post('member-add/', [MemberController::class, 'store']);
    Route::get('member-edit/{id}', [MemberController::class, 'edit']);
    Route::post('member-update', [MemberController::class, 'update']);
    Route::post('member-delete', [MemberController::class, 'destroy']);
    Route::get('/contacts-download-csv', [MemberController::class, 'bulkcontactupload']);
    Route::post('/contacts-upload-members', [MemberController::class, 'bulkUpload']);


    //Team Api
    Route::get('team/', [TeamController::class, 'index']);
    Route::post('team-add/', [TeamController::class, 'store']);
    Route::get('team-edit/{id}', [TeamController::class, 'edit']);
    Route::get('team-members-view/{id}', [TeamController::class, 'view']);
    Route::post('team-update/{id}', [TeamController::class, 'update']);
    Route::post('team-delete/{id}', [TeamController::class, 'destroy']);
    Route::post('invite-team', [TeamController::class, 'inviteTeam']);
    Route::get('list-members/{id}', [TeamController::class, 'listMember']);

    //Survey Api
    Route::get('survey/', [SurveyController::class, 'index']);
    Route::post('survey-add/', [SurveyController::class, 'store']);
    Route::get('survey-edit/{id}', [SurveyController::class, 'edit']);
    Route::post('survey-update', [SurveyController::class, 'update']);
    Route::post('survey-delete', [SurveyController::class, 'destroy']);
    Route::post('survey-questionadd/{id}', [SurveyController::class, 'addSurveyQuestion']);
    Route::get('edit-survey-questions/{surveyid}/{id}', [SurveyController::class, 'editSurveyQuestion']);
    Route::post('update-survey-questions/{surveyid}/{id}', [SurveyController::class, 'updateSurveyQuestion']);
    Route::post('delete-survey-questions/{surveyid}/{id}', [SurveyController::class, 'deleteSurveyQuestion']);
    Route::get('survey-questions/{id}', [SurveyController::class, 'surveyQuestionsList']);
    Route::get('feedback-questions-list/', [SurveyController::class, 'feedbackQuestionsList']);
    Route::post('/survey-reply', [SurveyController::class, 'surveyReply']);

    //Notification Api
    Route::get('notifications/', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/status', [NotificationController::class, 'changeSingleNotifyStatus']);
    Route::post('/notifications/status/admin', [NotificationController::class, 'changeNotifyStatusAdmin']);

    //Dashboard Api
    Route::get('dashboard/get-data/', [DashboardController::class, 'getDashData']);
    Route::post('dashboard/get-political-zones/', [DashboardController::class, 'getpoliticalzones']);
    Route::get('grassrooter-data/', [DashboardController::class, 'getgrassrooterdata']);
    Route::get('statecounts/{id}', [DashboardController::class, 'getStateCounts']);
    Route::get('lgacounts/{id}', [DashboardController::class, 'getLgaCounts']);

    //polling Agent Api
    Route::get('/polling-agent-list', [PollingAgentController::class, 'pollingagentlist']);
    Route::post('/polling-agent', [PollingAgentController::class, 'storepollingagent']);
    Route::post('/polling-agent-email/vin', [PollingAgentController::class, 'pollingagentemailvin']);
    Route::get('/edit-polling-agent/{pollingagentid}', [PollingAgentController::class, 'editpollingagent']);
    Route::get('/polling-agent-getlga/{stateid}', [PollingAgentController::class, 'pollingagentgetlga']);
    Route::get('/polling-agent-getward/{lgaid}', [PollingAgentController::class, 'pollingagentgetward']);
    Route::post('/polling-agent-update/{pollingagentid}', [PollingAgentController::class, 'pollingagentupdate']);

    //Election Campaign Api
    Route::get('/election-campaign', [ElectionCampaignController::class, 'electioncampaignlist']);
    Route::get('/election-campaign/states/{campaigntype}', [ElectionCampaignController::class, 'electioncampaignstatewise']);
    Route::get('/election-campaign/lga/{stateid}', [ElectionCampaignController::class, 'statewiselga']);
    Route::get('/election-campaign/ward/{lgaid}', [ElectionCampaignController::class, 'lgawiseward']);
    Route::get('/election-campaign/polling-unit/{wardid}', [ElectionCampaignController::class, 'wardwisepu']);

    //Bulk Email Api
    Route::get('/send-email/role-type', [BulkEmailController::class, 'getroletype']);
    Route::get('/contact-members/{role}', [BulkEmailController::class, 'getcontactmembers']);
    Route::post('/send-email/team-members', [BulkEmailController::class, 'emailToTeamMembers']);

    //Bulk SMS Api
    Route::get('/send-sms/role-type', [BulkSmsController::class, 'getroletype']);
    Route::get('/sms-contact-members/{role}', [BulkSmsController::class, 'getcontactmembers']);
    Route::post('/send-bulk-sms', [BulkSmsController::class, 'sendBulkSms']);

    //Election Results Api
    Route::get('/election-results', [ElectionResultController::class, 'electionresults']);
    Route::post('/getyear-electiontype', [ElectionResultController::class, 'getyearontype']);
    Route::post('/filter/election-results', [ElectionResultController::class, 'filterelectionresults']);
    Route::post('/get-statevote-tooltip', [ElectionResultController::class, 'getstatevotetooltip']);
    Route::post('/get-statevote-onclick', [ElectionResultController::class, 'getstatevoteonregionclick']); //pending

    // Canvassing Api
    Route::post('/get-survey-response', [CanvassingController::class, 'getsurveyresponse']);
    Route::post('/get-response-of-roletype', [CanvassingController::class, 'getresponseonroletypes']);
    Route::post('/filter-electionresults', [CanvassingController::class, 'filterelectionresults']);
    Route::get('/postive-grassrooters', [CanvassingController::class, 'getPositiveReviews']);


    Route::post('/verify-token', [ApiAuthController::class, 'verifytoken']);


    //Social Media
    Route::get('/whatsapp-message', [SocialmediaController::class, 'whatsappmessage']);

});
//Upcoming Elections Api
Route::get('/upcoming-elections', [UpcomingElectionController::class, 'upcomingelection']);
Route::get('/homepage-layout', [ApiAuthController::class, 'homepagelayout']);

//payment api
Route::post('/donation/initiate', [PageController::class, 'donationDataStore']);
Route::get('/paystack/callback', [PageController::class, 'handlePaystackCallback'])->name('paystack.callback');
