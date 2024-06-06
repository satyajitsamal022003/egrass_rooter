<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Site_content;
use Illuminate\Support\Facades\DB;

class ManagesitecontController extends Controller
{
    public function list(Request $request){

        $searchtxt = $request->input('searchtxt');

        // Check if search term is present
        if ($searchtxt) {
            $localcont = Site_content::where('lga', 'like', '%' . $searchtxt . '%')->get();
        } else {
            $localcont = Site_content::all();
        }
    
        // Check if the request is an Ajax request
        if ($request->ajax()) {
            return view('admin.managesitecontent.filter', compact('localcont'));
        } else {
            return view('admin.managesitecontent.list', compact('localcont'));
        }
    }

    public function create(){
        return view('admin.managesitecontent.add');
    }

    public function store(Request $request){
        $sitecont = new Site_content();
        $sitecont->title = $request->input('title');
        $sitecont->referene = $request->input('reference');
        $sitecont->description = $request->input('description');
        $sitecont->img_alt = $request->input('imagalt');
        $sitecont->is_active = $request->input('status');
        $sitecont->created_at = now();
        $sitecont->updated_at = now(); 

        if ($request->hasFile('image')) {
            // Generate a unique file name for the image
            $imageName = 'sitecont_' . time() .'.'.$request->file('image')->getClientOriginalExtension();
            
            $destinationDirectory = public_path('images/sitecontents');
        
            if (!is_dir($destinationDirectory)) {
                mkdir($destinationDirectory, 0777, true);
            }
        
            // Move the file to the public/uploads directory
            $request->file('image')->move($destinationDirectory, $imageName);
        
            $sitecont->image = $imageName;
        }
        $sitecont->save();
        
        return redirect()->route('managesitecontent.list')->with('message', 'Site content created Successfully !');
    }

    public function edit($id){
        $editsitecont = Site_content::find($id);
      return view('admin.managesitecontent.edit',compact('editsitecont'));
    }

    
    public function update(Request $request,$id){
        $sitecont = Site_content::findOrFail($id);
        $sitecont->title = $request->input('title');
        $sitecont->referene = $request->input('reference');
        $sitecont->description = $request->input('description');
        $sitecont->img_alt = $request->input('imagalt');
        $sitecont->is_active = $request->input('status');
        $sitecont->updated_at = now(); 

         // Update Image
         if ($request->hasFile('image')) {
            // Generate a unique file name for the image
            $imageName = 'sitecont_' . time() .'.'.$request->file('image')->getClientOriginalExtension();
            
            $destinationDirectory = public_path('images/sitecontents');
        
            if (!is_dir($destinationDirectory)) {
                mkdir($destinationDirectory, 0777, true);
            }
        
            // Move the file to the public/uploads directory
            $request->file('image')->move($destinationDirectory, $imageName);
        
            // Delete existing image file if it exists
            if (!empty($sitecont->image)) {
                $existingImagePath = $destinationDirectory . '/' . $sitecont->image;
                if (file_exists($existingImagePath) && is_file($existingImagePath)) {
                    unlink($existingImagePath);
                }
            }
        
            $sitecont->image = $imageName;
        }

        $sitecont->save();
        
        return redirect()->route('managesitecontent.list')->with('message', 'Site content Updated Successfully !');
    }

    public function destroy($id)
    {
        $locconst = Site_content::find($id); // Find the item by its ID
        if (!$locconst) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $locconst->delete(); // Delete the item

         return redirect()->route('managesitecontent.list')->with('message', 'Site content Removed successfully !.'); // Redirect to the index page with success message
    }

    public function status(Request $request){
        $get_id=$request->id;
        $catstatus=DB::table('site_contents')
        ->select('is_active')
        ->where('id','=',$get_id)
        ->first();
        

        $astatus=$catstatus->is_active;
         if($astatus == '1'){
             $astatus='0'; 
         } else{
             $astatus='1';
         }
         $statusupdate=DB::table('site_contents')
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
