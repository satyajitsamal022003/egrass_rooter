<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Faq;

class ManageFaqController extends Controller
{
    public function list(Request $request){

        $searchtxt = $request->input('searchtxt');

        // Check if search term is present
        if ($searchtxt) {
            $faqlist = Faq::where('lga', 'like', '%' . $searchtxt . '%')->get();
        } else {
            $faqlist = Faq::all();
        }
    
        // Check if the request is an Ajax request
        if ($request->ajax()) {
            return view('admin.managefaqs.filter', compact('faqlist'));
        } else {
            return view('admin.managefaqs.list', compact('faqlist'));
        }
    }

    public function create(){
        return view('admin.managefaqs.add');
    }

    public function store(Request $request){

       $faqadd = new Faq();
       $faqadd->question = $request->input('question');
       $faqadd->answer = $request->input('answer');
       $faqadd->is_active = $request->input('status');
       $faqadd->created_at = now();
       $faqadd->updated_at = now(); 

       $faqadd->save();
        
        return redirect()->route('managefaqs.list')->with('message', 'Faq created Successfully !');
    }

    public function edit($id){
        $faqedit = Faq::find($id);
      return view('admin.managefaqs.edit',compact('faqedit'));
    }

    
    public function update(Request $request,$id){
        $updatefaq = Faq::findOrFail($id);
        $updatefaq->question = $request->input('question');
        $updatefaq->answer = $request->input('answer');
        $updatefaq->is_active = $request->input('status');
        $updatefaq->updated_at = now(); 
 
        $updatefaq->save();
        
        return redirect()->route('managefaqs.list')->with('message', 'Faq Updated Successfully !');
    }

    public function destroy($id)
    {
        $locconst = Faq::find($id); // Find the item by its ID
        if (!$locconst) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $locconst->delete(); // Delete the item

         return redirect()->route('managefaqs.list')->with('message', 'Faq Removed successfully !.'); // Redirect to the index page with success message
    }

    public function status(Request $request){
        $get_id=$request->id;
        $catstatus=DB::table('faqs')
        ->select('is_active')
        ->where('id','=',$get_id)
        ->first();
        

        $astatus=$catstatus->is_active;
         if($astatus == '1'){
             $astatus='0'; 
         } else{
             $astatus='1';
         }
         $statusupdate=DB::table('faqs')
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
