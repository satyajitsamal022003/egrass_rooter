<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogTag;
use Illuminate\Support\Facades\DB;

class ManageblogtagController extends Controller
{
    public function create(){
        return view('admin.manageblogtag.add');
    }

    public function store(Request $request){
        $blogcat = new BlogTag();
        $blogcat->tag_title = $request->input('title');
        $blogcat->slug = $request->input('slug');
        $blogcat->meta_title = $request->input('metatitle');
        $blogcat->meta_key = $request->input('meta_key');
        $blogcat->meta_desc = $request->input('meta_description');
        $blogcat->is_active = $request->input('status');
        $blogcat->created_at = now();
        $blogcat->updated_at = now(); 
        $blogcat->save();
        
        return back()->with('message', 'Blog Tag Added Successfully!');
    }

    public function edit($id){
        $editblogtag = BlogTag::find($id);
        return view('admin.manageblogtag.edit',compact('editblogtag'));
    }

    public function list(){
        $blogtaglist = BlogTag::all();
        return view('admin.manageblogtag.list',compact('blogtaglist'));
    }

    public function update(Request $request,$id){
        $blogtagupdate = BlogTag::findOrFail($id);
        $blogtagupdate->tag_title = $request->input('title');
        $blogtagupdate->slug = $request->input('slug');
        $blogtagupdate->meta_title = $request->input('metatitle');
        $blogtagupdate->meta_key = $request->input('meta_key');
        $blogtagupdate->meta_desc = $request->input('meta_description');
        $blogtagupdate->is_active = $request->input('status');
        $blogtagupdate->updated_at = now(); 
        $blogtagupdate->save();
        
        return back()->with('message', 'Blog Tag Updated Successfully!');
    }

    public function destroy($id)
    {
        $blogtag = BlogTag::find($id); // Find the item by its ID
        if (!$blogtag) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $blogtag->delete(); // Delete the item

         return redirect()->route('manageblogtag.list')->with('success', 'Blog Category deleted successfully.'); // Redirect to the index page with success message
   }

   public function status(Request $request){
    $get_id=$request->id;
    $catstatus=DB::table('blog_tags')
    ->select('is_active')
    ->where('tag_id','=',$get_id)
    ->first();
    

    $astatus=$catstatus->is_active;
     if($astatus == '1'){
         $astatus='0'; 
     } else{
         $astatus='1';
     }
     $statusupdate=DB::table('blog_tags')
     ->where('tag_id', $get_id)
     ->update(array('is_active'=>$astatus));

     if($statusupdate){
         return response()->json([
             'status' => 'success',
             'code' => 200,
         ]);
        }
    }
}
