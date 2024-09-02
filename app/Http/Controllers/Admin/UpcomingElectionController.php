<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Upcomingelection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UpcomingElectionController extends Controller
{
    public function addupcomingelections()
    {
        return view('admin.upcomingelection.add');
    }

    public function postupcomingelections(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'election_date' => 'required|date',
        ]);

        // Create a new Upcomingelection instance and save the data
        $upcomingElection = new Upcomingelection();
        $upcomingElection->election_type_id = $request->input('election_type');
        $upcomingElection->state_id = $request->input('state_id');
        $upcomingElection->election_date = $request->input('election_date');
        $upcomingElection->save();

        // Redirect or return a response
        return redirect()->route('upcomingelections')->with('message', 'Upcoming election added successfully.');
    }

    public function editupcomingelections($id)
    {
        $election = Upcomingelection::find($id);

        return view('admin.upcomingelection.edit', compact('election'));
    }

    public function updateupcomingelections(Request $request, $id)
    {
        $upcomingElection = Upcomingelection::findOrFail($id);

        // Validate the form data
        $validatedData = $request->validate([
            'election_date' => 'required|date',
        ]);

        // Update the Upcomingelection instance with the validated data
        $upcomingElection->election_type_id = $request->input('election_type');
        $upcomingElection->state_id = $request->input('state_id');
        $upcomingElection->election_date = $request->input('election_date');
        $upcomingElection->updated_at = Now();
        $upcomingElection->save();

        // Redirect or return a response
        return redirect()->route('upcomingelections')->with('message', 'Upcoming election updated successfully.');
    }

    public function upcomingelections()
    {
        $elections = Upcomingelection::get();
        return view('admin.upcomingelection.list', compact('elections'));
    }

    public function destroyupcomingelection($id)
    {
        // Find the upcoming election by ID
        $election = Upcomingelection::find($id);

        // Check if the election exists
        if (!$election) {
            return redirect()->route('upcomingelections')->with('error', 'Election not found.');
        }

        // Delete the election
        $election->delete();

        // Redirect back with a success message
        return redirect()->route('upcomingelections')->with('message', 'Election deleted successfully.');
    }


    public function upcomingstatus(Request $request){
        $get_id=$request->id;
        $catstatus=DB::table('upcomingelections')
        ->select('is_active')
        ->where('id','=',$get_id)
        ->first();
        

        $astatus=$catstatus->is_active;
         if($astatus == '1'){
             $astatus='0'; 
         } else{
             $astatus='1';
         }
         $statusupdate=DB::table('upcomingelections')
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
