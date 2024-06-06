<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State_constituency;

class ManagestateconstituencyController extends Controller
{
    public function create(){
        return view('admin.managestate_constituency.add');
    }

    public function store(Request $request){
        $stateconst = new State_constituency();
        $stateconst->state_id = $request->input('state');
        $stateconst->state_constituency = $request->input('state_const');
        $stateconst->code = $request->input('code');
        $stateconst->composition = $request->input('composition');
        $stateconst->created_at = now();
        $stateconst->updated_at = now(); 
        $stateconst->save();
        
        return redirect()->route('stateconst.list')->with('message', 'State constituency created Successfully!');
    }

    public function edit($id){
        $statconst = State_constituency::find($id);
      return view('admin.managestate_constituency.edit',compact('statconst'));
    }

    public function update(Request $request,$id){
        $stateconst = State_constituency::findOrFail($id);
        $stateconst->state_id = $request->input('state');
        $stateconst->state_constituency = $request->input('state_const');
        $stateconst->code = $request->input('code');
        $stateconst->composition = $request->input('composition');
        $stateconst->updated_at = now(); 
        $stateconst->save();
        
        return redirect()->route('stateconst.list')->with('message', 'State constituency Updated Successfully!');
    }

    public function list(Request $request){

        $searchtxt = $request->input('searchtxt');

        // Check if search term is present
        if ($searchtxt) {
            $stateconst = State_constituency::where('state_constituency', 'like', '%' . $searchtxt . '%')->get();
        } else {
            $stateconst = State_constituency::all();
        }
    
        // Check if the request is an Ajax request
        if ($request->ajax()) {
            return view('admin.managestate_constituency.filter', compact('stateconst'));
        } else {
            return view('admin.managestate_constituency.list', compact('stateconst'));
        }
    }

    public function destroy($id)
    {
        $statconst = State_constituency::find($id); // Find the item by its ID
        if (!$statconst) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $statconst->delete(); // Delete the item

         return redirect()->route('stateconst.list')->with('message', 'State constituency Removed successfully !.'); // Redirect to the index page with success message
    }
}
