<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AddMember;
use App\Models\Local_constituency;
use App\Models\Polling_unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PollingAgent;
use App\Models\Role;
use App\Models\State;
use App\Models\Ward;

class PollingAgentController extends Controller
{
    public function storepollingagent(Request $request)
    {
        // Check if the user is authenticated
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }


        // Validate the incoming request data
        $request->validate([
            'polling_agents' => 'required', // Adjust as needed
            'polling_units' => 'required', // Adjust as needed
            'polling_agent_type' => 'required', // Adjust as needed
        ]);

        // Store the data in the PollingAgent model
        $pollingAgent = new PollingAgent();
        $pollingAgent->email = $request->input('email');
        $pollingAgent->polling_agents = $request->input('polling_agents');
        $pollingAgent->polling_units = $request->input('polling_units');
        $pollingAgent->user_id = $user->id;
        $pollingAgent->polling_agent_type = $request->input('polling_agent_type');

        $pollingAgent->save();

        return response()->json(['message' => 'Polling agent stored successfully'], 200);
    }


    public function pollingagentlist()
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        // Retrieve polling agents where is_active = 1 and user_id matches the provided $userid
        $pollingagents = PollingAgent::where('user_id', $user->id)
            ->get();

        $allpollingagents = [];

        if ($pollingagents) {
            foreach ($pollingagents as $agent) {
                $allpollingagents[] = [
                    'id' => $agent->id,
                    'email' => $agent->email,
                    'polling_agent' => $agent->polling_agents,
                    'polling_unit' => $agent->polling_units,
                    'polling_agent_type' => $agent->polling_agent_type,
                    'state' => $agent->state,
                    'lga' => $agent->voting_local_govt,
                    'ward' => $agent->ward,
                    'status' => $agent->is_active,
                    // Add any additional fields you need to format
                ];
            }
        }

        // Return the formatted list of polling agents as a JSON response
        return response()->json([
            'message' => 'Polling agent list retrieved successfully',
            'pollingagents' => $allpollingagents
        ], 200);
    }

    public function pollingagentemailvin(Request $request)
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userid = $user->id;
        $emailvin = $request->get('email_vin');
        $search = $request->get('search'); 

        $emailpollingagents = PollingAgent::where('user_id', $userid)
            ->where('role_type', 3)
            ->where('email', $emailvin)
            ->get();

        $pollingagentvinoremail = [];

        if ($emailvin == 'email') {
            if ($emailpollingagents) {
                foreach ($emailpollingagents as $agent) {
                    $pollingagentvinoremail[] = [
                        'id' => $agent->id,
                        'email' => $agent->email ?? '',
                    ];
                }
            }
        } else {
            if ($emailpollingagents) {
                foreach ($emailpollingagents as $agent) {
                    $pollingagentvinoremail[] = [
                        'id' => $agent->id,
                        'vin' => $agent->email ?? '',
                    ];
                }
            }
        }

        $pollingunitsQuery = Polling_unit::query();

        if ($user->campaign_type != 1) {
            $pollingunitsQuery->where('state_id', $user->state);
        }

        if ($search) {
            $pollingunits = $pollingunitsQuery->where('polling_name', 'like', "%$search%")->get();
        } else {
            $pollingunits = $pollingunitsQuery->take(25)->get();
        }

        $allpollingunits = $pollingunits->map(function ($pu) {
            return [
                'id' => $pu->id,
                'pollingunit_name' => $pu->polling_name ?? '',
            ];
        });

        return response()->json([
            'message' => 'Polling unit retrieved successfully',
            'pollingagentsvinoremail' => $pollingagentvinoremail,
            'polling_unit' => $allpollingunits
        ], 200);
    }


    public function editpollingagent($pollingagentid)
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $pollingagentdata = PollingAgent::where('id', $pollingagentid)->first();

        if ($pollingagentdata) {
            $pollingagentemail = AddMember::where('id', $pollingagentdata->polling_agents)->first();
        } else {
            return response()->json(['error' => 'Polling Agent not found'], 404);
        }

        $pollingagenteditdata = [
            'pollingagent_id' => $pollingagentdata->id ?? '',
            'polling_unit_id' => $pollingagentdata->polling_units ?? '',
            'role_type_id' => $pollingagentdata->role_type ?? '',
            'member_name' => $pollingagentdata->name ?? '',
            'gender' => $pollingagentdata->gender ?? '',
            'phone_number' => $pollingagentdata->phone_number ?? '',
            'email_id' => $pollingagentemail->email_id ?? '',
            'date_of_birth' => $pollingagentdata->dob ?? '',
            'voter_id_number' => $pollingagentemail->voters_id_number ?? '',
            'voting_state' => $pollingagentdata->state ?? '',
            'voting_local_government' => $pollingagentdata->voting_local_govt ?? '',
            'voting_registration_area' => $pollingagentdata->ward ?? '',
            'code' => $pollingagentdata->code ?? '',
            'occupation' => $pollingagentdata->occupation ?? '',
            'date_of_registration' => $pollingagentdata->date_of_registration ?? '',
            'address' => $pollingagentdata->address ?? '',
            'political_party' => $pollingagentdata->political_party ?? '',
        ];

        $role = Role::where('is_active', 1)->get();

        $roletype = $role->map(function ($r) {
            return [
                'id' => $r->id,
                'role_type' => $r->role ?? '',
            ];
        });

        if ($user->campaign_type != 1) {
            $states = State::where('id', $user->state)->first();

            $statedata = [
                'id' => $states->id,
                'state_name' => $states->state ?? '',
            ];
        } else {
            $states = State::get();

            $statedata = $states->map(function ($state) {
                return [
                    'id' => $state->id,
                    'state_name' => $state->state ?? '',
                ];
            });
        }
        return response()->json([
            'message' => 'Polling agent edit data retrieved successfully',
            'pollingagenteditdata' => $pollingagenteditdata,
            'roletype' => $roletype,
            'statedata' => $statedata
        ], 200);
    }


    public function pollingagentgetlga($stateid)
    {

        $lga =  Local_constituency::where('state_id', $stateid)->get();

        $lgadata = $lga->map(function ($l) {
            return [
                'id' => $l->id,
                'lga_name' => $l->lga ?? '',
            ];
        });

        return response()->json([
            'message' => 'Polling agent Lga data retrieved successfully',
            'lgadata' => $lgadata
        ], 200);
    }

    public function pollingagentgetward($lgaid)
    {
        $ward =  Ward::where('lga_id', $lgaid)->get();

        $warddata = $ward->map(function ($wa) {
            return [
                'id' => $wa->id,
                'ward_name' => $wa->ward_details ?? '',
            ];
        });

        return response()->json([
            'message' => 'Polling agent ward data retrieved successfully',
            'warddata' => $warddata
        ], 200);
    }


    public function pollingagentupdate(Request $request, $pollingagentid)
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }


        $request->validate([
            'polling_unit_id' => 'required',
            'role_type_id' => 'required',
            'member_name' => 'required',
            'gender' => 'required',
            'phone_number' => 'required',
            'voting_state' => 'required',
            'voting_local_government' => 'required',
            'voting_registration_area' => 'required',
            'code' => 'required',
            'occupation' => 'required',
            'date_of_registration' => 'required',
            'address' => 'required',
            'political_party' => 'required',
        ]);

        // Update the data in the PollingAgent model
        $pollingAgent = PollingAgent::find($pollingagentid);
        $pollingAgent->polling_units = $request->input('polling_unit_id');
        $pollingAgent->role_type = $request->input('role_type_id');
        $pollingAgent->name = $request->input('member_name');
        $pollingAgent->gender = $request->input('gender');
        $pollingAgent->phone_number = $request->input('phone_number');
        $pollingAgent->dob = $request->input('date_of_birth');
        $pollingAgent->state = $request->input('voting_state');
        $pollingAgent->voting_local_govt = $request->input('voting_local_government');
        $pollingAgent->ward = $request->input('voting_registration_area');
        $pollingAgent->code = $request->input('code');
        $pollingAgent->occupation = $request->input('occupation');
        $pollingAgent->date_of_registration = $request->input('date_of_registration');
        $pollingAgent->address = $request->input('address');
        $pollingAgent->political_party = $request->input('political_party');
        $pollingAgent->updated = NOW();
        $pollingAgent->save();


        $pollingagentdata = PollingAgent::where('id', $pollingagentid)->first();

        if ($pollingagentdata) {
            $pollingagent = AddMember::where('id', $pollingagentdata->polling_agents)->first();

            if ($pollingagent) {

                $request->validate([
                    'email_id' => 'required|unique:add_members,email_id,' . $pollingagent->id,
                    'voters_id_number' => 'required|unique:add_members,voters_id_number,' . $pollingagent->id,
                ]);

                $pollingagent->update([
                    'email_id' => $request->input('email_id'),
                    'voters_id_number' => $request->input('voter_id_number')
                ]);
            } else {
                return response()->json(['error' => 'Member not found'], 404);
            }
        } else {
            return response()->json(['error' => 'Polling Agent not found'], 404);
        }

        return response()->json(['message' => 'Polling agent Updated successfully'], 200);
    }
}
