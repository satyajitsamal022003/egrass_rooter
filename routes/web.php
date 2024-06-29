<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ManagecampaignController;
use App\Http\Controllers\Admin\ManageblogController;
use App\Http\Controllers\Admin\ManageblogcategoryController;
use App\Http\Controllers\Admin\ManageblogtagController;
use App\Http\Controllers\Admin\DashboardmenuController;
use App\Http\Controllers\Admin\ManagepartyController;
use App\Http\Controllers\Admin\ManagestateController;
use App\Http\Controllers\Admin\ManagesenatorialdistrictController;
use App\Http\Controllers\Admin\ManagefederalconstituencyController;
use App\Http\Controllers\Admin\ManagetestimonialController;
use App\Http\Controllers\Admin\ManagestateconstituencyController;
use App\Http\Controllers\Admin\ManagelgaController;
use App\Http\Controllers\Admin\ManagewardController;
use App\Http\Controllers\Admin\ManagesitecontController;
use App\Http\Controllers\Admin\ManagepollingunitController;
use App\Http\Controllers\Admin\ManageSociallinkController;
use App\Http\Controllers\Admin\ManageFeatureController;
use App\Http\Controllers\Admin\AdminmenuController;
use App\Http\Controllers\Admin\ManageclientController;
use App\Http\Controllers\Admin\ManagecampaignorganizationController;
use App\Http\Controllers\Admin\ManageQuickSoftwareController;
use App\Http\Controllers\Admin\ManageserviceController;
use App\Http\Controllers\Admin\ManageFaqController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ManageTeamController;
use App\Http\Controllers\Admin\ManageuserController;
use App\Http\Controllers\Admin\ManagemenuController;
use App\Http\Controllers\Admin\ManageelectioncountController;
use App\Http\Controllers\Admin\ManageadminController;
use App\Http\Controllers\Admin\ManageelectionresultController;






use Illuminate\Support\Facades\Route;

use function PHPUnit\Framework\returnSelf;

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
   return redirect('/admin/login');
});

// Route::get('/registration', function () {
//     return view('registration');
// });



