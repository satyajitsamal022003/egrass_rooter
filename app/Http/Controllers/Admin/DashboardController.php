<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign_user;
use App\Models\ElectionType;
use App\Models\Polling_unit;
use App\Models\Service;
use App\Models\Party;
use App\Models\Survey;
use App\Models\GrassrooterFeedback;
use App\Models\PartyVote;
use App\Models\State;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $campaignManagers = Campaign_user::orderBy('id', 'desc')->take(5)->get();
        $pollingList = Polling_unit::orderBy('id')->limit(5)->get();
        $allServices = Service::orderBy('id', 'desc')->limit(5)->get();

        $registeredusers = Campaign_user::get();
        $verifiedusers = Campaign_user::where('is_mail_verified', 1)->count();
        $activeuser = Campaign_user::where('is_mail_verified', 1)
            ->where('is_active', 1)
            ->count();

        $response = [];
        $parties = Party::all();

        $responsekey = $request->get('responsekey');

        $surveys = Survey::where('is_active', 1)->get();

        foreach ($surveys as $survey) {
            $responseCounts = [
                'poor' => 0,
                'average' => 0,
                'good' => 0,
                'verygood' => 0,
                'outstanding' => 0
            ];

            $feedbackQuery = GrassrooterFeedback::where('survey_id', $survey->id);

            if ($responsekey) {
                $feedbackQuery->where('response', $responsekey);
            }

            $feedbackResponses = $feedbackQuery->get();

            foreach ($feedbackResponses as $feedback) {
                switch ($feedback->response) {
                    case 1:
                        $responseCounts['poor']++;
                        break;
                    case 2:
                        $responseCounts['average']++;
                        break;
                    case 3:
                        $responseCounts['good']++;
                        break;
                    case 4:
                        $responseCounts['verygood']++;
                        break;
                    case 5:
                        $responseCounts['outstanding']++;
                        break;
                }
            }

            $response[] = [
                'survey_id' => $survey->id,
                'survey_title' => $survey->title,
                'counts' => $responseCounts
            ];
        }

        $surveyTitles = [];
        $surveyCounts = [];

        foreach ($response as $item) {
            $surveyTitles[] = $item['survey_title'];
            $surveyCounts[] = $item['counts'];
        }

        $electionTypes = ElectionType::get();

        $states = State::get();

        return view('admin.dashboard', compact(
            'campaignManagers',
            'pollingList',
            'allServices',
            'registeredusers',
            'parties',
            'verifiedusers',
            'activeuser',
            'surveyTitles',
            'surveyCounts',
            'electionTypes',
            'states'
        ));
    }

    public function getElectionResults(Request $request)
    {
        $election_type = $request->input('election_type');
        $state_id = $request->input('state_id');
        $election_year = $request->input('election_year');

        $query = PartyVote::where('election_type', $election_type)
            ->where('election_year', $election_year);

        // If election_type is not 1, include state filter
        if ($election_type != 1) {
            $query->where('state_id', $state_id);
        }

        $results = $query->with('state', 'party')->get();

        $stateNames = [];
        $voteValues = [];

        foreach ($results as $result) {
            $stateNames[] = $result->state->state;
            $voteValues[] = $result->vote_value;
        }

        return response()->json([
            'stateNames' => $stateNames,
            'voteValues' => $voteValues
        ]);
    }



    public function viewNotify()
    {
        $affectedRows = DB::table('notifications')
            ->where('admin_status', 2)
            ->update(['admin_status' => 1]);

        if ($affectedRows) {
            return redirect()->route('admin.dashboard')
                ->with('message', 'Success!');
        } else {
            return redirect()->route('admin.dashboard')
                ->with('info', 'No New Notification Found !');
        }
    }
}
