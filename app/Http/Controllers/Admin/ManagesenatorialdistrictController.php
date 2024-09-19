<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Senatorial_state;

class ManagesenatorialdistrictController extends Controller
{
    public function create(){
        return view('admin.managesenatorial_districts.add');
    }

    public function list(Request $request){

        $searchtxt = $request->input('searchtxt');

        // Check if search term is present
        if ($searchtxt) {
            $statelist = Senatorial_state::where('sena_district', 'like', '%' . $searchtxt . '%')->get();
        } else {
            $statelist = Senatorial_state::all();
        }
    
        // Check if the request is an Ajax request
        if ($request->ajax()) {
            return view('admin.managesenatorial_districts.filter', compact('statelist'));
        } else {
            return view('admin.managesenatorial_districts.list', compact('statelist'));
        }
    }

    public function store(Request $request){
        $partyadd = new Senatorial_state();
        $partyadd->state_id = $request->input('state');
        $partyadd->sena_district = $request->input('district_name');
        $partyadd->code = $request->input('code');
        $partyadd->composition = $request->input('composition');
        $partyadd->collation_center = $request->input('collation_centre');
        $partyadd->created_at = now();
        $partyadd->updated_at = now(); 
        $partyadd->save();
        
        return redirect()->route('senatorialdist.list')->with('message', 'Senatorial district Added Successfully!');
    }

    public function edit($id){
        $editstate = Senatorial_state::find($id);
      return view('admin.managesenatorial_districts.edit',compact('editstate'));
    }

    public function update(Request $request,$id){
        $partyadd = Senatorial_state::findOrFail($id);
        $partyadd->state_id = $request->input('state');
        $partyadd->sena_district = $request->input('district_name');
        $partyadd->code = $request->input('code');
        $partyadd->composition = $request->input('composition');
        $partyadd->collation_center = $request->input('collation_centre');
        $partyadd->updated_at = now(); 
        $partyadd->save();
        
        return redirect()->route('senatorialdist.list')->with('message', 'Senatorial district Updated Successfully!');
    }

    public function destroy($id)
    {
        $state = Senatorial_state::find($id); // Find the item by its ID
        if (!$state) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $state->delete(); // Delete the item

         return redirect()->route('managestate.list')->with('message', 'Senatorial district Removed successfully !.'); // Redirect to the index page with success message
    }
}
