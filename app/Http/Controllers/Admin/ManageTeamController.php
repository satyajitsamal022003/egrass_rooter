<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddMember;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Survey;
use App\Models\Issue;
use App\Models\TeamInvite;
use Illuminate\Support\Facades\DB;

class ManageTeamController extends Controller
{
    public function list()
    {

        $team = Team::get();
        return view('admin.manageteam.list', compact('team'));
    }

    public function teammembers($teamid)
    {

        $memberIds = TeamInvite::where('team_id', $teamid)->pluck('member_id');

        // Step 2: Decode JSON-encoded member IDs
        $decodedMemberIds = collect($memberIds)->map(function ($item) {
            return json_decode($item);
        })->flatten();

        // Step 3: Fetch member details from AddMember model and join with Role model
        $members = AddMember::whereIn('add_members.id', $decodedMemberIds) // Specify table name for id
            ->leftJoin('role', 'role.id', '=', 'add_members.role_type') // Join with Role model
            ->get([
                'add_members.id',           // Member ID
                'role.role as role_name',   // Role Name from Role model
                'add_members.name',         // Name from AddMember model
                'add_members.phone_number', // Phone Number from AddMember model
                'add_members.email_id',     // Email ID from AddMember model
                'add_members.address',       // Address from AddMember model
                'add_members.user_id',       // Address from AddMember model
                'add_members.role_type'       // Address from AddMember model
            ]);

            // dd($members);
        return view('admin.manageteam.memberview', compact('members'));
    }

    public function destroy($id)
    {
        $locconst = Team::find($id); // Find the item by its ID
        if (!$locconst) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $locconst->delete(); // Delete the item

        return redirect()->route('manageteam.list')->with('message', 'Team Deleted successfully !.'); // Redirect to the index page with success message
    }

    public function surveylist()
    {

        $survey = Survey::get();
        return view('admin.survey.list', compact('survey'));
    }

    public function surveydestroy($id)
    {
        $locconst = Survey::find($id); // Find the item by its ID
        if (!$locconst) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $locconst->delete(); // Delete the item

        return redirect()->route('managesurvey.list')->with('message', 'Survey Deleted successfully !'); // Redirect to the index page with success message
    }

    public function reportissuelist()
    {

        $issue = Issue::get();
        return view('admin.issue.list', compact('issue'));
    }

    public function reportissuedestroy($id)
    {
        $locconst = Issue::find($id); // Find the item by its ID
        if (!$locconst) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $locconst->delete(); // Delete the item

        return redirect()->route('reportissue.list')->with('message', 'Issue Removed successfully !'); // Redirect to the index page with success message
    }

    public function status(Request $request){
        $get_id=$request->id;
        $catstatus=DB::table('add_members')
        ->select('is_active')
        ->where('id','=',$get_id)
        ->first();
        

        $astatus=$catstatus->is_active;
         if($astatus == '1'){
             $astatus='0'; 
         } else{
             $astatus='1';
         }
         $statusupdate=DB::table('add_members')
         ->where('id', $get_id)
         ->update(array('is_active'=>$astatus));

         if($statusupdate){
             return response()->json([
                 'status' => 'success',
                 'code' => 200,
             ]);
            }
        }
}
