<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPassword;
use App\Mail\RegistrationEmail;
use Illuminate\Http\Request;
use App\Models\Campaign_user;
use App\Models\Sitesetting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Party;
use App\Models\State;
use App\Models\Senatorial_state;
use App\Models\Federal_constituency;
use App\Models\Local_constituency;
use App\Models\ElectionType;
use App\Models\Role;

class ApiAuthController extends Controller
{
    public function getcampaign()
    {
        $politicalparties = Party::where('is_active', 1)->get();

        $partydata = [];
        foreach ($politicalparties as $party) {
            $partydata[] = [
                'id' => $party->id,
                'party_name' => $party->party_name,
                'party_name' => $party->party_name,
                'owner_name' => $party->owner_name,
                'party_img' => $party->party_img,
                'candidate_img' => $party->candidate_img,
                'color' => $party->color,
            ];
        }

        $states = State::get();

        $statedata = [];
        foreach ($states as $s) {
            $statedata[] = [
                'id' => $s->id,
                'name' => $s->state,
            ];
        }

        $electiontype = ElectionType::get();

        $electiontypedata = [];
        foreach ($electiontype as $s) {
            $electiontypedata[] = [
                'id' => $s->id,
                'electiontype' => $s->type,
            ];
        }

        $roles = Role::where('is_active', 1)->get();

        $role_type = [];
        foreach ($roles as $r) {
            $role_type[] = [
                'id' => $r->id,
                'role_name' => $r->role,
            ];
        }

        return response()->json([
            'partydata' => $partydata,
            'statedata' => $statedata,
            'electiontypedata' => $electiontypedata,
            'role_type_data' => $role_type
        ]);
    }

    public function getsenatorialstates($stateid)
    {
        $senatorialstates = Senatorial_state::where('state_id', $stateid)->get();

        $senatorialstatedata = [];
        foreach ($senatorialstates as $s) {
            $senatorialstatedata[] = [
                'id' => $s->id,
                'name' => $s->sena_district,
            ];
        }

        return response()->json([
            'senatorialstatedata' => $senatorialstatedata
        ]);
    }

    public function getfederalconstituency($stateid)
    {
        $federalconstituency = Federal_constituency::where('state_id', $stateid)->get();

        $federalconstituencydata = [];
        foreach ($federalconstituency as $s) {
            $federalconstituencydata[] = [
                'id' => $s->id,
                'name' => $s->federal_name,
            ];
        }

        return response()->json([
            'federalconstituencydata' => $federalconstituencydata
        ]);
    }

