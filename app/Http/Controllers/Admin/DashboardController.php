<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign_user;
use App\Models\Polling_unit;
use App\Models\Service;
use App\Models\Party;
use App\Models\Survey;
use App\Models\GrassrooterFeedback;


class DashboardController extends Controller
{
    public function index()
    {
        $campaignManagers = Campaign_user::orderBy('id', 'desc')->take(5)->get();
        $pollingList = Polling_unit::orderBy('id')->limit(5)->get();
        $allServices = Service::orderBy('id', 'desc')->limit(5)->get();

        $registeredusers = Campaign_user::get();
        // $verifiedusers = Campaign_user::where('is_active',1)->get();
        // dd($verifiedusers);

        $parties = Party::get();

        $verifiedusers = Campaign_user::where('is_mail_verified', 1)->count();
        $activeuser = Campaign_user::where('is_mail_verified', 1)
                                ->where('is_active', 1)
                                ->count();



        // dd($campaignManagers);
        return view('admin.dashboard',compact('campaignManagers','pollingList','allServices','registeredusers','parties','verifiedusers', 'activeuser'));
    }
}
