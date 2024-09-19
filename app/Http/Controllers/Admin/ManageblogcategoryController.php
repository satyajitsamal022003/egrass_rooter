<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use Illuminate\Support\Facades\DB;

class ManageblogcategoryController extends Controller
{
    public function create(){
        return view('admin.manageblogcategory.add');
    }

    public function store(Request $request){
        $blogcat = new BlogCategory();
        $blogcat->cat_title = $request->input('title');
        $blogcat->slug = $request->input('slug');
        $blogcat->meta_title = $request->input('metatitle');
        $blogcat->meta_key = $request->input('meta_key');
        $blogcat->meta_desc = $request->input('meta_description');
        $blogcat->is_active = $request->input('status');
        $blogcat->created_at = now();
        $blogcat->updated_at = now(); 
        $blogcat->save();
        
        return back()->with('message', 'Blog Category Added Successfully!');

    }

    public function list(){
        $blogcatlist = BlogCategory::all();
        return view('admin.manageblogcategory.list',compact('blogcatlist'));
    }

    public function edit($id){
        $editblogs = BlogCategory::find($id);
        return view('admin.manageblogcategory.edit',compact('editblogs'));

    }

    public function update(Request $request,$id){
        $blogcatupdate = BlogCategory::findOrFail($id);
        $blogcatupdate->cat_title = $request->input('title');
        $blogcatupdate->slug = $request->input('slug');
        $blogcatupdate->meta_title = $request->input('metatitle');
        $blogcatupdate->meta_key = $request->input('meta_key');
        $blogcatupdate->meta_desc = $request->input('meta_description');
        $blogcatupdate->is_active = $request->input('status');
        $blogcatupdate->updated_at = now(); 
        $blogcatupdate->save();
        
        return back()->with('message', 'Blog Category Updated Successfully!');
    }

    public function destroy($id)
    {
        $blogcat = BlogCategory::find($id); // Find the item by its ID
        if (!$blogcat) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $blogcat->delete(); // Delete the item

         return redirect()->route('manageblogcat.list')->with('success', 'Blog Category deleted successfully.'); // Redirect to the index page with success message
   }

   public function status(Request $request){
    $get_id=$request->id;
    $catstatus=DB::table('blog_categories')
    ->select('is_active')
    ->where('cat_id','=',$get_id)
    ->first();

    // dd($catstatus);
    

    $astatus=$catstatus->is_active;
     if($astatus == '1'){
         $astatus='0'; 
     } else{
         $astatus='1';
     }
     $statusupdate=DB::table('blog_categories')
     ->where('cat_id', $get_id)
     ->update(array('is_active'=>$astatus));

     if($statusupdate){
         return response()->json([
             'status' => 'success',
             'code' => 200,
         ]);
        }
    }



}