    public function getlocalconstituency($stateid)
    {
        $localconstituency = Local_constituency::where('state_id', $stateid)->get();

        $localconstituencydata = [];
        foreach ($localconstituency as $s) {
            $localconstituencydata[] = [
                'id' => $s->id,
                'name' => $s->lga,
            ];
        }

        return response()->json([
            'localconstituencydata' => $localconstituencydata
        ]);
    }



    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'user_type' => 'required|string|max:255',
                'date_of_registration' => 'required',
                'email' => 'required|string|email|max:255|unique:campaign_users,email_id',
                'phone_number' => 'required|string|max:15|unique:campaign_users,telephone',
                'pass' => 'required|string|confirmed|min:8',
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255',
                'campaign_type' => 'required|string|max:255',
            ]);

            $campaignname = $validatedData['title'];
            $existingcampaignname = Campaign_user::where('title', $campaignname)->first();
            if ($existingcampaignname) {
                return response()->json([
                    'success' => false,
                    'message' => 'Campaign Name Already Taken!',
                ], 409);
            }

            $email = $validatedData['email'];
            $existingUser = Campaign_user::where('email_id', $email)->first();
            if ($existingUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email ID already exists!',
                ], 409);
            }

            $userName = $validatedData['first_name'] . $validatedData['last_name'];
            $existingUserName = Campaign_user::where('username', $userName)->first();

            if ($existingUserName) {
                $userName .= rand(10, 10000);
            }

            $lastUser = Campaign_user::orderBy('id', 'desc')->first();
            if ($lastUser) {
                $lastUserId = $lastUser->userid;
                $userIdParts = explode('SO', $lastUserId);
                $prefix = $userIdParts[0];
                $idNumber = (int)$userIdParts[1] + 1;
                $userid = $prefix . 'SO' . $idNumber;
            } else {
                $userid = 'CMSO1001';
            }

            // Generate email confirmation link
            $mail_code = base64_encode(rand(10, 100000));
            $activationLink = url('/api/activate?email=' . base64_encode($validatedData['email']) . '&activation_code=' . $mail_code . '&task=1');

            $user = Campaign_user::create([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'user_type' => $validatedData['user_type'],
                'date_of_registration' => Carbon::createFromFormat('Y-m-d', $validatedData['date_of_registration'])->format('Y-m-d'),
                'email_id' => $validatedData['email'],
                'telephone' => $validatedData['phone_number'],
                'pass' => md5($validatedData['pass']),
                'username' =>  $userName,
                'mail_code' =>  $mail_code,
                'userid' => $userid,
                'title' => $validatedData['title'],
                'slug' => $validatedData['slug'],
                'campaign_type' => $validatedData['campaign_type'],
                'state' => $request->input('state', 0),
                'senatorial_district_id' => $request->input('senatorial_district_id'),
                'federal_constituency_id' => $request->input('federal_constituency_id'),
                'local_constituency_id' => $request->input('local_constituency_id'),
                'ngo_name' => $request->input('ngo'),
                'political_party' => $request->input('political_party'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            // Get site settings
            $siteSetting = Sitesetting::find(1);
            $logoImg = $siteSetting->logo;
            if (file_exists(asset('/images/siteimage/' . $logoImg))) {
                $logo = '<a href="' . url('/') . '"> <img src="' . asset('/images/siteimage/' . $logoImg) . '" ></a>';
            } else {
                $logo = '<a href="' . url('/') . '"><img src="' . asset('/images/logo.png') . '"></a>';
            }

            // Create email content
            $emailImgPath = asset('/egrassrooter.png');
            $emailContent = "<table width='650' style='background:#fff; margin:0px auto; font-family: Open Sans, sans-serif; font-size:13px; line-height:19px;border-collapse: collapse;' border='0' vspace='0'>
                <tr>
                    <td align='center' style='border-bottom:4px solid #1abc9c; padding:15px 0px 15px;'>
                        <table width='96%' border='0' cellspacing='0' cellpadding='0'>
                            <tbody>
                                <tr>
                                    <td>" . $logo . "</td>
                                    <td align='right'><a href='" . url('/') . "' style='color:#163963;'>View in Browser</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                  <td><img src='" . $emailImgPath . "' width='100%'  alt=''/></td>
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
                  Copyright © 2019 Egrassrooter.com. All rights reserved </td>
                </tr>
            </table>";

            $subject = stripslashes($siteSetting->site_title) . ': Registration';

            // Send email
            // Mail::send([], [], function ($message) use ($validatedData, $subject, $emailContent) {
            //     $message->to($validatedData['email'])
            //         ->subject($subject)
            //         ->setBody($emailContent, 'text/html');
            // });

            Mail::to($validatedData['email'])->send(new RegistrationEmail($activationLink, $logo, $emailContent));

            // Create notification
            // $notificationData = [
            //     'user_id' => 0,
            //     'notification_type' => 'Registration',
            //     'status' => 3, // Show to admin only
            //     'admin_status' => 2,
            //     'name' => 'New User registered',
            //     'member_id' => 0,
            //     'desc' => 'New User registered',
            //     'date' => Carbon::now()->format('Y-m-d'),
            //     'created_at' => now(),
            //     'updated_at' => now()
            // ];

            // Notification::create($notificationData);

            // Generate token
            // $token = $user->createToken('auth_token')->plainTextToken;
            // if (!$token = JWTAuth::fromUser($user)) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Could not create token',
            //     ], 500);
            // }

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully, Please check and Verify your Mail',
                // 'token' => $token
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function activate(Request $request)
    {
        try {
            if (isset($request->activation_code) && isset($request->task)) {
                if (!empty($request->activation_code) && !empty($request->task)) {
                    $email = base64_decode($request->email);
                    $activationCode = $request->activation_code;

                    $user = Campaign_user::where('email_id', $email)
                        ->where('mail_code', $activationCode)
                        ->first();

                    if ($user) {
                        if ($user->is_mail_verified != 1) {
                            $user->is_mail_verified = 1;
                            $user->save();

                            // Redirect to the Next.js URL with a success message
                            return redirect('http://localhost:3000/login?status=success&message=Account%20Verified%20Successfully');
                        } else {
                            // Redirect to the Next.js URL with an already verified message
                            return redirect('http://localhost:3000/login?status=error&message=Account%20is%20already%20verified');
                        }
                    } else {
                        // Redirect to the Next.js URL with an invalid activation error message
                        return redirect('http://localhost:3000/login?status=error&message=Invalid%20activation%20code%20or%20email');
                    }
                }
            }

            // Redirect to the Next.js URL with a general invalid request error message
            return redirect('http://localhost:3000/login?status=error&message=Invalid%20request.%20Activation%20code%20and%20task%20are%20required');
        } catch (\Exception $e) {
            Log::error('Activation error: ' . $e->getMessage());

            // Redirect to the Next.js URL with a generic error message
            return redirect('http://localhost:3000/login?status=error&message=Activation%20failed');
        }
    }


    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $user = Campaign_user::where('email_id', $credentials['email'])->first();

            if (!$user || md5($credentials['password']) !== $user->pass) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials',
                ], 401);
            }

            // Generate JWT token
            if (!$token = JWTAuth::fromUser($user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Could not create token',
                ], 500);
            }
            // $decodedToken = JWTAuth::setToken($token)->getPayload()->toArray();
            // dd($decodedToken);

            return response()->json([
                'success' => true,
                'message' => 'User logged in successfully',
                'token' => $token,
                'user_details' => [
                    'id' => $user->id,
                    'email_id' => $user->email_id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'user_type' => $user->user_type,
                    'telephone' => $user->telephone,
                    'campaign_type' => $user->campaign_type,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'current_password' => 'required',
            'new_password' => 'required|different:current_password',
            'retype_new_password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $currentpassword = $request->current_password;
        // dd($currentpassword);
        $newpassword = $request->new_password;
        $confirmpassword = $request->retype_new_password;
        if ($request->id) {
            $chk_user =  Campaign_user::find($request->id);
            // $check = Hash::check($currentpassword, $chk_user->password);
            $check = (md5($currentpassword) === $chk_user->pass);
            if ($check) {
                if ($newpassword == $confirmpassword) {
                    Campaign_user::where('id', $request->id)->update(['pass' => md5($newpassword)]);
                    return response()->json([
                        'message' => 'Password Changed Successfully.',
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'New password and confirm password are not same',
                    ], 400);
                }
            } else {
                return response()->json(['message' => 'The Current Password you have entered is not correct'], 400);
            }
        } else {
            return response()->json([
                'message' => 'Sorry !! We could not found this user in our system.',
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = Auth::user();

            // Ensure the user is authenticated
            if ($user) {
                // Revoke the token that was used to authenticate the current request
                $request->user()->currentAccessToken()->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'User logged out successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getProfile($id)
    {
        $user = Campaign_user::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $profile = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'gender' => $user->gender,
            'dob' => $user->dob,
            'telephone' => $user->telephone,
            'email_id' => $user->email_id,
            'residential_address' => $user->address,
            'nationality' => $user->nationality,
            'code' => $user->code,
            'pu_code' => $user->pu_code,
            'employment' => $user->occupation,
            'registration_date' => $user->date_of_registration,
            'political_party' => $user->political_party,
        ];

        return response()->json([
            'success' => true,
            'profile' => $profile
        ]);
    }


    public function updateProfile(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string|max:10',
            'dob' => 'required|date',
            'telephone' => 'required|string|max:20',
            'email_id' => 'required|string|email|max:255',
            'residential_address' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'pu_code' => 'required|string|max:255',
            'employment' => 'required|string|max:255',
            'registration_date' => 'required|date',
            'political_party' => 'required|string|max:255',
        ]);

        $user = Campaign_user::find($request->id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->gender = $request->gender;
        $user->dob = $request->dob;
        $user->telephone = $request->telephone;
        $user->email_id = $request->email_id;
        $user->address = $request->residential_address;
        $user->nationality = $request->nationality;
        $user->vin = $request->code;
        $user->pu_code = $request->pu_code;
        $user->occupation = $request->employment;
        $user->date_of_registration = $request->registration_date;
        $user->political_party = $request->political_party;
        $user->save();


        if ($user->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile'
            ], 500);
        }
    }


    public function forgotPasswordSendMail(Request $request)
    {
        try {
            $request->validate([
                'email_id' => 'required|string|email',
            ]);

            $email = $request->input('email_id');
            $user = Campaign_user::where('email_id', $email)->where('is_active', 1)->first();
            // dd($user);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'A user with this email address was not found.',
                ], 404);
            }

            $siteAdmin = Sitesetting::find(1);
            if (!$siteAdmin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Site settings not found.',
                ], 500);
            }

            // $resetLink = url('/api/reset-password/' . base64_encode($user->id));
            $resetLink = url('/Reset-password/' . base64_encode($user->id));

            $body = "
            <table width='100%'  style='line-height:20px; font-size:12px'>
              <thead>
                  <th style='height:80px; font-size:24px; background:#'>
                      <center>Welcome To Campaign Software</center>
                  </th>
              </thead>
            </table>
            <table width='100%'  style='line-height:20px; font-size:12px'>
              <tr>
                  <td><h4 style='font-weight:bold;'>Hi " . stripslashes(ucwords($user->first_name)) . ",</h4></td>
              </tr>
               <tr>
                  <td><strong>You have requested for your login credential</strong></td>
              </tr>
               <tr>
                  <td>&nbsp;</td>
              </tr>
              <tr>
                  <td><strong>Login ID: </strong>" . $user->email_id . "</td>
              </tr>
              <tr>
                  <td>Please click the below link to set new password.<br>
                    " . $resetLink . "
                  </td>
              </tr>
              <tr>
                  <td>&nbsp;</td>
              </tr>
              <tr>
                  <td ><strong>Thanking You,</strong></td>
              </tr>
              <tr>
                  <td style='color:#78b454;'>Campaign Software Team</td>
              </tr>
            </table>";

            Mail::to($email)->send(new ForgotPassword($body));

            return response()->json([
                'success' => true,
                'message' => 'Your Password request sent successfully. Please check your E-Mail to get the Credentials.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Password reset failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function resetPassword(Request $request, $userid)
    {
        try {
            $validator = Validator::make($request->all(), [
                // 'email' => 'required|string|email',
                'npwd' => 'required|string|min:6',
                'cpwd' => 'required|string|same:npwd',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $decodedUserId = base64_decode($userid);
            $user = Campaign_user::where('id', $decodedUserId)
                // ->where('email_id', $request->input('email'))
                ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wrong Username. Please try again'
                ], 404);
            }

            $user->pass = md5($request->input('npwd'));
            if ($user->save()) {
                return redirect('http://localhost:3000/login?status=success&message=Password%20Changed%20Successfully');
                // return response()->json([
                //     'success' => true,
                //     'message' => 'Password Changed Successfully'
                // ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to change the Password'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Password change failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getslug(Request $request, $slug)
    {
        // Check if the slug already exists in the database
        $existingSlug = Campaign_user::where('slug', $slug)->first();

        if ($existingSlug) {
            // If the slug already exists, return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'The slug is already in use. Please choose a different one.'
            ], 409); // 409 Conflict
        }

        // If the slug is unique, you can proceed with your logic
        return response()->json([
            'status' => 'success',
            'message' => 'The slug is unique and available for use.'
        ], 200); // 200 OK
    }

    public function checkemail(Request $request, $email)
    {
        // Check if the slug already exists in the database
        $existingemail = Campaign_user::where('email_id', $email)->first();

        if ($existingemail) {
            // If the slug already exists, return an error response
            return response()->json([
                'status' => 'error',
                'message' => 'The Email ID is already in use. Please choose a different one.'
            ], 409); // 409 Conflict
        }

        // If the slug is unique, you can proceed with your logic
        return response()->json([
            'status' => 'success',
            'message' => 'The email is unique and available for use.'
        ], 200); // 200 OK
    }
}
