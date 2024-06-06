<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use Illuminate\Support\Facades\DB;

class ManagetestimonialController extends Controller
{
    public function create(){
        return view('admin.managetestimonial.add');
    }

    public function store(Request $request){
        $testimonial = new Testimonial();
        $testimonial->client_name = $request->input('clientname');
        $testimonial->position = $request->input('clientdesig');
        $testimonial->description = $request->input('msg_desc');
        $testimonial->is_active = $request->input('status');
        $testimonial->created_at = now();
        $testimonial->updated_at = now(); 

        if ($request->hasFile('clientimage')) {
            // Generate a unique file name for the image
            $imageName = 'testimonials_' . time() .'.'.$request->file('clientimage')->getClientOriginalExtension();
            
            $destinationDirectory = public_path('images/testimonials');
        
            if (!is_dir($destinationDirectory)) {
                mkdir($destinationDirectory, 0777, true);
            }
        
            // Move the file to the public/uploads directory
            $request->file('clientimage')->move($destinationDirectory, $imageName);
        
            $testimonial->client_image = $imageName;
        }
        $testimonial->save();
        
        return back()->with('message', 'Testimonial Added Successfully!');
    }

    public function list(){
        $testimonials = Testimonial::all();
        return view('admin.managetestimonial.list',compact('testimonials'));
    }

    public function edit($id){
        $edittestimonial = Testimonial::find($id);
        return view('admin.managetestimonial.edit',compact('edittestimonial'));
    }

    public function update(Request $request,$id){
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->client_name = $request->input('clientname');
        $testimonial->position = $request->input('clientdesig');
        $testimonial->description = $request->input('msg_desc');
        $testimonial->is_active = $request->input('status');
        $testimonial->created_at = now();
        $testimonial->updated_at = now(); 

        if ($request->hasFile('clientimage')) {
            // Generate a unique file name for the image
            $imageName = 'testimonials_' . time() .'.'.$request->file('clientimage')->getClientOriginalExtension();
            
            $destinationDirectory = public_path('images/testimonials');
        
            if (!is_dir($destinationDirectory)) {
                mkdir($destinationDirectory, 0777, true);
            }
        
            // Move the file to the public/uploads directory
            $request->file('clientimage')->move($destinationDirectory, $imageName);

             // Delete existing image file if it exists
             if (!empty($testimonial->client_image)) {
                $existingImagePath = $destinationDirectory . '/' . $testimonial->client_image;
                if (file_exists($existingImagePath) && is_file($existingImagePath)) {
                    unlink($existingImagePath);
                }
            }
        
            $testimonial->client_image = $imageName;
        }
        $testimonial->save();
        
        return back()->with('message', 'Testimonial Updated Successfully!');
    }


    public function destroy($id)
    {
        $testimonial = Testimonial::find($id); // Find the item by its ID
        if (!$testimonial) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $testimonial->delete(); // Delete the item

         return redirect()->route('managetestimonial.list')->with('success', 'Testimonial deleted successfully.'); // Redirect to the index page with success message
    }

    public function status(Request $request){
        $get_id=$request->id;
        $catstatus=DB::table('testimonials')
        ->select('is_active')
        ->where('id','=',$get_id)
        ->first();
        
    
        $astatus=$catstatus->is_active;
         if($astatus == '1'){
             $astatus='0'; 
         } else{
             $astatus='1';
         }
         $statusupdate=DB::table('testimonials')
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
