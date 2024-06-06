<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Local_constituency;

class ManagelgaController extends Controller
{
    public function list(Request $request){

        $searchtxt = $request->input('searchtxt');

        // Check if search term is present
        if ($searchtxt) {
            $localcont = Local_constituency::where('lga', 'like', '%' . $searchtxt . '%')->get();
        } else {
            $localcont = Local_constituency::all();
        }
    
        // Check if the request is an Ajax request
        if ($request->ajax()) {
            return view('admin.manageLGA.filter', compact('localcont'));
        } else {
            return view('admin.manageLGA.list', compact('localcont'));
        }
    }

    public function create(){
        return view('admin.manageLGA.add');
    }

    public function store(Request $request){
        $lga = new Local_constituency();
        $lga->state_id = $request->input('state');
        $lga->lga = $request->input('lga');
        $lga->created_at = now();
        $lga->updated_at = now(); 
        $lga->save();
        
        return redirect()->route('managelga.list')->with('message', 'LGA created Successfully !');
    }

    public function edit($id){
        $localconst = Local_constituency::find($id);
      return view('admin.manageLGA.edit',compact('localconst'));
    }

    
    public function update(Request $request,$id){
        $lga = Local_constituency::findOrFail($id);
        $lga->state_id = $request->input('state');
        $lga->lga = $request->input('lga');
        $lga->updated_at = now(); 
        $lga->save();
        
        return redirect()->route('managelga.list')->with('message', 'LGA Updated Successfully !');
    }

    public function destroy($id)
    {
        $locconst = Local_constituency::find($id); // Find the item by its ID
        if (!$locconst) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $locconst->delete(); // Delete the item

         return redirect()->route('managelga.list')->with('message', 'LGA Removed successfully !.'); // Redirect to the index page with success message
    }

}
