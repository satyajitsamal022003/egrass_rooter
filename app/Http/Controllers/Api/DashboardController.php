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
        // dd($party_detail->party_name);

        if ($popupcampaignname) {
            $dashboard_data['campaignName'] = $popupcampaignname->title;
        } else {
            $dashboard_data['campaignName'] = null;
        }

        if ($campaign_details) {
            $campaign_type = '';
            switch ($campaign_details->campaign_type) {
                case 1:
                    $campaign_type = 'Presidential Election Campaign';
                    break;
                case 2:
                    $campaign_type = 'Senate Election Campaign';
                    break;
                case 3:
                    $campaign_type = 'House of Representative Campaign';
                    break;
                case 4:
                    $campaign_type = 'Governorship Campaign';
                    break;
                case 5:
                    $campaign_type = 'House of Assembly Campaign';
                    break;
                case 6:
                    $campaign_type = 'Chairman/Councillor Campaign';
                    break;
                case 7:
                    $campaign_type = 'Pressure Group/NGO';
                    break;
                default:
                    $campaign_type = 'N/A';
                    break;
            }
            $dashboard_data['campaignType'] = $campaign_type;
        } else {
            $dashboard_data['campaignType'] = 'N/A';
        }

        if ($campaign_details) {
            $dashboard_data['campaignManager'] = $campaign_details->first_name . ' ' . $campaign_details->last_name;
        } else {
            $dashboard_data['campaignManager'] = null;
        }

        if (!empty($campaign_details)) {
            if ($campaign_details->campaign_type != 7) {
                if (!empty($party_detail)) {
                    $dashboard_data['Party'] = $party_detail->party_name;
                } else {
                    $dashboard_data['Party'] = 'N/A';
                }
            } else {
                $dashboard_data['Party'] = 'Pressure Group';
            }
        }

        if ($campaign_details->campaign_type == 1 || $campaign_details->campaign_type == 4 || $campaign_details->campaign_type == 5 || $campaign_details->campaign_type == 6) {
            $dashboard_data['Area'] = 'N/A';
        } elseif ($campaign_details->campaign_type == 2 || $campaign_details->campaign_type == 3 || $campaign_details->campaign_type == 7) {
            if (!empty($senatorialState)) {
                $dashboard_data['Area'] = $senatorialState->sena_district;
            } else {
                $dashboard_data['Area'] = 'N/A';
            }
        } else {
            $dashboard_data['Area'] = 'N/A';
        }


        $grassrooters = AddMember::where([
            ['role_type', '=', 2],
            ['is_active', '=', 1],
            ['user_id', '=', $userid]
        ])->orderBy('id', 'desc')->get();
        // dd($grassrooters);

        $contactNumbers = AddMember::where('user_id', $userid)->orderBy('id', 'desc')->get();

        if ($grassrooters) {
            $dashboard_data['Grassrooters'] = $grassrooters->count();
        } else {
            $dashboard_data['Grassrooters'] = null;
        }

        if ($contactNumbers) {
            $dashboard_data['NumberOfContacts'] = $contactNumbers->count();
        } else {
            $dashboard_data['NumberOfContacts'] = null;
        }


        if ($campaign_details) {
            $stateId = $campaign_details->state;

            if ($campaign_details->campaign_type == 1) {
                $dashboard_data['States'] = State::where('id', $stateId)
                    ->join('country', 'states.country_id', '=', 'country.id')
                    ->count();
            } elseif ($campaign_details->campaign_type == 7) {
                $dashboard_data['States'] = State::where('id', $stateId)->count();
            }
        } else {
            $dashboard_data['States'] = null;
        }

        return response()->json(['data' => $dashboard_data], 200);
    }
}
