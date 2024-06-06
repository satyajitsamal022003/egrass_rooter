<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;

class ManagestateController extends Controller
{
    public function create(){
        return view('admin.managestate.add');
    }

    public function store(Request $request){
        $partyadd = new State();
        $partyadd->state = $request->input('state_name');
        $partyadd->zone = $request->input('zone');
        $partyadd->created_at = now();
        $partyadd->updated_at = now(); 
        $partyadd->save();
        
        return redirect()->route('managestate.list')->with('message', 'State Added Successfully!');
    }

    public function edit($id){
        $editstate = State::find($id);
      return view('admin.managestate.edit',compact('editstate'));
    }

    public function update(Request $request,$id){
        $partyadd = State::findOrFail($id);
        $partyadd->state = $request->input('state_name');
        $partyadd->zone = $request->input('zone');
        $partyadd->updated_at = now(); 
        $partyadd->save();
        
        return redirect()->route('managestate.list')->with('message', 'State Updated Successfully!');
    }

    public function list(Request $request){

        $searchtxt = $request->input('searchtxt');

        // Check if search term is present
        if ($searchtxt) {
            $statelist = State::where('state', 'like', '%' . $searchtxt . '%')->get();
        } else {
            $statelist = State::all();
        }
    
        // Check if the request is an Ajax request
        if ($request->ajax()) {
            return view('admin.managestate.filter', compact('statelist'));
        } else {
            return view('admin.managestate.list', compact('statelist'));
        }
    }

    public function destroy($id)
    {
        $state = State::find($id); // Find the item by its ID
        if (!$state) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $state->delete(); // Delete the item

         return redirect()->route('managestate.list')->with('message', 'State Removed successfully !.'); // Redirect to the index page with success message
    }
}