Route::prefix('admin')->middleware('auth')->group(function () {
  Route::get('/dashboard', [DashboardController::class, 'index'])
  ->middleware(['verified'])
  ->name('admin.dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

     // pages
     Route::get('/manage-pages/index', [PagesController::class, 'index'])->name('pages.index');
     Route::get('/pages/create', [PagesController::class, 'create'])->name('pages.create');
     Route::post('/pages/store', [PagesController::class, 'store'])->name('pages.store');
     Route::any('/pages/destroy/{id}', [PagesController::class, 'destroy'])->name('pages.destroy');
     Route::any('/pages/edit/{id}', [PagesController::class, 'edit'])->name('pages.edit');
     Route::post('/pages/update/{id}', [PagesController::class, 'update'])->name('pages.update');
     Route::post('/pages/status-update', [PagesController::class, 'status'])->name('pages.status');



     //manage campaign list
     Route::get('/manage-campaign/list', [ManagecampaignController::class, 'list'])->name('managecampaign.list');
     Route::any('/manage-campaign/view/{id}', [ManagecampaignController::class, 'view'])->name('managecampaign.view');

      //Manage blog 
      Route::get('/manage-blog/list', [ManageblogController::class, 'list'])->name('manageblog.list');
      Route::get('/manage-blog/filter', [ManageblogController::class, 'filter'])->name('manageblog.filter');
      Route::any('/manage-blog/comment-list/{id}', [ManageblogController::class, 'commentlist'])->name('manageblog.commentlist');
      Route::get('/manage-blog/create', [ManageblogController::class, 'create'])->name('manageblog.create');
      Route::post('/manage-blog/store', [ManageblogController::class, 'store'])->name('manageblog.store');
      Route::any('/manage-blog/edit/{id}', [ManageblogController::class, 'edit'])->name('manageblog.edit');
      Route::post('/manage-blog/update/{id}', [ManageblogController::class, 'update'])->name('manageblog.update');
      Route::any('/manage-blog/destroy/{id}', [ManageblogController::class, 'destroy'])->name('manageblog.destroy');
      Route::post('/manage-blog/status', [ManageblogController::class, 'status'])->name('manageblog.status');

    //Manage blog category
    Route::get('/manage-blog-category/list', [ManageblogcategoryController::class, 'list'])->name('manageblogcat.list');
    Route::get('/manage-blog-category/create', [ManageblogcategoryController::class, 'create'])->name('manageblogcat.create');
    Route::post('/manage-blog-category/store', [ManageblogcategoryController::class, 'store'])->name('manageblogcat.store');
    Route::any('/manage-blog-category/edit/{id}', [ManageblogcategoryController::class, 'edit'])->name('manageblogcat.edit');
    Route::post('/manage-blog-category/update/{id}', [ManageblogcategoryController::class, 'update'])->name('manageblogcat.update');
    Route::any('/manage-blog-category/destroy/{id}', [ManageblogcategoryController::class, 'destroy'])->name('manageblogcat.destroy');
    Route::post('/manage-blog-category/status', [ManageblogcategoryController::class, 'status'])->name('manageblogcat.status');

    //Manage blog tag
    Route::get('/manage-blog-tag/list', [ManageblogtagController::class, 'list'])->name('manageblogtag.list');
    Route::get('/manage-blog-tag/create', [ManageblogtagController::class, 'create'])->name('manageblogtag.create');
    Route::post('/manage-blog-tag/store', [ManageblogtagController::class, 'store'])->name('manageblogtag.store');
    Route::any('/manage-blog-tag/edit/{id}', [ManageblogtagController::class, 'edit'])->name('manageblogtag.edit');
    Route::post('/manage-blog-tag/update/{id}', [ManageblogtagController::class, 'update'])->name('manageblogtag.update');
    Route::any('/manage-blog-tag/destroy/{id}', [ManageblogtagController::class, 'destroy'])->name('manageblogtag.destroy');
    Route::post('/manage-blog-tag/status', [ManageblogtagController::class, 'status'])->name('manageblogtag.status');


    // Manage Testimonial
    Route::get('/manage-testimonial/list', [ManagetestimonialController::class, 'list'])->name('managetestimonial.list');
    Route::get('/manage-testimonial/create', [ManagetestimonialController::class, 'create'])->name('managetestimonial.create');
    Route::post('/manage-testimonial/store', [ManagetestimonialController::class, 'store'])->name('managetestimonial.store');
    Route::any('/manage-testimonial/edit/{id}', [ManagetestimonialController::class, 'edit'])->name('managetestimonial.edit');
    Route::post('/manage-testimonial/update/{id}', [ManagetestimonialController::class, 'update'])->name('managetestimonial.update');
    Route::any('/manage-testimonial/destroy/{id}', [ManagetestimonialController::class, 'destroy'])->name('managetestimonial.destroy');
    Route::post('/manage-testimonial/status', [ManagetestimonialController::class, 'status'])->name('managetestimonial.status');


      //manage-party
      Route::get('/manage-party/list', [ManagepartyController::class, 'list'])->name('manageparty.list');
      Route::get('/manage-party/create', [ManagepartyController::class, 'create'])->name('manageparty.create');
      Route::post('/manage-party/store', [ManagepartyController::class, 'store'])->name('manageparty.store');
      Route::any('/manage-party/edit/{id}', [ManagepartyController::class, 'edit'])->name('manageparty.edit');
      Route::post('/manage-party/update/{id}', [ManagepartyController::class, 'update'])->name('manageparty.update');
      Route::any('/manage-party/destroy/{id}', [ManagepartyController::class, 'destroy'])->name('manageparty.destroy');
      Route::get('/manage-party/upload', [ManagepartyController::class, 'upload'])->name('manageparty.upload');
      Route::post('/manage-party/status', [ManagepartyController::class, 'status'])->name('manageparty.status');



      //manage-state
      Route::get('/manage-state/list', [ManagestateController::class, 'list'])->name('managestate.list');
      Route::get('/manage-state/create', [ManagestateController::class, 'create'])->name('managestate.create');
      Route::post('/manage-state/store', [ManagestateController::class, 'store'])->name('managestate.store');
      Route::any('/manage-state/edit/{id}', [ManagestateController::class, 'edit'])->name('managestate.edit');
      Route::post('/manage-state/update/{id}', [ManagestateController::class, 'update'])->name('managestate.update');
      Route::any('/manage-state/destroy/{id}', [ManagestateController::class, 'destroy'])->name('managestate.destroy');

      //senatorial districts
        Route::get('/senatorial-districts/list', [ManagesenatorialdistrictController::class, 'list'])->name('senatorialdist.list');
        Route::get('/senatorial-districts/create', [ManagesenatorialdistrictController::class, 'create'])->name('senatorialdist.create');
        Route::post('/senatorial-districts/store', [ManagesenatorialdistrictController::class, 'store'])->name('senatorialdist.store');
        Route::any('/senatorial-districts/edit/{id}', [ManagesenatorialdistrictController::class, 'edit'])->name('senatorialdist.edit');
        Route::post('/senatorial-districts/update/{id}', [ManagesenatorialdistrictController::class, 'update'])->name('senatorialdist.update');
        Route::any('/senatorial-districts/destroy/{id}', [ManagesenatorialdistrictController::class, 'destroy'])->name('senatorialdist.destroy');

      //federal constituency
      Route::get('/federal-constituency/list', [ManagefederalconstituencyController::class, 'list'])->name('federalconst.list');
      Route::get('/federal-constituency/create', [ManagefederalconstituencyController::class, 'create'])->name('federalconst.create');
      Route::post('/federal-constituency/store', [ManagefederalconstituencyController::class, 'store'])->name('federalconst.store');
      Route::any('/federal-constituency/edit/{id}', [ManagefederalconstituencyController::class, 'edit'])->name('federalconst.edit');
      Route::post('/federal-constituency/update/{id}', [ManagefederalconstituencyController::class, 'update'])->name('federalconst.update');
      Route::any('/federal-constituency/destroy/{id}', [ManagefederalconstituencyController::class, 'destroy'])->name('federalconst.destroy');


       //State constituency 
       Route::get('/state-constituency/list', [ManagestateconstituencyController::class, 'list'])->name('stateconst.list');
       Route::get('/state-constituency/create', [ManagestateconstituencyController::class, 'create'])->name('stateconst.create');
       Route::post('/state-constituency/store', [ManagestateconstituencyController::class, 'store'])->name('stateconst.store');
       Route::any('/state-constituency/edit/{id}', [ManagestateconstituencyController::class, 'edit'])->name('stateconst.edit');
       Route::post('/state-constituency/update/{id}', [ManagestateconstituencyController::class, 'update'])->name('stateconst.update');
       Route::any('/state-constituency/destroy/{id}', [ManagestateconstituencyController::class, 'destroy'])->name('stateconst.destroy');


       //Local Govt Area 
       Route::get('/Local-government-area/list', [ManagelgaController::class, 'list'])->name('managelga.list');
       Route::get('/Local-government-area/create', [ManagelgaController::class, 'create'])->name('managelga.create');
       Route::post('/Local-government-area/store', [ManagelgaController::class, 'store'])->name('managelga.store');
       Route::any('/Local-government-area/edit/{id}', [ManagelgaController::class, 'edit'])->name('managelga.edit');
       Route::post('/Local-government-area/update/{id}', [ManagelgaController::class, 'update'])->name('managelga.update');
       Route::any('/Local-government-area/destroy/{id}', [ManagelgaController::class, 'destroy'])->name('managelga.destroy');

        //Manage Wards
        Route::get('/manage-wards/list', [ManagewardController::class, 'list'])->name('manageward.list');
        Route::get('/manage-wards/create', [ManagewardController::class, 'create'])->name('manageward.create');
        Route::post('/manage-wards/store', [ManagewardController::class, 'store'])->name('manageward.store');
        Route::any('/manage-wards/edit/{id}', [ManagewardController::class, 'edit'])->name('manageward.edit');
        Route::post('/manage-wards/update/{id}', [ManagewardController::class, 'update'])->name('manageward.update');
        Route::any('/manage-wards/destroy/{id}', [ManagewardController::class, 'destroy'])->name('manageward.destroy');

         //Manage Site Content
         Route::get('/manage-site-content/list', [ManagesitecontController::class, 'list'])->name('managesitecontent.list');
         Route::get('/manage-site-content/create', [ManagesitecontController::class, 'create'])->name('managesitecontent.create');
         Route::post('/manage-site-content/store', [ManagesitecontController::class, 'store'])->name('managesitecontent.store');
         Route::any('/manage-site-content/edit/{id}', [ManagesitecontController::class, 'edit'])->name('managesitecontent.edit');
         Route::post('/manage-site-content/update/{id}', [ManagesitecontController::class, 'update'])->name('managesitecontent.update');
         Route::any('/manage-site-content/destroy/{id}', [ManagesitecontController::class, 'destroy'])->name('managesitecontent.destroy');
         Route::post('/manage-site-content/status', [ManagesitecontController::class, 'status'])->name('managesitecontent.status');


        //  Manage Polling Units
        Route::get('/manage-polling-unit/list', [ManagepollingunitController::class, 'list'])->name('managepollings.list');
        Route::get('/manage-polling-unit/create', [ManagepollingunitController::class, 'create'])->name('managepollings.create');
        Route::post('/manage-polling-unit/store', [ManagepollingunitController::class, 'store'])->name('managepollings.store');
        Route::any('/manage-polling-unit/edit/{id}', [ManagepollingunitController::class, 'edit'])->name('managepollings.edit');
        Route::post('/manage-polling-unit/update/{id}', [ManagepollingunitController::class, 'update'])->name('managepollings.update');
        Route::any('/manage-polling-unit/destroy/{id}', [ManagepollingunitController::class, 'destroy'])->name('managepollings.destroy');
        Route::post('/manage-polling-unit/lgas', [ManagepollingunitController::class, 'getLgas'])->name('managepollings.getLgas');
        Route::post('/manage-polling-unit/getwards', [ManagepollingunitController::class, 'getwards'])->name('managepollings.getwards');
        // Route::post('/manage-polling-/status', [ManagepollingunitController::class, 'status'])->name('managepollings.status');


         //Manage Social Links
         Route::get('/manage-social-links/list', [ManageSociallinkController::class, 'list'])->name('managesociallinks.list');
         Route::get('/manage-social-links/create', [ManageSociallinkController::class, 'create'])->name('managesociallinks.create');
         Route::post('/manage-social-links/store', [ManageSociallinkController::class, 'store'])->name('managesociallinks.store');
         Route::any('/manage-social-links/edit/{id}', [ManageSociallinkController::class, 'edit'])->name('managesociallinks.edit');
         Route::post('/manage-social-links/update/{id}', [ManageSociallinkController::class, 'update'])->name('managesociallinks.update');
         Route::any('/manage-social-links/destroy/{id}', [ManageSociallinkController::class, 'destroy'])->name('managesociallinks.destroy');
         Route::post('/manage-social-links/status', [ManageSociallinkController::class, 'status'])->name('managesociallinks.status');


          //Manage Feature
          Route::get('/manage-feature/list', [ManageFeatureController::class, 'list'])->name('managefeature.list');
          Route::get('/manage-feature/create', [ManageFeatureController::class, 'create'])->name('managefeature.create');
          Route::post('/manage-feature/store', [ManageFeatureController::class, 'store'])->name('managefeature.store');
          Route::any('/manage-feature/edit/{id}', [ManageFeatureController::class, 'edit'])->name('managefeature.edit');
          Route::post('/manage-feature/update/{id}', [ManageFeatureController::class, 'update'])->name('managefeature.update');
          Route::any('/manage-feature/destroy/{id}', [ManageFeatureController::class, 'destroy'])->name('managefeature.destroy');
          Route::post('/manage-feature/status', [ManageFeatureController::class, 'status'])->name('managefeature.status');


           //Admin menu
           Route::get('/admin-menu/list', [AdminmenuController::class, 'list'])->name('adminmenu.list');
           Route::get('/admin-menu/create', [AdminmenuController::class, 'create'])->name('adminmenu.create');
           Route::post('/admin-menu/store', [AdminmenuController::class, 'store'])->name('adminmenu.store');
           Route::any('/admin-menu/edit/{id}', [AdminmenuController::class, 'edit'])->name('adminmenu.edit');
           Route::post('/admin-menu/update/{id}', [AdminmenuController::class, 'update'])->name('adminmenu.update');
           Route::any('/admin-menu/destroy/{id}', [AdminmenuController::class, 'destroy'])->name('adminmenu.destroy');
           Route::post('/admin-menu/status', [AdminmenuController::class, 'status'])->name('adminmenu.status');

            //Manage Client
            Route::get('/manage-client/list', [ManageclientController::class, 'list'])->name('manageclient.list');
            Route::get('/manage-client/create', [ManageclientController::class, 'create'])->name('manageclient.create');
            Route::post('/manage-client/store', [ManageclientController::class, 'store'])->name('manageclient.store');
            Route::any('/manage-client/edit/{id}', [ManageclientController::class, 'edit'])->name('manageclient.edit');
            Route::post('/manage-client/update/{id}', [ManageclientController::class, 'update'])->name('manageclient.update');
            Route::any('/manage-client/destroy/{id}', [ManageclientController::class, 'destroy'])->name('manageclient.destroy');
            Route::post('/manage-client/status', [ManageclientController::class, 'status'])->name('manageclient.status');


            //Manage campaign Organizations
            Route::get('/manage-campaign-organizations/list', [ManagecampaignorganizationController::class, 'list'])->name('managecampaignorgs.list');
            Route::get('/manage-campaign-organizations/create', [ManagecampaignorganizationController::class, 'create'])->name('managecampaignorgs.create');
            Route::post('/manage-campaign-organizations/store', [ManagecampaignorganizationController::class, 'store'])->name('managecampaignorgs.store');
            Route::any('/manage-campaign-organizations/edit/{id}', [ManagecampaignorganizationController::class, 'edit'])->name('managecampaignorgs.edit');
            Route::post('/manage-campaign-organizations/update/{id}', [ManagecampaignorganizationController::class, 'update'])->name('managecampaignorgs.update');
            Route::any('/manage-campaign-organizations/destroy/{id}', [ManagecampaignorganizationController::class, 'destroy'])->name('managecampaignorgs.destroy');


            //Manage Quick Software
            Route::get('/manage-quick-software/list', [ManageQuickSoftwareController::class, 'list'])->name('managequicksoftware.list');
            Route::get('/manage-quick-software/create', [ManageQuickSoftwareController::class, 'create'])->name('managequicksoftware.create');
            Route::post('/manage-quick-software/store', [ManageQuickSoftwareController::class, 'store'])->name('managequicksoftware.store');
            Route::any('/manage-quick-software/edit/{id}', [ManageQuickSoftwareController::class, 'edit'])->name('managequicksoftware.edit');
            Route::post('/manage-quick-software/update/{id}', [ManageQuickSoftwareController::class, 'update'])->name('managequicksoftware.update');
            Route::any('/manage-quick-software/destroy/{id}', [ManageQuickSoftwareController::class, 'destroy'])->name('managequicksoftware.destroy');
            Route::post('/manage-quick-software/status', [ManageQuickSoftwareController::class, 'status'])->name('managequicksoftware.status');


            //Manage Service
            Route::get('/manage-services/list', [ManageserviceController::class, 'list'])->name('manageservices.list');
            Route::get('/manage-services/create', [ManageserviceController::class, 'create'])->name('manageservices.create');
            Route::post('/manage-services/store', [ManageserviceController::class, 'store'])->name('manageservices.store');
            Route::any('/manage-services/edit/{id}', [ManageserviceController::class, 'edit'])->name('manageservices.edit');
            Route::post('/manage-services/update/{id}', [ManageserviceController::class, 'update'])->name('manageservices.update');
            Route::any('/manage-services/destroy/{id}', [ManageserviceController::class, 'destroy'])->name('manageservices.destroy');
            Route::post('/manage-services/status', [ManageserviceController::class, 'status'])->name('manageservices.status');


            //Manage Faqs
            Route::get('/manage-faq/list', [ManageFaqController::class, 'list'])->name('managefaqs.list');
            Route::get('/manage-faq/create', [ManageFaqController::class, 'create'])->name('managefaqs.create');
            Route::post('/manage-faq/store', [ManageFaqController::class, 'store'])->name('managefaqs.store');
            Route::any('/manage-faq/edit/{id}', [ManageFaqController::class, 'edit'])->name('managefaqs.edit');
            Route::post('/manage-faq/update/{id}', [ManageFaqController::class, 'update'])->name('managefaqs.update');
            Route::any('/manage-faq/destroy/{id}', [ManageFaqController::class, 'destroy'])->name('managefaqs.destroy');
            Route::post('/manage-faq/status', [ManageFaqController::class, 'status'])->name('managefaqs.status');

            //Manage Team
            Route::get('/manage-team/list', [ManageTeamController::class, 'list'])->name('manageteam.list');
            Route::any('/manage-team/destroy/{id}', [ManageTeamController::class, 'destroy'])->name('manageteam.destroy');

            // Manage Survey List
            Route::get('/manage-survey/list', [ManageTeamController::class, 'surveylist'])->name('managesurvey.list');
            Route::any('/manage-survey/destroy/{id}', [ManageTeamController::class, 'surveydestroy'])->name('managesurvey.destroy');

            //Issue
            Route::get('/report-issue/list', [ManageTeamController::class, 'reportissuelist'])->name('reportissue.list');
            Route::any('/report-issue/destroy/{id}', [ManageTeamController::class, 'reportissuedestroy'])->name('reportissue.destroy');


          
            //Manage Users
            Route::get('manage-users/list', [ManageuserController::class, 'list'])->name('manageusers.list');
            Route::get('manage-users/create', [ManageuserController::class, 'create'])->name('manageusers.create');
            Route::post('manage-users/store', [ManageuserController::class, 'store'])->name('manageusers.store');
            Route::any('manage-users/edit/{id}', [ManageuserController::class, 'edit'])->name('manageusers.edit');
            Route::post('manage-users/update/{id}', [ManageuserController::class, 'update'])->name('manageusers.update');
            Route::any('manage-users/destroy/{id}', [ManageuserController::class, 'destroy'])->name('manageusers.destroy');
            Route::post('manage-users/status', [ManageuserController::class, 'status'])->name('manageusers.status');
            Route::post('/manage-users/senatorial-states', [ManageuserController::class, 'getsenstates'])->name('manageusers.getsenstates');
            Route::post('/manage-users/checkSlug', [ManageuserController::class, 'checkslug'])->name('manageusers.checkslug');



            //Manage Menu
            Route::get('manage-menu/list', [ManagemenuController::class, 'list'])->name('managemenu.list');
            Route::get('manage-menu/create', [ManagemenuController::class, 'create'])->name('managemenu.create');
            Route::post('manage-menu/store', [ManagemenuController::class, 'store'])->name('managemenu.store');
            Route::any('manage-menu/edit/{id}', [ManagemenuController::class, 'edit'])->name('managemenu.edit');
            Route::post('manage-menu/update/{id}', [ManagemenuController::class, 'update'])->name('managemenu.update');
            Route::any('manage-menu/destroy/{id}', [ManagemenuController::class, 'destroy'])->name('managemenu.destroy');
            Route::post('manage-menu/status', [ManagemenuController::class, 'status'])->name('managemenu.status');


            //Manage Election Count or votes
            Route::get('manage-election-voters/list', [ManageelectioncountController::class, 'list'])->name('manageelectionvoters.list');
            Route::get('manage-election-voters/create', [ManageelectioncountController::class, 'create'])->name('manageelectionvoters.create');
            Route::post('manage-election-voters/store', [ManageelectioncountController::class, 'store'])->name('manageelectionvoters.store');


            //Manage admin-users
            Route::get('manage-admin-users/list', [ManageadminController::class, 'list'])->name('manageadmins.list');
            Route::get('manage-admin-users/create', [ManageadminController::class, 'create'])->name('manageadmins.create');
            Route::post('manage-admin-users/store', [ManageadminController::class, 'store'])->name('manageadmins.store');
            Route::any('manage-admin-users/edit/{id}', [ManageadminController::class, 'edit'])->name('manageadmins.edit');
            Route::post('manage-admin-users/update/{id}', [ManageadminController::class, 'update'])->name('manageadmins.update');
            Route::any('manage-admin-users/destroy/{id}', [ManageadminController::class, 'destroy'])->name('manageadmins.destroy');

            //Manage Election Result
            Route::get('manage-election-result/list', [ManageelectionresultController::class, 'list'])->name('manageelectionresult.list');
            Route::get('manage-election-result/create', [ManageelectionresultController::class, 'create'])->name('manageelectionresult.create');
            Route::post('manage-election-result/store', [ManageelectionresultController::class, 'store'])->name('manageelectionresult.store');
            Route::any('manage-election-result/edit/{id}', [ManageelectionresultController::class, 'edit'])->name('manageelectionresult.edit');
            Route::post('manage-election-result/update/{id}', [ManageelectionresultController::class, 'update'])->name('manageelectionresult.update');
            Route::any('manage-election-result/destroy/{id}', [ManageelectionresultController::class, 'destroy'])->name('manageelectionresult.destroy');
            Route::get('/api/manage-election-result/fetch-polling-units', [ManageelectionresultController::class, 'fetchPollingUnits'])->name('api.fetchPollingUnits');

          

 
 
    
 
    
 
 
      //Dashboard Menu
      Route::resource('/dashboard-menu', DashboardmenuController::class);
 
    
 
     
 
    
 
      
});

   




require __DIR__.'/auth.php'; 
