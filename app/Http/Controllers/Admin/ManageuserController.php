<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Senatorial_state;
use App\Models\Local_constituency;
use App\Models\Party;
use App\Models\Federal_constituency;
use App\Models\Campaign_user;
use Validator;

class ManageuserController extends Controller
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
            return view('admin.manageusers.filter', compact('faqlist'));
        } else {
            return view('admin.manageusers.list', compact('faqlist'));
        }
    }

    public function create(){

        $localConList = Local_constituency::orderBy('lga')->get();

        $getAllParty = Party::orderBy('party_name')->get();

        $federalList = Federal_constituency::orderBy('federal_name')->get();
        return view('admin.manageusers.add',compact('localConList','getAllParty','federalList'));
    }

    public function store(Request $request){

        // Define validation rules
        $rules = [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email_id' => 'required|email|max:255',
            'password'=>'required|min:6',
            'dob'=>'required',
            'address'=>'required',
            'campaign_name'=>'required',
            'campaign_type' => 'required|numeric',
            'user_type' => 'required',
            'state_id' => 'required_if:campaign_type,2,3,4,5,6|numeric',
            'senotorial_state' => 'required_if:campaign_type,2|numeric',
            'federal_const' => 'required_if:campaign_type,3|numeric',
            'lca' => 'required_if:campaign_type,6|numeric',
            'political_party'=>'required'
        ];

        // Define custom error messages
        $messages = [
            'firstname.required' => 'First name is required.',
            'lastname.required' => 'Last name is required.',
            'email_id.required' => 'Email ID is required.',
            'email_id.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
            'dob.required' => 'Date of birth is required.',
            'address.required' => 'Address is required.',
            'campaign_name.required' => 'Campaign name is required.',
            'password.min' => 'Password must be at least :min characters.',
            'campaign_type.required' => 'Campaign type is required.',
            'state_id.required_if' => 'State is required.',
            'senotorial_state.required_if' => 'Senatorial state is required.',
            'federal_const.required_if' => 'Federal constituency is required.',
            'lca.required_if' => 'Local constituency area is required.',
            'user_type.required' => 'Please select the user type.',

            // Add custom error messages for other fields if needed...
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);

        // Check if the validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        

      
        
        
        return back()->with('message', 'User created Successfully !');
    }

    public function edit($id){
        $faqedit = Faq::find($id);
      return view('admin.manageusers.edit',compact('faqedit'));
    }

    
    public function update(Request $request,$id){
        $updatefaq = Faq::findOrFail($id);
        $updatefaq->question = $request->input('question');
        $updatefaq->answer = $request->input('answer');
        $updatefaq->is_active = $request->input('status');
        $updatefaq->updated_at = now(); 
 
        $updatefaq->save();
        
        return redirect()->route('manageusers.list')->with('message', 'Faq Updated Successfully !');
    }

    public function destroy($id)
    {
        $locconst = Faq::find($id); // Find the item by its ID
        if (!$locconst) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $locconst->delete(); // Delete the item

         return redirect()->route('manageusers.list')->with('message', 'Faq Removed successfully !.'); // Redirect to the index page with success message
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


        public function getsenstates(Request $request)
        {
            $stateId = $request->input('id');
            $stateconst = Senatorial_state::where('state_id', $stateId)->get();
            
            if ($stateconst->isEmpty()) {
                return response()->json(['code' => 200, 'status' => []]);
            }
    
            return response()->json(['code' => 200, 'status' => $stateconst]);
        }

     public function checkSlug(Request $request)
        {
            $title = $request->input('title');
            $editid = $request->input('editid');

            // Check if the slug already exists in the database
            $existingSlug = Campaign_user::where('slug', $title)->exists();

            if ($existingSlug) {
                return response()->json(2); // Slug already exists
            } else {
                return response()->json($title); // Slug is unique
            }
        }

}
