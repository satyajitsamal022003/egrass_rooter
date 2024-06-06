<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quick_software;
use Illuminate\Support\Facades\DB;

class ManageQuickSoftwareController extends Controller
{
    public function list(Request $request){

        $searchtxt = $request->input('searchtxt');

        // Check if search term is present
        if ($searchtxt) {
            $quicksoftlist = Quick_software::where('lga', 'like', '%' . $searchtxt . '%')->get();
        } else {
            $quicksoftlist = Quick_software::all();
        }
    
        // Check if the request is an Ajax request
        if ($request->ajax()) {
            return view('admin.managequicksoftware.filter', compact('quicksoftlist'));
        } else {
            return view('admin.managequicksoftware.list', compact('quicksoftlist'));
        }
    }

    public function create(){
        return view('admin.managequicksoftware.add');
    }

    public function store(Request $request){

       $softwareadd = new Quick_software();
       $softwareadd->title = $request->input('title');
       $softwareadd->is_active = $request->input('status');
       $softwareadd->created_at = now();
       $softwareadd->updated_at = now(); 

        if ($request->hasFile('image')) {
            // Generate a unique file name for the image
            $imageName = 'software_' . time() .'.'.$request->file('image')->getClientOriginalExtension();
            
            $destinationDirectory = public_path('images/quick_software');
        
            if (!is_dir($destinationDirectory)) {
                mkdir($destinationDirectory, 0777, true); 
            }
        
            // Move the file to the public/uploads directory
            $request->file('image')->move($destinationDirectory, $imageName);
        
           $softwareadd->image = $imageName;
        }
       $softwareadd->save();
        
        return redirect()->route('managequicksoftware.list')->with('message', 'Quick Software created Successfully !');
    }

    public function edit($id){
        $editquicksoft = Quick_software::find($id);
      return view('admin.managequicksoftware.edit',compact('editquicksoft'));
    }

    
    public function update(Request $request,$id){
        $updatesoftware = Quick_software::findOrFail($id);
        $updatesoftware->title = $request->input('title');
        $updatesoftware->is_active = $request->input('status');
        $updatesoftware->updated_at = now(); 

         // Update Image
         if ($request->hasFile('image')) {
            // Generate a unique file name for the image
            $imageName = 'software_' . time() .'.'.$request->file('image')->getClientOriginalExtension();
            
            $destinationDirectory = public_path('images/quick_software');
        
            if (!is_dir($destinationDirectory)) {
                mkdir($destinationDirectory, 0777, true);
            }
        
            // Move the file to the public/uploads directory
            $request->file('image')->move($destinationDirectory, $imageName);
        
            // Delete existing image file if it exists
            if (!empty($updatesoftware->image)) {
                $existingImagePath = $destinationDirectory . '/' . $updatesoftware->image;
                if (file_exists($existingImagePath) && is_file($existingImagePath)) {
                    unlink($existingImagePath);
                }
            }
        
            $updatesoftware->image = $imageName;
        }

        $updatesoftware->save();
        
        return redirect()->route('managequicksoftware.list')->with('message', 'Quick Software Updated Successfully !');
    }

    public function destroy($id)
    {
        $locconst = Quick_software::find($id); // Find the item by its ID
        if (!$locconst) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $locconst->delete(); // Delete the item

         return redirect()->route('managequicksoftware.list')->with('message', 'Quick Software Removed successfully !.'); // Redirect to the index page with success message
    }

    public function status(Request $request){
        $get_id=$request->id;
        $catstatus=DB::table('quick_software')
        ->select('is_active')
        ->where('id','=',$get_id)
        ->first();
        

        $astatus=$catstatus->is_active;
         if($astatus == '1'){
             $astatus='0'; 
         } else{
             $astatus='1';
         }
         $statusupdate=DB::table('quick_software')
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
