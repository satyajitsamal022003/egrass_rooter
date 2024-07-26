<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AddMember;
use App\Models\Campaign_user;
use App\Models\Role;
use App\Models\Sitesetting;
use App\Models\Team;
use App\Models\TeamInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TeamController extends Controller
{
    public function index(Request $request, $userid)
    {
        // Enable query logging
        DB::enableQueryLog();
        $teamdetails = Team::where('user_id', $userid)->orderBy('id', 'desc')->get();
        $team_data = [];
        if (!$teamdetails->isEmpty()) {
            foreach ($teamdetails as $key => $team) {

                $team_data[$key] = [
                    'id' => $team->id,
                    'name' => $team->name ?? '',
                    'description' => $team->description ?? '',
                    'task' => $team->task ?? '',
                    'address' => $team->address ?? '',
                ];
            }
        }

        return response()->json(['data' => $team_data], 200);
    }


    public function store(Request $request)
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Get the authenticated user's ID
        $userid = $user->id;
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'task' => 'required|string|max:15',
        ]);

        $team = new Team();
        $team->user_id = $userid;
        $team->name = $request->input('name');
        $team->description = $request->input('description');
        $team->task = $request->input('task');
        $team->latitude = $request->input('latitude');
        $team->longitude = $request->input('longitude');
        $team->address = $request->input('address');
        $team->is_active = $request->input('is_active', 0);
        $team->created = now();
        $team->modified = now();

        $team->save();

        return response()->json(['message' => 'Team created successfully', 'data' => $team], 201);
    }


    public function edit($id)
    {
        // Find the member by its ID
        $team = Team::find($id);

        // Check if the team exists
        if (!$team) {
            return response()->json([
                'success' => false,
                'message' => 'Team not found'
            ], 404);
        }

        // Prepare the member data with role and state
        $team_data = [
            'id' => $team->id,
            'name' => $team->name,
            'description' => $team->description,
            'task' => $team->task,
            'latitude' => $team->latitude,
            'longitude' => $team->longitude,
            'address' => $team->address,
        ];

        // Return the response with member data
        return response()->json([
            'success' => true,
            'team_data' => $team_data
        ]);
    }


    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:team,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'task' => 'required|string|max:15',
        ]);

        $team = Team::find($request->id);

        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }


        $team->name = $request->input('name');
        $team->description = $request->input('description');
        $team->task = $request->input('task');
        $team->latitude = $request->input('latitude');
        $team->longitude = $request->input('longitude');
        $team->address = $request->input('address');
        $team->modified = now();

        $team->save();

        return response()->json(['message' => 'Team updated successfully', 'data' => $team], 200);
    }


    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $team = Team::find($request->id);

        if (!$team) {
            return response()->json([
                'success' => false,
                'message' => 'Team not found'
            ], 404);
        }

        $team->delete();

        return response()->json([
            'success' => true,
            'message' => 'Team deleted successfully'
        ]);
    }

    public function inviteTeam(Request $request)
    {
        // Authenticate the user
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userId = $user->id;

        // Validate the request
        $request->validate([
            'team_id' => 'required|integer|exists:team,id',
            'member_id' => 'required|array',
            'member_id.*' => 'exists:add_members,id',
        ]);

        $team_id = $request->team_id;

        DB::transaction(function () use ($request, $team_id, $userId) {
            // Delete existing TeamInvite records for the given team_id
            TeamInvite::where('team_id', $team_id)->delete();

            foreach ($request->member_id as $singleMember) {
                $getMember = AddMember::find($singleMember);

                $teamInvite = new TeamInvite();
                $teamInvite->team_id = $team_id;
                $teamInvite->user_id = $userId;
                $teamInvite->member_id = $singleMember;
                $teamInvite->created = now();
                $teamInvite->modified = now();

                $mailCode = rand(10, 100000);
                $activationLink = url("/api/activate-invite-team?email=" . base64_encode($getMember->email_id) . "&activation_code=" . $mailCode . "&task=1");
                $volunteerLink = url("/api/activate-volunteer?email=" . base64_encode($getMember->email_id) . "&activation_code=" . $mailCode . "&task=1");
                $siteAdmin = Sitesetting::find(1);

                $umsg = "<table width='100%' style='line-height:20px; font-size:12px'>
                        <thead>
                            <th style='height:80px; font-size:24px; background:#'>
                                <center>Member Assign Successful</center>
                            </th>
                        </thead>
                    </table>
                    <table width='100%' style='line-height:20px; font-size:12px'>
                        <tr>
                            <td><h4 style='font-weight:bold;'>Hi " . $getMember->name . ",</h4></td>
                        </tr>";

                if ($getMember->role_type == 1) {
                    $umsg .= "<tr>
                            <td><h4 style='font-weight:bold;'>You are invited to the " . stripslashes($siteAdmin->site_title) . ", Below are the Activation link</h4></td>
                        </tr>
                        <tr>
                            <td><strong>ActivationLink:</strong>" . $activationLink . "</td>
                        </tr>";
                } else {
                    $umsg .= "<tr>
                            <td><h4 style='font-weight:bold;'>You are invited to the " . stripslashes($siteAdmin->site_title) . ", Below are the Activation Details</h4></td>
                        </tr>
                        <tr>
                            <td><strong>ActivationCode:</strong>" . $mailCode . "</td>
                        </tr>";
                }

                $umsg .= "<tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td><strong>Thanking You,</strong></td>
                    </tr>
                    <tr>
                        <td style='color:#78b454;'>The " . stripslashes($siteAdmin->site_title) . " Member</td>
                    </tr>
                </table>";

                $subject = stripslashes($siteAdmin->site_title) . ': Member';
                Mail::send([], [], function ($message) use ($getMember, $subject, $umsg) {
                    $message->to($getMember->email_id)
                        ->subject($subject)
                        ->html($umsg); // Use html() instead of setBody()
                });

                if (!$teamInvite->save()) {
                    throw new \Exception('Error saving team invite');
                }
            }
        });

        return response()->json(['message' => 'Team Invite successfully'], 200);
    }

    public function inviteteamActivate(Request $request)
    {
        $activationCode = $request->activation_code;
        $task = $request->task;
        $mailId = base64_decode($request->email);

        if (!empty($activationCode) && !empty($task)) {
            // Find the member by email
            $member = AddMember::where('email_id', $mailId)->first();
            if (!$member) {
                return response()->json(['message' => 'Member not found'], 404);
            }

            // Find the user by email
            $user = Campaign_user::where('email_id', $mailId)->first();
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Check if the user’s email is already verified
            if ($user->is_mail_verified == 1) {
                return response()->json(['message' => 'Email already verified'], 200);
            }

            // Update the user with the member ID
            $user->update(['member_id' => $member->id]);

            return response()->json(['message' => 'Account verified successfully'], 200);
        }

        return response()->json(['message' => 'Invalid request'], 400);
    }

    public function volunteerActivate(Request $request)
    {
        $activationCode = $request->activation_code;
        $task = $request->task;
        $mailId = base64_decode($request->email);

        if (!empty($activationCode) && !empty($task)) {
            // Find the member by email
            $member = AddMember::where('email_id', $mailId)->first();
            if (!$member) {
                return response()->json(['message' => 'Member not found'], 404);
            }

            // Find the user by email
            $user = Campaign_user::where('email_id', $mailId)->first();
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Check if the user's email is already verified
            if ($user->is_mail_verified == 1) {
                return response()->json(['message' => 'Email already verified'], 200);
            }

            // Update the user with the member ID
            $user->update(['member_id' => $member->id]);

            return response()->json(['message' => 'Account verified successfully'], 200);
        }

        return response()->json(['message' => 'Invalid request'], 400);
    }

    public function listMember(Request $request, $id)
    {
        // Enable query logging
        DB::enableQueryLog();
        $user = Auth::guard('api')->user(); // Adjust 'web' to your authentication guard if different
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Fetch team invites based on team_id
        $teamInvites = TeamInvite::where('team_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        // Collect member IDs from team invites
        $members = $teamInvites->pluck('member_id')->toArray();
        $member_list = [];
        if (!empty($members)) {
            foreach ($members as $key => $member) {
                $memberList = AddMember::where('id', $member)
                    ->orderBy('id', 'desc')
                    ->first();
                // $createdDate = date('d-m-Y H:i:s', strtotime($memberList->created));
                $roleDetails = Role::where('id', $memberList->role_type)->first();;

                $member_list[$key] = [
                    'role' => $roleDetails->role ?? '',
                    'name' => $memberList->name ?? '',
                    'phone_number' => $memberList->phone_number ?? '',
                    'email_id' => $memberList->email_id ?? '',
                    'address' => $memberList->address ?? '',
                ];
            }
        }

        return response()->json(['data' => $member_list], 200);
    }
}
