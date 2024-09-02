<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AddMember;
use App\Models\Local_constituency;
use App\Models\Party;
use App\Models\Polling_unit;
use App\Models\Role;
use App\Models\Senatorial_state;
use App\Models\State;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userid = $user->id;
        // Enable query logging
        DB::enableQueryLog();
        $query = AddMember::where('user_id', $userid);

        if ($request->has('search')) {
            $searchtxt = $request->get('searchtxt', '');
            $role_type = $request->get('role_type', '');

            if (!empty($searchtxt)) {
                $query->where('address', 'like', "%$searchtxt%");
            }
            if (!empty($role_type)) {
                $query->where('role_type', $role_type);
            }
        }

        $memberDetails = $query->orderBy('id', 'desc')->get();

        $member_data = [];
        if (!$memberDetails->isEmpty()) {
            foreach ($memberDetails as $key => $member) {
                $roleDetails = Role::where('id', $member->role_type)->first();
                $memberDetailState = State::find($member->state);
                $createdDate = date('d-m-Y', strtotime($member->created));

                $member_data[$key] = [
                    'id' => $member->id,
                    'role' => $roleDetails->role ?? '',
                    'name' => $member->name ?? '',
                    'phone_number' => $member->phone_number ?? '',
                    'email_id' => $member->email_id ?? '',
                    'state' => $memberDetailState->state ?? '',
                    'address' => $member->address ?? '',
                    'created' => $createdDate ?? null,
                ];
            }
        }

        return response()->json(['data' => $member_data], 200);
    }


    public function store(Request $request)
    {
        // $tableName = (new \App\Models\AddMember())->getTable();
        // dd($tableName); 
        // Check if the user is authenticated
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Get the authenticated user's ID
        $userid = $user->id;
        $request->validate([
            'role_type' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'email_id' => 'required|string|email|max:255|unique:add_members,email_id',
            'dob' => 'required|date_format:m/d/y',
            'voters_id_number' => 'required|string|max:255',
            'state' => 'required|exists:states,id',
            'address' => 'nullable|string|max:255',
        ]);

        $member = new AddMember();
        $member->user_id = $userid;
        $member->role_type = $request->input('role_type');
        $member->name = $request->input('name');
        $member->gender = $request->input('gender');
        $member->phone_number = $request->input('phone_number');
        $member->email_id = $request->input('email_id');
        $member->dob = $request->input('dob');
        $member->voters_id_number = $request->input('voters_id_number');
        $member->state = $request->input('state');
        $member->senatorial = $request->input('senatorial');
        $member->voting_local_govt = $request->input('voting_local_govt');
        $member->ward = $request->input('ward');
        $member->polling_unit = $request->input('polling_unit');
        $member->code = $request->input('code');
        $member->occupation = $request->input('occupation');
        $member->latitude = $request->input('latitude');
        $member->longitude = $request->input('longitude');
        $member->date_of_registration = $request->input('date_of_registration');
        $member->address = $request->input('address');
        $member->political_party = $request->input('political_party');
        $member->is_active = $request->input('is_active');
        $member->created = now();
        $member->modified = now();

        $member->save();

        return response()->json(['message' => 'Member created successfully', 'data' => $member], 201);
    }


    public function edit($id)
    {
        // Find the member by its ID
        $member = AddMember::find($id);

        // Check if the member exists
        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member not found'
            ], 404);
        }

        // Retrieve the role and state details
        $roleDetails = Role::find($member->role_type);
        $stateDetails = State::find($member->state);

        // Prepare the member data with role and state
        $member_data = [
            'id' => $member->id,
            'role_type' => $member->role_type,
            'role' => $roleDetails ? $roleDetails->role : 'Role not found',
            'name' => $member->name,
            'gender' => $member->gender,
            'phone_number' => $member->phone_number,
            'email_id' => $member->email_id,
            'dob' => $member->dob,
            'voters_id_number' => $member->voters_id_number,
            'state' => $member->state,
            'state_name' => $stateDetails ? $stateDetails->state : 'State not found',
            'address' => $member->address,
            'senatorial' => $member->senatorial,
            'voting_local_govt' => $member->voting_local_govt,
            'ward' => $member->ward,
            'polling_unit' => $member->polling_unit,
            'code' => $member->code,
            'occupation' => $member->occupation,
            'latitude' => $member->latitude,
            'longitude' => $member->longitude,
            'date_of_registration' => $member->date_of_registration,
            'political_party' => $member->political_party,
            'is_active' => $member->is_active,
            'created' => date('d-m-Y', strtotime($member->created)),
        ];

        // Return the response with member data
        return response()->json([
            'success' => true,
            'member_data' => $member_data
        ]);
    }


    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:add_members,id',
            'role_type' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'email_id' => 'required|string|email|max:255|unique:add_members,email_id,' . $request->id,
            'dob' => 'required|date_format:m/d/y',
            'voters_id_number' => 'required|string|max:255',
            'state' => 'required|exists:states,id',
            'address' => 'nullable|string|max:255',
        ]);

        $member = AddMember::find($request->id);

        if (!$member) {
            return response()->json(['message' => 'Member not found'], 404);
        }

        $member->role_type = $request->input('role_type');
        $member->name = $request->input('name');
        $member->gender = $request->input('gender');
        $member->phone_number = $request->input('phone_number');
        $member->email_id = $request->input('email_id');
        $member->dob = $request->input('dob');
        $member->voters_id_number = $request->input('voters_id_number');
        $member->state = $request->input('state');
        $member->address = $request->input('address');
        $member->senatorial = $request->input('senatorial');
        $member->voting_local_govt = $request->input('voting_local_govt');
        $member->ward = $request->input('ward');
        $member->polling_unit = $request->input('polling_unit');
        $member->code = $request->input('code');
        $member->occupation = $request->input('occupation');
        $member->latitude = $request->input('latitude');
        $member->longitude = $request->input('longitude');
        $member->date_of_registration = $request->input('date_of_registration');
        $member->political_party = $request->input('political_party');
        $member->is_active = $request->input('is_active');
        $member->modified = now();

        $member->save();

        return response()->json(['message' => 'Member updated successfully', 'data' => $member], 200);
    }


    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $member = AddMember::find($request->id);

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Member not found'
            ], 404);
        }

        $member->delete();

        return response()->json([
            'success' => true,
            'message' => 'Member deleted successfully'
        ]);
    }

    public function addmember()
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $getroletype = Role::select('id', 'role')->where('is_active', 1)->get();

        $getroletypedata = $getroletype->map(function ($roledata) {
            return [
                'role_id' => $roledata->id,
                'role_name' => $roledata->role
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

        $party = Party::where('is_active', 1)->get();
        $partydata = $party->map(function ($p) {
            return [
                'id' => $p->id,
                'party_name' => $p->party_name ?? '',
            ];
        });

        return response()->json([
            'role_type' => $getroletypedata,
            'statedata' => $statedata,
            'partydata' => $partydata,
        ], 200);
    }


    public function getsenatorialstates($stateid)
    {
        $senatorialstates = Senatorial_state::where('state_id', $stateid)->get();

        $senatorialstatedata = $senatorialstates->map(function ($senastate) {
            return [
                'id' => $senastate->id,
                'senatorial_state_name' => $senastate->sena_district ?? '',
            ];
        });

        return response()->json([
            'senatorialstatedata' => $senatorialstatedata
        ], 200);
    }

    public function getlga($stateid)
    {
        $lgalist = Local_constituency::where('state_id', $stateid)
            ->get()
            ->map(function ($edata) {
                return [
                    'id' => $edata->id,
                    'lga_name' => $edata->lga,
                ];
            });

        return response()->json([
            'statewiselga' => $lgalist,
        ]);
    }

    public function getward($lgaid)
    {
        $wardlist = Ward::where('lga_id', $lgaid)
            ->get()
            ->map(function ($warddata) {
                return [
                    'id' => $warddata->id,
                    'ward_name' => $warddata->ward_details,
                ];
            });

        return response()->json([
            'lgawiseward' => $wardlist,
        ]);
    }


    public function getpu($wardid)
    {
        $pollinglist = Polling_unit::where('ward_id', $wardid)
            ->get()
            ->map(function ($pudata) {
                return [
                    'id' => $pudata->id,
                    'polling_unit_name' => $pudata->polling_name,
                ];
            });

        return response()->json([
            'wardwise_pollingunit' => $pollinglist,
        ]);
    }
}
