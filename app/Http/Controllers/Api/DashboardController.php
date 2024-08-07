<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AddMember;
use App\Models\Campaign_user;
use App\Models\GrassrooterFeedback;
use App\Models\ImportVotersdata;
use App\Models\Notification;
use App\Models\Party;
use App\Models\Role;
use App\Models\Senatorial_state;
use App\Models\State;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\UserWebsite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getDashData(Request $request, $userid)
    {
        $dashboard_data = [];

        $popupcampaignname = UserWebsite::where('user_id', $userid)->first();
        $campaign_details = Campaign_user::where('id', $userid)->first();
        $party_detail = Party::where('id', $campaign_details->political_party)->first();
        $senatorialState = Senatorial_state::where('id', $campaign_details->senatorial_district_id)
            ->orderBy('id', 'desc')
            ->first();

        $dashboard_data['campaignName'] = $popupcampaignname ? $popupcampaignname->title : null;

        if ($campaign_details) {
            $campaign_type = match ($campaign_details->campaign_type) {
                1 => 'Presidential Election Campaign',
                2 => 'Senate Election Campaign',
                3 => 'House of Representative Campaign',
                4 => 'Governorship Campaign',
                5 => 'House of Assembly Campaign',
                6 => 'Chairman/Councillor Campaign',
                7 => 'Pressure Group/NGO',
                default => 'N/A',
            };
            $dashboard_data['campaignType'] = $campaign_type;
            $dashboard_data['campaignManager'] = $campaign_details->first_name . ' ' . $campaign_details->last_name;

            if ($campaign_details->campaign_type != 7) {
                $dashboard_data['Party'] = $party_detail ? $party_detail->party_name : 'N/A';
            } else {
                $dashboard_data['Party'] = 'Pressure Group';
            }

            if (in_array($campaign_details->campaign_type, [2, 3, 7])) {
                $dashboard_data['Area'] = $senatorialState ? $senatorialState->sena_district : 'N/A';
            } else {
                $dashboard_data['Area'] = 'N/A';
            }

            $stateId = $campaign_details->state;

            if ($campaign_details->campaign_type == 1) {
                $dashboard_data['States'] = State::where('states.id', $stateId)
                    ->join('country', 'states.country_id', '=', 'country.id')
                    ->count();
            } elseif ($campaign_details->campaign_type == 7) {
                $dashboard_data['States'] = State::where('states.id', $stateId)->count();
            }

            if ($campaign_details->campaign_type == 1) {
                $dashboard_data['SenatorialStates'] = State::leftJoin('country', 'country.id', '=', 'states.country_id')
                    ->leftJoin('senatorial_states', 'states.id', '=', 'senatorial_states.state_id')
                    ->where('country.id', 158)
                    ->select('senatorial_states.id as senatorialStateId')
                    ->count();

                $dashboard_data['FederalConstituencies'] = State::leftJoin('country', 'country.id', '=', 'states.country_id')
                    ->leftJoin('federal_constituencies', 'federal_constituencies.state_id', '=', 'states.id')
                    ->where('country.id', 158)
                    ->select('federal_constituencies.id as federalConstituencyId')
                    ->count();
                $dashboard_data['LGAConstituencies'] = State::leftJoin('local_constituencies', 'local_constituencies.state_id', '=', 'states.id')
                    ->where('states.country_id', 158)
                    ->select('local_constituencies.id as localConstituencyId')
                    ->count();
                $dashboard_data['WardConstituencies'] = State::leftJoin('local_constituencies', 'local_constituencies.state_id', '=', 'states.id')
                    ->leftJoin('wards', 'wards.lga_id', '=', 'local_constituencies.id')
                    ->where('states.country_id', 158)
                    ->select('wards.id as wardId')
                    ->count();
                $dashboard_data['PollingUnits'] = State::leftJoin('polling_units', 'polling_units.state_id', '=', 'states.id')
                    ->where('states.country_id', 158)
                    ->select('polling_units.id as pollingUnitId')
                    ->count();
                $dashboard_data['PollingAgents'] = State::leftJoin('polling_agent', 'polling_agent.state', '=', 'states.id')
                    ->where('states.country_id', 158)
                    ->whereNotNull('polling_agent.state')
                    ->select('polling_agent.id as pollingAgentId')
                    ->count();
            } elseif ($campaign_details->campaign_type == 7) {
                $dashboard_data['SenatorialStates'] = State::leftJoin('country', 'country.id', '=', 'states.country_id')
                    ->leftJoin('senatorial_states', 'states.id', '=', 'senatorial_states.state_id')
                    ->where('states.id', $campaign_details->state)
                    ->select('senatorial_states.id as senatorialStateId')
                    ->count();

                $dashboard_data['FederalConstituencies'] = State::leftJoin('country', 'country.id', '=', 'states.country_id')
                    ->leftJoin('federal_constituencies', 'federal_constituencies.state_id', '=', 'states.id')
                    ->where('states.id', $campaign_details->state)
                    ->select('federal_constituencies.id as federalConstituencyId')
                    ->count();
                $dashboard_data['LGAConstituencies'] = State::leftJoin('local_constituencies', 'local_constituencies.state_id', '=', 'states.id')
                    ->where('states.id', $campaign_details->state)
                    ->select('local_constituencies.id as localConstituencyId')
                    ->count();
                $dashboard_data['WardConstituencies'] = State::leftJoin('local_constituencies', 'local_constituencies.state_id', '=', 'states.id')
                    ->leftJoin('wards', 'wards.lga_id', '=', 'local_constituencies.id')
                    ->where('states.id', $campaign_details->state)
                    ->select('wards.id as wardId')
                    ->count();
                $dashboard_data['PollingUnits'] = State::leftJoin('polling_units', 'polling_units.state_id', '=', 'states.id')
                    ->where('states.id', $campaign_details->state)
                    ->select('polling_units.id as pollingUnitId')
                    ->count();
                $dashboard_data['PollingAgents'] = State::leftJoin('polling_agent', 'polling_agent.state', '=', 'states.id')
                    ->where('states.id', $campaign_details->state)
                    ->whereNotNull('polling_agent.state')
                    ->select('polling_agent.id as pollingAgentId')
                    ->count();
            }
        } else {
            $dashboard_data['campaignType'] = 'N/A';
            $dashboard_data['campaignManager'] = null;
            $dashboard_data['Party'] = 'N/A';
            $dashboard_data['Area'] = 'N/A';
            $dashboard_data['States'] = null;
            $dashboard_data['SenatorialStates'] = null;
            $dashboard_data['FederalConstituencies'] = null;
            $dashboard_data['LGAConstituencies'] = null;
            $dashboard_data['WardConstituencies'] = null;
            $dashboard_data['PollingUnits'] = null;
            $dashboard_data['PollingAgents'] = null;
        }

        $grassrooters = AddMember::where([
            ['role_type', '=', 2],
            ['is_active', '=', 1],
            ['user_id', '=', $userid]
        ])->orderBy('id', 'desc')->get();

        $contactNumbers = AddMember::where('user_id', $userid)->orderBy('id', 'desc')->get();

        $dashboard_data['Grassrooters'] = $grassrooters ? $grassrooters->count() : null;
        $dashboard_data['NumberOfContacts'] = $contactNumbers ? $contactNumbers->count() : null;

        return response()->json(['data' => $dashboard_data], 200);
    }
}
