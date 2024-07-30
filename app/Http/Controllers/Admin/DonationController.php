<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use Illuminate\Support\Facades\DB;

class DonationController extends Controller
{
    public function list(Request $request){
        $donations = Donation::all();
        return view('admin.donations.list',compact('donations'));
    }

    public function status(Request $request)
    {
        $get_id = $request->id;
        $catstatus = DB::table('donation')
            ->select('is_active')
            ->where('id', '=', $get_id)
            ->first();


        $astatus = $catstatus->is_active;
        if ($astatus == '1') {
            $astatus = '0';
        } else {
            $astatus = '1';
        }
        $statusupdate = DB::table('donation')
            ->where('id', $get_id)
            ->update(array('is_active' => $astatus));

        if ($statusupdate) {
            return response()->json([
                'status' => 'success',
                'code' => 200,
            ]);
        }
    }
}
