<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Survey;
use App\Models\Issue;
use App\Models\TeamInvite;


class ManageTeamController extends Controller
{
    public function list(){

        $team = Team::get();
        return view('admin.manageteam.list',compact('team'));
    }

    public function sucessmember($id){
        $team = TeamInvite::where('team_id', $id)->get();
        $memberids = [];
        foreach ($team as $t) {
            $memberids[] = $t->member_id;
        }
        // dd($memberids);
        return view('admin.manageteam.successmember',compact('team','memberids'));
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

    public function surveylist(){

        $survey = Survey::get();
        return view('admin.survey.list',compact('survey'));
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

    public function reportissuelist(){

        $issue = Issue::get();
        // dd($issue);
        return view('admin.issue.list',compact('issue'));
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

}
