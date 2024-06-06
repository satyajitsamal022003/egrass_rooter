<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ward;

class ManagewardController extends Controller
{
    public function list(Request $request){

        $searchtxt = $request->input('searchtxt');

        // Check if search term is present
        if ($searchtxt) {
            $localcont = Ward::where('lga', 'like', '%' . $searchtxt . '%')->get();
        } else {
            $localcont = Ward::all();
        }
    
        // Check if the request is an Ajax request
        if ($request->ajax()) {
            return view('admin.managewards.filter', compact('localcont'));
        } else {
            return view('admin.managewards.list', compact('localcont'));
        }
    }

    public function create(){
        return view('admin.managewards.add');
    }

    public function store(Request $request){
        $lga = new Ward();
        $lga->lga_id = $request->input('lga');
        $lga->ward_details = $request->input('wardname');
        $lga->ward_no = $request->input('wardno');
        $lga->created_at = now();
        $lga->updated_at = now(); 
        $lga->save();
        
        return redirect()->route('manageward.list')->with('message', 'Ward created Successfully !');
    }

    public function edit($id){
        $editward = Ward::find($id);
      return view('admin.managewards.edit',compact('editward'));
    }

    
    public function update(Request $request,$id){
        $lga = Ward::findOrFail($id);
        $lga->lga_id = $request->input('lga');
        $lga->ward_details = $request->input('wardname');
        $lga->ward_no = $request->input('wardno');
        $lga->updated_at = now(); 
        $lga->save();
        
        return redirect()->route('manageward.list')->with('message', 'Ward Updated Successfully !');
    }

    public function destroy($id)
    {
        $locconst = Ward::find($id); // Find the item by its ID
        if (!$locconst) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $locconst->delete(); // Delete the item

         return redirect()->route('manageward.list')->with('message', 'Ward Removed successfully !.'); // Redirect to the index page with success message
    }

}
