<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class ManageclientController extends Controller
{
    public function list(Request $request){

        $searchtxt = $request->input('searchtxt');

        // Check if search term is present
        if ($searchtxt) {
            $clientlist = Client::where('lga', 'like', '%' . $searchtxt . '%')->get();
        } else {
            $clientlist = Client::all();
        }
    
        // Check if the request is an Ajax request
        if ($request->ajax()) {
            return view('admin.manageclient.filter', compact('clientlist'));
        } else {
            return view('admin.manageclient.list', compact('clientlist'));
        }
    }

    public function create(){
        return view('admin.manageclient.add');
    }

    public function store(Request $request){

       $clientadd = new Client();
       $clientadd->client_name = $request->input('clientname');
       $clientadd->is_active = $request->input('status');
       $clientadd->created_at = now();
       $clientadd->updated_at = now(); 

        if ($request->hasFile('image')) {
            // Generate a unique file name for the image
            $imageName = 'client_' . time() .'.'.$request->file('image')->getClientOriginalExtension();
            
            $destinationDirectory = public_path('images/clients');
        
            if (!is_dir($destinationDirectory)) {
                mkdir($destinationDirectory, 0777, true);
            }
        
            // Move the file to the public/uploads directory
            $request->file('image')->move($destinationDirectory, $imageName);
        
           $clientadd->client_image = $imageName;
        }
       $clientadd->save();
        
        return redirect()->route('manageclient.list')->with('message', 'Client created Successfully !');
    }

    public function edit($id){
        $editsitecont = Client::find($id);
      return view('admin.manageclient.edit',compact('editsitecont'));
    }

    
    public function update(Request $request,$id){
        $updateclient = Client::findOrFail($id);
        $updateclient->client_name = $request->input('clientname');
        $updateclient->is_active = $request->input('status');
        $updateclient->updated_at = now(); 

         // Update Image
         if ($request->hasFile('image')) {
            // Generate a unique file name for the image
            $imageName = 'client_' . time() .'.'.$request->file('image')->getClientOriginalExtension();
            
            $destinationDirectory = public_path('images/clients');
        
            if (!is_dir($destinationDirectory)) {
                mkdir($destinationDirectory, 0777, true);
            }
        
            // Move the file to the public/uploads directory
            $request->file('image')->move($destinationDirectory, $imageName);
        
            // Delete existing image file if it exists
            if (!empty($updateclient->client_image)) {
                $existingImagePath = $destinationDirectory . '/' . $updateclient->client_image;
                if (file_exists($existingImagePath) && is_file($existingImagePath)) {
                    unlink($existingImagePath);
                }
            }
        
            $updateclient->client_image = $imageName;
        }

        $updateclient->save();
        
        return redirect()->route('manageclient.list')->with('message', 'Client Updated Successfully !');
    }

    public function destroy($id)
    {
        $locconst = Client::find($id); // Find the item by its ID
        if (!$locconst) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $locconst->delete(); // Delete the item

         return redirect()->route('manageclient.list')->with('message', 'Client Removed successfully !.'); // Redirect to the index page with success message
    }

    public function status(Request $request){
        $get_id=$request->id;
        $catstatus=DB::table('clients')
        ->select('is_active')
        ->where('id','=',$get_id)
        ->first();
        

        $astatus=$catstatus->is_active;
         if($astatus == '1'){
             $astatus='0'; 
         } else{
             $astatus='1';
         }
         $statusupdate=DB::table('clients')
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

