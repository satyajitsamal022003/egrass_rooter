<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use App\Models\AddMember;
use App\Models\Sitesetting;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BulkEmailController extends Controller
{
    public function getroletype()
    {
        $getroletype = Role::select('id', 'role')->where('is_active', 1)->get();

        $getroletypedata = $getroletype->map(function ($roledata) {
            return [
                'role_id' => $roledata->id,
                'role_name' => $roledata->role
            ];
        });
        return response()->json([
            'getroletype' => $getroletypedata
        ], 200);
    }

    public function getcontactmembers($role)
    {
        $getcontactmembers = AddMember::where('role_type', $role)->where('is_active', 1)->get();

        $getmemberdata = [];
        foreach ($getcontactmembers as $edata) {
            $getmemberdata[] = [
                'id' => $edata->id,
                'email' => $edata->email_id,
            ];
        }

        return response()->json([
            'members' => $getmemberdata
        ], 200);
    }

    public function emailToTeamMembers(Request $request)
    {
        // Check if the user is authenticated
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

       
        $userid = $user->id;
        // $userid = 674; // Uncomment if you want to use a hardcoded user ID for testing
        $selectedMembers = $request->input('selectedmember', []);
        $umsg = $request->input('compose');
        $roleType = $request->input('role_type');
        $selectAll = $request->input('selectall');
        $subject = 'Hello,';

        try {
            if ($roleType === 'ALL') {
                $members = AddMember::where('user_id', $userid)
                    ->pluck('email_id');
            } elseif ($selectAll) {
                $members = AddMember::where('user_id', $userid)
                    ->where('role_type', $roleType)
                    ->pluck('email_id');
            } else {
                $members = AddMember::where('user_id', $userid)
                    ->whereIn('email_id', $selectedMembers)
                    ->pluck('email_id');
            }

            if ($members->isEmpty()) {
                return response()->json([
                    'message' => 'No Member Found!'
                ]);
            }

            $emailAddresses = $members->toArray();

            $allSent = true; 

            // Send emails using array_map
            $results = array_map(function ($email) use ($umsg, $subject) {
                try {
                    Mail::to($email)->send(new \App\Mail\NotificationMail($umsg, $subject));
                    return true;
                } catch (\Exception $e) {
                    // Log the error and return the exception message for debugging
                    // Log::error('Email sending failed for ' . $email . ': ' . $e->getMessage());
                    // dd('Email sending failed for ' . $email . ': ' . $e->getMessage());
                    return false;
                }
            }, $emailAddresses);
            if (in_array(false, $results, true)) {
                return response()->json(['message' => 'Some emails could not be sent.'], 500);
            } else {
                return response()->json(['message' => 'Email Sent to Team Members successfully!'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error sending email: ' . $e->getMessage()], 500);
        }
    }
}
