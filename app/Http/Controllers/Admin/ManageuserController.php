<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Senatorial_state;
use App\Models\Local_constituency;
use App\Models\Party;
use App\Models\Federal_constituency;
use App\Models\Campaign_user;
use App\Models\UserWebsite;
use App\Models\CampaignNext;
use App\Models\Sitesetting;
use Illuminate\Support\Facades\Mail; // Import the Mail facade
use App\Mail\YourMailable;
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email_id' => 'required|email|max:255|unique:campaign_users,email_id',
            'pass'=>'required|min:6',
            'dob'=>'required',
            'address'=>'required',
            'title'=>'required',
            'campaign_type' => 'required',
            'user_type' => 'required',
            'state' => 'required_if:campaign_type,2,3,4,5,6',
            'senatorial_district_id' => 'required_if:campaign_type,2',
            'federal_constituency_id' => 'required_if:campaign_type,3',
            'local_constituency_id' => 'required_if:campaign_type,6',
            'political_party'=>'required'
        ];

        // Define custom error messages
        $messages = [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email_id.required' => 'Email ID is required.',
            'email_id.email' => 'Please enter a valid email address.',
            'pass.required' => 'Password is required.',
            'dob.required' => 'Date of birth is required.',
            'address.required' => 'Address is required.',
            'title.required' => 'Campaign name is required.',
            'pass.min' => 'Password must be at least :min characters.',
            'campaign_type.required' => 'Campaign type is required.',
            'state.required_if' => 'State is required.',
            'senatorial_district_id.required_if' => 'Senatorial state is required.',
            'federal_constituency_id.required_if' => 'Federal constituency is required.',
            'local_constituency_id.required_if' => 'Local constituency area is required.',
            'user_type.required' => 'Please select the user type.',

            // Add custom error messages for other fields if needed...
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules, $messages);

        // Check if the validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }


         // Prepare the data
         $allpost = $request->all();
         $mailid = $allpost['email_id'];
         $allpost['pass'] = base64_encode($allpost['pass']);
         $allpost['is_active'] = 1;
         $allpost['created_at'] = now();
         $allpost['updated_at'] = now();
 
         // Generate username
         $userName = $allpost['first_name'] . $allpost['last_name'];
         $chkUserName = Campaign_user::where('username', $userName)->get();
         if ($chkUserName->count() > 0) {
             $userName .= rand(10, 10000);
         }
 
         // Generate user ID
         $lastUser = Campaign_user::orderBy('id', 'desc')->first();
         if ($lastUser) {
             $customerid = $lastUser->userid;
             $custArr = explode("SO", $customerid);
             $fistID = $custArr[0];
             $secID = intval($custArr[1]) + 1;
             $allpost['userid'] = $fistID . "SO" . $secID;
         } else {
             $allpost['userid'] = "CMSO1001";
         }
 
         $allpost['username'] = $userName;
         $allpost['mail_code'] = base64_encode(rand(10, 100000));
 
         // Save user
         $user = new Campaign_user();
         $user->email_id = $allpost['email_id'];
         $user->pass = $allpost['pass'];
         $user->is_active = $allpost['is_active'];
         $user->created_at = NOW();
         $user->updated_at = NOW();
         $user->userid = $allpost['userid'];
         $user->username = $allpost['username'];
         $user->mail_code = $allpost['mail_code'];
         $user->save();
 
         if ($user) {
             // Prepare email data
             $activationLink = url("pagefront/activate?email=" . base64_encode($allpost['email_id']) . "&activation_code=" . $allpost['mail_code'] . "&task=1");
             $siteAdmin = Sitesetting::find(1);
             $logoImg = $siteAdmin->logo;
             $logo = file_exists(public_path('uploads/siteimage/' . $logoImg))
                 ? '<a href="' . url('/') . '"> <img src="' . url('uploads/siteimage/' . $logoImg) . '" ></a>'
                 : '<a href="' . url('/') . '"><img src="' . url('frontfile/images/logo.jpg') . '"></a>';
             $emailImgpath = url('frontfile/images/email-conf.png');
             $umsg = "<table width='650' style='background:#fff; margin:0px auto; font-family: Open Sans, sans-serif; font-size:13px; line-height:19px;border-collapse: collapse;' border='0' vspace='0'>
                         <tr>
                             <td align='center' style='border-bottom:4px solid #1abc9c; padding:15px 0px 15px;'>
                                 <table width='96%' border='0' cellspacing='0' cellpadding='0'>
                                     <tbody>
                                         <tr>
                                             <td>'" . $logo . "'</td>
                                             <td align='right'><a href='" . url('/') . "' style='color:#163963;'>View in Browser</a></td>
                                         </tr>
                                     </tbody>
                                 </table>
                             </td>
                         </tr>
                         <tr>
                             <td><img src='" . $emailImgpath . "' width='100%' alt=''/></td>
                         </tr>
                         <tr>
                             <td style='padding:10px 25px 25px; font-size:13px; line-height:21px; text-align:center'>
                                 <h1>Email Confirmation</h1>
                                 <p style='font-size:16px; line-height:24px;'>You are almost ready to start using Egrassrooter to manage your political campaign.
                                 <br><br>
                                 Simply click on the button below to verify your email address to continue your registration.</p><br>
                                 <a href='" . $activationLink . "' style='display:inline-block; background-color:#01a29b; color:#fff; font-size:16px; text-decoration:none; padding:13px 20px; border-radius:4px;'>Verify Email Address</a>
                             </td>
                         </tr>
                         <tr>
                             <td style='padding:10px 25px 20px 25px; border-top:2px solid #1abc9c; font-size:14px; line-height:22px; color:#DDDDDD' bgcolor='#15222e' align='center'>
                                 Email sent by Egrassrooter<br>
                                 Copyright © 2019 Egrassrooter.com. All rights reserved
                             </td>
                         </tr>
                     </table>";
             $subject = stripslashes($siteAdmin->site_title) . ': registration';
 
           // Prepare email data
                $activationLink = url("pagefront/activate?email=" . base64_encode($allpost['email_id']) . "&activation_code=" . $allpost['mail_code'] . "&task=1");

                // Send email
                Mail::send('admin.emails.your_mailable', ['activationLink' => $activationLink, 'subject' => $subject, 'umsg' => $umsg], function ($message) use ($allpost, $subject) {
                    $message->to($allpost['email_id'])
                        ->subject($subject);
                });
 
             // Save campaign
             $campaign = new UserWebsite();
             $campaign->title = $allpost['title'];
             $campaign->slug = $allpost['slug'];
             $campaign->user_id = $user->id;
             $campaign->save();
 
             // Save campaign next
             $campaignNext = new CampaignNext();
             $campaignNext->user_id = $user->id;
             $campaignNext->senatorial_district_id = $allpost['senatorial_district_id'] ?? 0;
             $campaignNext->federal_constituency_id = $allpost['federal_constituency_id'] ?? 0;
             $campaignNext->local_constituency_id = $allpost['local_constituency_id'] ?? 0;
             $campaignNext->save();
 
             return redirect()->route('users.index')->with('message', 'User created successfully!');
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
