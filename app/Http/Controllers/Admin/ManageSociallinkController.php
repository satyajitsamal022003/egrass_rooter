<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Social;
use Illuminate\Support\Facades\DB;

class ManageSociallinkController extends Controller
{
    public function list(Request $request){

        $searchtxt = $request->input('searchtxt');

        // Check if search term is present
        if ($searchtxt) {
            $socialdatas = Social::where('lga', 'like', '%' . $searchtxt . '%')->get();
        } else {
            $socialdatas = Social::all();
        }
    
        // Check if the request is an Ajax request
        if ($request->ajax()) {
            return view('admin.managesociallinks.filter', compact('socialdatas'));
        } else {
            return view('admin.managesociallinks.list', compact('socialdatas'));
        }
    }

    public function create(){
        return view('admin.managesociallinks.add');
    }

    public function store(Request $request){
        $socialadd = new Social();
        $socialadd->social_media_name = $request->input('social_name');
        $socialadd->social_media_link = $request->input('social_link');
        $socialadd->social_media_class = $request->input('social_class');
        $socialadd->is_active = $request->input('status');
        $socialadd->created_at = now();
        $socialadd->updated_at = now(); 
        $socialadd->save();
        
        return redirect()->route('managesociallinks.list')->with('message', 'Social Link created Successfully !');
    }

    public function edit($id){
        $editsocialdata = DB::table('socials')->select('*')->where('id',$id)->first();
      return view('admin.managesociallinks.edit',compact('editsocialdata'));
    }

    
    public function update(Request $request,$id ){
        $updatesocialdata = Social::findOrFail($id);
        $updatesocialdata->social_media_name = $request->input('social_name');
        $updatesocialdata->social_media_link = $request->input('social_link');
        $updatesocialdata->social_media_class = $request->input('social_class');
        $updatesocialdata->is_active = $request->input('status');
        $updatesocialdata->updated_at = now(); 
        $updatesocialdata->save();
        
        return redirect()->route('managesociallinks.list')->with('message', 'Social Link Updated Successfully !');
    }

    public function destroy($id )
    {
        $socconst = Social::find($id ); // Find the item by its ID
        if (!$socconst) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $socconst->delete(); // Delete the item

         return redirect()->route('managesociallinks.list')->with('message', 'Social Link Removed successfully !.'); // Redirect to the index page with success message
    }

    public function status(Request $request){
        $get_id=$request->id;
        $socialstatus=DB::table('socials')
        ->select('is_active')
        ->where('id','=',$get_id)
        ->first();
        

        $astatus=$socialstatus->is_active;
         if($astatus == '1'){
             $astatus='0'; 
         } else{
             $astatus='1';
         }
         $statusupdate=DB::table('socials')
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
