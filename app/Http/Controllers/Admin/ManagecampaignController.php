<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManagecampaignController extends Controller
{
    public function list(){
        return view('admin.managecampaigns.list');
    }

    public function view($id){

        $campaignnext = DB::table('campaign_next')->where('user_id',$id)->first();
        // dd($campaignnext);
        return view('admin.managecampaigns.view',compact('campaignnext'));
    }
}
