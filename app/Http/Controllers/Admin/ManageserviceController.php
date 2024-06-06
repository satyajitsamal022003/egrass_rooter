<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Service;

class ManageserviceController extends Controller
{
    public function list(Request $request){

        $searchtxt = $request->input('searchtxt');

        // Check if search term is present
        if ($searchtxt) {
            $servicelist = Service::where('lga', 'like', '%' . $searchtxt . '%')->get();
        } else {
            $servicelist = Service::all();
        }
    
        // Check if the request is an Ajax request
        if ($request->ajax()) {
            return view('admin.manageservices.filter', compact('servicelist'));
        } else {
            return view('admin.manageservices.list', compact('servicelist'));
        }
    }

    public function create(){
        return view('admin.manageservices.add');
    }

    public function store(Request $request){

       $serviceadd = new Service();
       $serviceadd->title = $request->input('title');
       $serviceadd->content = $request->input('content');
       $serviceadd->icon = $request->input('serviceclass');
       $serviceadd->is_active = $request->input('status');
       $serviceadd->created_at = now();
       $serviceadd->updated_at = now(); 

       $serviceadd->save();
        
        return redirect()->route('manageservices.list')->with('message', 'Service created Successfully !');
    }

    public function edit($id){
        $editservices = Service::find($id);
      return view('admin.manageservices.edit',compact('editservices'));
    }

    
    public function update(Request $request,$id){
        $updateservice = Service::findOrFail($id);
        $updateservice->title = $request->input('title');
        $updateservice->content = $request->input('content');
        $updateservice->icon = $request->input('serviceclass');
        $updateservice->is_active = $request->input('status');
        $updateservice->updated_at = now(); 
 
        $updateservice->save();
        
        return redirect()->route('manageservices.list')->with('message', 'Service Updated Successfully !');
    }

    public function destroy($id)
    {
        $locconst = Service::find($id); // Find the item by its ID
        if (!$locconst) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $locconst->delete(); // Delete the item

         return redirect()->route('manageservices.list')->with('message', 'Service Removed successfully !.'); // Redirect to the index page with success message
    }

    public function status(Request $request){
        $get_id=$request->id;
        $catstatus=DB::table('services')
        ->select('is_active')
        ->where('id','=',$get_id)
        ->first();
        

        $astatus=$catstatus->is_active;
         if($astatus == '1'){
             $astatus='0'; 
         } else{
             $astatus='1';
         }
         $statusupdate=DB::table('services')
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
