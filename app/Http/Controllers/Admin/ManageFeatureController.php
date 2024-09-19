<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feature_section;
use Illuminate\Support\Facades\DB;

class ManageFeatureController extends Controller
{
    public function list(Request $request){

        $searchtxt = $request->input('searchtxt');

        // Check if search term is present
        if ($searchtxt) {
            $featurelist = Feature_section::where('lga', 'like', '%' . $searchtxt . '%')->get();
        } else {
            $featurelist = Feature_section::all();
        }
    
        // Check if the request is an Ajax request
        if ($request->ajax()) {
            return view('admin.managefeature.filter', compact('featurelist'));
        } else {
            return view('admin.managefeature.list', compact('featurelist'));
        }
    }

    public function create(){
        return view('admin.managefeature.add');
    }

    public function store(Request $request){
        $featureadd = new Feature_section();
        $featureadd->title = $request->input('title');
        $featureadd->slug = $request->input('slug');
        $featureadd->description = $request->input('description');
        $featureadd->icon = $request->input('feature_class');
        $featureadd->is_active = $request->input('status');
        $featureadd->created_at = now();
        $featureadd->updated_at = now(); 
        $featureadd->save();
        
        return redirect()->route('managefeature.list')->with('message', 'Feature created Successfully !');
    }

    public function edit($id){
        $editfeature = DB::table('feature_sections')->select('*')->where('id',$id)->first();
      return view('admin.managefeature.edit',compact('editfeature'));
    }

    
    public function update(Request $request,$id ){
        $updatefeature = Feature_section::findOrFail($id);
        $updatefeature->title = $request->input('title');
        $updatefeature->slug = $request->input('slug');
        $updatefeature->description = $request->input('description');
        $updatefeature->icon = $request->input('feature_class');
        $updatefeature->is_active = $request->input('status');
        $updatefeature->created_at = now();
        $updatefeature->updated_at = now(); 
        $updatefeature->save();
        
        return redirect()->route('managefeature.list')->with('message', 'Feature Updated Successfully !');
    }

    public function destroy($id )
    {
        $socconst = Feature_section::find($id ); // Find the item by its ID
        if (!$socconst) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $socconst->delete(); // Delete the item

         return redirect()->route('managefeature.list')->with('message', 'Feature Removed successfully !.'); // Redirect to the index page with success message
    }

    public function status(Request $request){
        $get_id=$request->id;
        $socialstatus=DB::table('feature_sections')
        ->select('is_active')
        ->where('id','=',$get_id)
        ->first();
        

        $astatus=$socialstatus->is_active;
         if($astatus == '1'){
             $astatus='0'; 
         } else{
             $astatus='1';
         }
         $statusupdate=DB::table('feature_sections')
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
