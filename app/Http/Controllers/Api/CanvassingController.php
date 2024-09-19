<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AddMember;
use App\Models\GrassrooterFeedback;
use App\Models\PartyVote;
use App\Models\Role;
use App\Models\State;
use App\Models\Survey;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CanvassingController extends Controller
{
    public function getsurveyresponse(Request $request)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userid = $user->id;
        $responsekey = $request->get('responsekey');

        $response = [];

        $surveys = Survey::where('is_active', 1)
            ->where('user_id', $userid)
            ->get();

        foreach ($surveys as $survey) {
            $feedbackQuery = GrassrooterFeedback::where('survey_id', $survey->id);

            if ($responsekey) {
                $feedbackQuery->where('response', $responsekey);
            }

            $count = $feedbackQuery->count();
            $feedbackResponses = $feedbackQuery->pluck('response');

            if ($count > 0) {
                $response[] = [
                    'survey_id' => $survey->id,
                    'survey_title' => $survey->title,
                    'count' => $count,
                    'responses' => $feedbackResponses
                ];
            }
        }

        return response()->json([
            'survey_response' => $response
        ]);
    }


    public function getresponseonroletypes(Request $request)
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userid = $user->id;
        $roleKey = $request->get('role_key');

        if ($roleKey) {
            $role = Role::find($roleKey);

            if (!$role) {
                return response()->json(['message' => 'Role not found'], 404);
            }

            $totalCount = AddMember::where('role_type', $roleKey)
                ->where('user_id', $userid)
                ->count();

            $roleData[] = [
                'role_id' => $role->id,
                'role_name' => $role->role,
                'members_count' => $totalCount
            ];

            return response()->json([
                'role_data' => $roleData
            ]);
        } else {
            $roles = Role::all();
            $roleData = [];

            foreach ($roles as $role) {
                $totalCount = AddMember::where('role_type', $role->id)
                    ->where('user_id', $userid)
                    ->count();

                $roleData[] = [
                    'role_id' => $role->id,
                    'role_name' => $role->role,
                    'members_count' => $totalCount
                ];
            }

            return response()->json([
                'role_data' => $roleData
            ]);
        }
    }



    public function filterelectionresults(Request $request)
    {
        $election_year = $request->input('election_year');
        $election_type = $request->input('election_type');
        $state_type = $request->input('stateid');

        $query = PartyVote::selectRaw('state_id, SUM(vote_value) as vote_value')
            ->where('election_year', $election_year)->where('election_type', $election_type);

        if ($election_type != 1) {
            $query->where('state_id', $state_type);
        }

        $votes = $query->groupBy('state_id')->get();

        $statearr = [];
        $vote_value = [];

        if ($votes->isNotEmpty()) {
            foreach ($votes as $vote) {
                $state = State::find($vote->state_id);
                if ($state) {
                    $statearr[] = $state->state;
                    $vote_value[] = $vote->vote_value;
                }
            }

            return response()->json([
                'vote_value' => $vote_value,
                'state_name' => $statearr,
            ]);
        } else {
            return response()->json([
                'vote_value' => $vote_value,
                'state_name' => $statearr
            ]);
        }
    }

    public function getPositiveReviews()
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userid = $user->id;
        $allActiveGrass = AddMember::where('is_active', 1)->where('user_id', $userid)
            ->where('role_type', 4)
            ->get();

        $responseArray = [];

        foreach ($allActiveGrass as $grassrooter) {
            $positiveFeedbackCount = GrassrooterFeedback::where('response', 5)
                ->where('grassrooters_id', $grassrooter->id)
                ->count();

            $responseArray[] = [
                'name' => $grassrooter->name,
                'positive_reviews' => $positiveFeedbackCount
            ];
        }

        return response()->json([
            'positive_response' => $responseArray
        ]);
    }
}
