<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function donationlist(){
        $donationlist = Donation::get();

        // dd($donationlist);

        return view('admin.managedonation.list',compact('donationlist'));
    }
}
