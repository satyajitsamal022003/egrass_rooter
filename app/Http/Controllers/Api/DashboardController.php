<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AddMember;
use App\Models\Blog;
use App\Models\Campaign_user;
use App\Models\Donation;
use App\Models\EventWebsite;
use App\Models\Local_constituency;
use App\Models\PartyVote;
use App\Models\Polling_unit;
use App\Models\PollingAgent;
use App\Models\State;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Upcomingelection;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function getDashData(Request $request)
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userid = $user->id;
        $campaign_details = Campaign_user::where('id', $userid)->first();

        //Voter Turn Out
        $voterturnouttrends = PartyVote::selectRaw('SUM(vote_value) as total_votes, election_year')
            ->groupBy('election_year')
            ->get();

        //Projected Voter Turn Out
        $historicalData = PartyVote::selectRaw('SUM(vote_value) as total_votes, election_year')
            ->groupBy('election_year')
            ->orderBy('election_year', 'asc')
            ->get();

        $historicalVotes = $historicalData->pluck('total_votes')->toArray();
        $growthRates = [];
        for ($i = 1; $i < count($historicalVotes); $i++) {
            $growthRates[] = ($historicalVotes[$i] - $historicalVotes[$i - 1]) / $historicalVotes[$i - 1];
        }
        $averageGrowthRate = array_sum($growthRates) / count($growthRates);
        $lastYearVotes = end($historicalVotes);
        $projectedVotes = $lastYearVotes * (1 + $averageGrowthRate);

        $projectedVotes = round($projectedVotes);

        //Donations
        $donationdata = Donation::where('is_active', 1)->sum('amount');

        //Future events
        $future_events = EventWebsite::where('event_date', '>=', now())->where('is_active', 1)->where('user_id', $userid)
            ->orderBy('event_date', 'asc')
            ->take(3)
            ->get()->map(function ($fevents) {
                return [
                    'id' => $fevents->id,
                    'event_date' => $fevents->event_date,
                    'event_time' => $fevents->event_time,
                    'description' => $fevents->description,
                    'venue' => $fevents->address,
                    'event_image' => $fevents->event_image ? asset('images/eventwebsite/' . $fevents->event_image) : "",
                ];
            });


        // Fetch past events
        $past_events = EventWebsite::where('event_date', '<', now())->where('is_active', 1)->where('user_id', $userid)
            ->orderBy('event_date', 'desc')
            ->take(3)
            ->get()
            ->map(function ($pevents) {
                return [
                    'id' => $pevents->id,
                    'event_date' => $pevents->event_date,
                    'event_time' => $pevents->event_time,
                    'description' => $pevents->description,
                    'venue' => $pevents->address,
                    'event_image' => $pevents->event_image ? asset('images/eventwebsite/' . $pevents->event_image) : "",
                ];
            });

        //Fetch Blog
        $blogs = Blog::where('is_active', 1)->where('user_id', $userid)->get()
            ->map(function ($bl) {
                return [
                    'id' => $bl->id,
                    'blog_name' => $bl->title,
                    'blog_image' => $bl->blog_image ? asset('images/blog/' . $bl->blog_image) : "",
                ];
            });

        //next election

        // Get today's date
        $today = Carbon::now()->startOfDay();

        // Get the closest upcoming election date
        $closestDate = Upcomingelection::where('is_active', 1)->where('election_date', '>=', $today)
            ->min('election_date');

        if (!$closestDate) {
            return response()->json([
                'status' => 'success',
                'message' => 'No Upcoming Elections Found',
                'data' => []
            ]);
        }

        $closestDate = Carbon::parse($closestDate);

        // Get elections for the closest date
        $elections = Upcomingelection::with('electionType', 'state')
            ->where('is_active', 1)
            ->whereDate('election_date', $closestDate->format('Y-m-d'))
            ->get();

        // Format the data and group by election type
        $groupedElections = $elections->groupBy(function ($election) {
            return $election->electionType->type ?? "";
        })->map(function ($electionsByType) {
            return $electionsByType->map(function ($election) {
                return $election->state->state ?? "";
            })->unique();
        });

        // Format the final response data
        $formattedData = [];
        foreach ($groupedElections as $type => $states) {
            $formattedData[$closestDate->format('Y-m-d')][] = [
                'election_type' => $type,
                'states' => $states
            ];
        }

        //Initialize counts
        $stateslast = [];
        $senatorialStates = [];
        $federalStates = [];
        $lgaStates = [];
        $wardStates = [];
        $pollingStates = [];
        $pollingagentsqlresultStates = [];

        if ($campaign_details->campaign_type == 1) {
            // Presidential state
            $stateslast = DB::table('states')
                ->join('country', 'states.country_id', '=', 'country.id')
                ->select('states.id')
                ->get();

            // Presidential Senatorial state
            $senatorialStates = DB::table('states')
                ->join('senatorial_states', 'states.id', '=', 'senatorial_states.state_id')
                ->select('senatorial_states.id as senatorialStateId')
                ->get();


            // Presidential federal constituency
            $federalStates = DB::table('states')
                ->join('federal_constituencies', 'federal_constituencies.state_id', '=', 'states.id')
                ->select('federal_constituencies.id as federalConstituencyId')
                ->get();


            // Presidential LGA constituency
            $lgaStates = DB::table('states')
                ->join('federal_constituencies', 'federal_constituencies.state_id', '=', 'states.id')
                ->select('federal_constituencies.id as localConstituencyId')
                ->get();

            // Presidential ward constituency
            $wardStates = DB::table('states')
                ->join('federal_constituencies', 'federal_constituencies.state_id', '=', 'states.id')
                ->join('wards', 'wards.lga_id', '=', 'federal_constituencies.id')
                ->select('wards.id')
                ->get();

            // Presidential Polling Unit
            $pollingStates = DB::table('states')
                ->join('polling_units', 'polling_units.state_id', '=', 'states.id')
                ->select('polling_units.id')
                ->get();

            // Presidential Polling Agent
            $pollingagentsqlresultStates = DB::table('states')
                ->join('polling_agent', 'polling_agent.state', '=', 'states.id')
                ->whereNotNull('polling_agent.state')
                ->select('polling_agent.id')
                ->get();
        }

        // Senatorial campaign
        elseif ($campaign_details->campaign_type == 2) {
            $lgaStates = DB::table('local_constituencies')
                ->where('senatorial_state_id', $campaign_details->senatorial_district_id)
                ->get();

            $wardStates = DB::table('wards')
                ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                ->where('local_constituencies.senatorial_state_id', $campaign_details->senatorial_district_id)
                ->select('wards.id as wardid', 'local_constituencies.lga as lganame', 'wards.ward_details as wardname')
                ->get();

            $pollingStates = DB::table('polling_units')
                ->join('wards', 'wards.id', '=', 'polling_units.wards_id')
                ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                ->where('local_constituencies.senatorial_state_id', $campaign_details->senatorial_district_id)
                ->select('polling_units.id as id', 'polling_units.lga', 'polling_units.ward_details as wardname', 'polling_units.polling_name as pollingname')
                ->get();

            $pollingagentsqlresultStates = DB::table('polling_agent as pa')
                ->join('polling_units as pu', 'pu.id', '=', 'pa.polling_units')
                ->join('wards as w', 'w.id', '=', 'pu.ward_id')
                ->join('local_constituencies as lc', 'lc.id', '=', 'w.lga_id')
                ->where('lc.senatorial_state_id', $campaign_details->senatorial_district_id)
                ->select('pa.id', 'pa.name')
                ->get();
        }

        // House of Representative Campaign
        elseif ($campaign_details->campaign_type == 3) {
            $lgaStates = DB::table('local_constituencies')
                ->where('federal_constituency_id', $campaign_details->federal_constituency_id)
                ->get();

            $wardStates = DB::table('wards')
                ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                ->where('local_constituencies.federal_constituency_id', $campaign_details->federal_constituency_id)
                ->select('wards.id as wardid', 'local_constituencies.lga as lganame', 'wards.ward_details as wardname')
                ->get();

            $pollingStates = DB::table('polling_units')
                ->join('wards', 'wards.id', '=', 'polling_units.ward_id')
                ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                ->where('local_constituencies.federal_constituency_id', $campaign_details->federal_constituency_id)
                ->select('polling_units.id as id', 'polling_units.lga', 'polling_units.ward_details as wardname', 'polling_units.polling_name as pollingname')
                ->get();

            $pollingagentsqlresultStates = DB::table('polling_agent as pa')
                ->join('polling_units as pu', 'pu.id', '=', 'pa.polling_units')
                ->join('wards as w', 'w.id', '=', 'pu.ward_id')
                ->join('local_constituencies as lc', 'lc.id', '=', 'w.lga_id')
                ->where('lc.federal_constituency_id', $campaign_details->federal_constituency_id)
                ->whereNotNull('pa.name')
                ->select('pa.id', 'pa.name')
                ->get();
        }

        // Governorship Campaign
        elseif ($campaign_details->campaign_type == 4) {
            $lgaStates = DB::table('local_constituencies')
                ->where('state_id', $campaign_details->state)
                ->get();

            $wardStates = DB::table('wards')
                ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                ->where('local_constituencies.state_id', $campaign_details->state)
                ->select('wards.id as wardid', 'local_constituencies.lga as lganame', 'wards.ward_details as wardname')
                ->get();

            $pollingStates = DB::table('polling_units')
                ->join('wards', 'wards.id', '=', 'polling_units.ward_id')
                ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                ->where('local_constituencies.state_id', $campaign_details->state)
                ->select('polling_units.id as id', 'polling_units.lga', 'polling_units.ward_details as wardname', 'polling_units.polling_name as pollingname')
                ->get();

            $pollingagentsqlresultStates = DB::table('polling_agent as pa')
                ->join('polling_units as pu', 'pu.id', '=', 'pa.polling_units')
                ->join('wards as w', 'w.id', '=', 'pu.ward_id')
                ->join('local_constituencies as lc', 'lc.id', '=', 'w.lga_id')
                ->where('lc.state_id', $campaign_details->state)
                ->whereNotNull('pa.name')
                ->select('pa.id', 'pa.name')
                ->get();
        } elseif ($campaign_details->campaign_type == 5) {
            $lgaStates = Local_constituency::where('state_id', $campaign_details->state)->get();

            $wardStates = DB::table('wards')
                ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                ->where('local_constituencies.state_id', $campaign_details->state)
                ->select('wards.id as ward_id', 'local_constituencies.lga as lga_name', 'wards.ward_details as ward_name')
                ->get();

            $pollingStates = DB::table('polling_units')
                ->join('wards', 'wards.id', '=', 'polling_units.ward_id')
                ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                ->where('local_constituencies.state_id', $campaign_details->state)
                ->select('polling_units.id', 'polling_units.lga', 'polling_units.ward_details as ward_name', 'polling_units.polling_name')
                ->get();

            $pollingagentsqlresultStates = DB::table('polling_agents as pa')
                ->join('polling_units as pu', 'pu.id', '=', 'pa.polling_units')
                ->join('wards as w', 'w.id', '=', 'pu.ward_id')
                ->join('local_constituencies as lc', 'lc.id', '=', 'w.lga_id')
                ->where('lc.state_id', $campaign_details->state)
                ->whereNotNull('pa.name')
                ->select('pa.id', 'pa.name')
                ->get();
        }
        //-----------End House of Assembly---------------//

        //-----------Local Government Chairman-------------//
        elseif ($campaign_details->campaign_type == 6) {
            $lgaStates = Local_constituency::where('id', $campaign_details->local_constituency_id)->get();

            $wardStates = DB::table('wards')
                ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                ->where('local_constituencies.id', $campaign_details->local_constituency_id)
                ->select('wards.id as ward_id', 'local_constituencies.lga as lga_name', 'wards.ward_details as ward_name')
                ->get();

            $pollingStates = DB::table('polling_units')
                ->join('wards', 'wards.id', '=', 'polling_units.ward_id')
                ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                ->where('local_constituencies.id', $campaign_details->local_constituency_id)
                ->select('polling_units.id', 'polling_units.lga', 'polling_units.ward_details as ward_name', 'polling_units.polling_name')
                ->get();

            $pollingagentsqlresultStates = DB::table('polling_agents as pa')
                ->join('polling_units as pu', 'pu.id', '=', 'pa.polling_units')
                ->join('wards as w', 'w.id', '=', 'pu.ward_id')
                ->join('local_constituencies as lc', 'lc.id', '=', 'w.lga_id')
                ->where('lc.id', $campaign_details->local_constituency_id)
                ->whereNotNull('pa.name')
                ->select('pa.id', 'pa.name')
                ->get();
        } elseif ($campaign_details->campaign_type == 7) {
            $stateId = $campaign_details->state;

            //----------------For NGO-Pressure Group State------------------//
            $stateslast = DB::table('states')
                ->join('country', 'states.country_id', '=', 'country.id')
                ->where('states.id', $stateId)
                ->select('states.id')
                ->get();

            //-------------------NGO-Pressure Group Senatorial State-------------------//
            $senatorialStates = DB::table('states')
                ->join('senatorial_states', 'states.id', '=', 'senatorial_states.state_id')
                ->where('states.id', $stateId)
                ->select('senatorial_states.id as senatorialStateId')
                ->get();

            //-------------------NGO-Pressure Group Federal Constituency-------------------//
            $federalStates = DB::table('states')
                ->join('federal_constituencies', 'federal_constituencies.state_id', '=', 'states.id')
                ->where('states.id', $stateId)
                ->select('federal_constituencies.id as federalConstituencyId')
                ->get();

            //------------------NGO-Pressure Group LGA Constituency-----------------------//
            $lgaStates = DB::table('states')
                ->join('local_constituencies', 'local_constituencies.state_id', '=', 'states.id')
                ->where('states.id', $stateId)
                ->select('local_constituencies.id as localConstituencyId')
                ->get();

            //-----------------NGO-Pressure Group Ward Constituency----------------------//
            $wardStates = DB::table('states')
                ->join('local_constituencies', 'local_constituencies.state_id', '=', 'states.id')
                ->join('wards', 'wards.lga_id', '=', 'local_constituencies.id')
                ->where('states.id', $stateId)
                ->select('wards.id')
                ->get();

            //------------------NGO-Pressure Group Polling Unit-------------------------//
            $pollingStates = DB::table('states')
                ->join('polling_units', 'polling_units.state_id', '=', 'states.id')
                ->where('states.id', $stateId)
                ->select('polling_units.id')
                ->get();

            //-----------------NGO-Pressure Group Polling Agent-----------------------//
            $pollingagentsqlresultStates = DB::table('states')
                ->join('polling_agent', 'polling_agent.state', '=', 'states.id')
                ->where('states.id', $stateId)
                ->whereNotNull('polling_agent.state')
                ->select('polling_agent.id')
                ->get();
        }

        $grassrooters = AddMember::where([
            ['role_type', '=', 2],
            ['is_active', '=', 1],
            ['user_id', '=', $userid]
        ])->orderBy('id', 'desc')->get();

        $contactNumbers = AddMember::where('user_id', $userid)->orderBy('id', 'desc')->get();


        $campaigndata = [
            'statescount' => $stateslast->count(),
            'senatorialStatescount' => $senatorialStates->count(),
            'federalStatescount' => $federalStates->count(),
            'lgacount' => $lgaStates->count(),
            'wardscount' => $wardStates->count(),
            'pollingunitscount' => $pollingStates->count(),
            'pollingagentscount' => $pollingagentsqlresultStates->count(),
            'contactscount' => $contactNumbers ? $contactNumbers->count() : null,
            'grassrooterscount' =>  $grassrooters ? $grassrooters->count() : null
        ];

        return response()->json([
            'campaign_data' => $campaigndata,
            'voter_turnout_trendscount' => $voterturnouttrends,
            'projected_voter_turnoutcount' => $projectedVotes,
            'donationstcount' => $donationdata,
            'future_eventscount' => $future_events,
            'past_eventscount' => $past_events,
            'blogs' => $blogs,
            'upcoming_election' => $formattedData
        ]);
    }


    public function getPoliticalZones(Request $request)
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userid = $user->id;
        $campaign_details = Campaign_user::where('id', $userid)->first();

        $zonekey = $request->get('zone_key'); // Use query() method to get parameters

        // Initialize arrays for data
        $local_constituencies = [];
        $wards = [];
        $pollingUnits = [];
        $pollingAgents = [];
        $states = [];
        $senatestates = [];
        $federalcons = [];

        if ($campaign_details->campaign_type == 1) { // For Presidential Campaign

            if ($zonekey == 1) {
                $local_constituencies = DB::table('local_constituencies')
                    ->join('states', 'local_constituencies.state_id', '=', 'states.id')
                    ->join('senatorial_states', 'senatorial_states.id', '=', 'local_constituencies.senatorial_state_id')
                    ->where('states.country_id', 158)
                    ->select('local_constituencies.id as lgaId', 'local_constituencies.lga as lganame', 'states.state as statename', 'senatorial_states.sena_district as senadist')
                    ->paginate(20);
            } elseif ($zonekey == 2) {
                $wards = DB::table('wards')
                    ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                    ->join('states', 'states.id', '=', 'local_constituencies.state_id')
                    ->where('states.country_id', 158)
                    ->select('wards.id as wardid', 'local_constituencies.lga as lganame', 'wards.ward_details as wardname', 'states.state as statename')
                    ->paginate(20);
            } elseif ($zonekey == 3) {
                $pollingUnits = DB::table('polling_units')
                    ->join('wards', 'wards.id', '=', 'polling_units.ward_id')
                    ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                    ->join('states', 'states.id', '=', 'polling_units.state_id')
                    ->where('states.country_id', 158)
                    ->select('polling_units.id as id', 'polling_units.lga as lga', 'polling_units.ward_details as wardname', 'polling_units.polling_name as pollingname', 'states.state as statename')
                    ->paginate(20);
            } elseif ($zonekey == 4) {
                $pollingAgents = DB::table('polling_agent')
                    ->join('polling_units', 'polling_units.id', '=', 'polling_agent.polling_units')
                    ->join('wards', 'wards.id', '=', 'polling_units.ward_id')
                    ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                    ->join('states', 'states.id', '=', 'polling_agent.state')
                    ->where('states.country_id', 158)
                    ->whereNotNull('polling_agent.name')
                    ->select('polling_agent.id', 'polling_agent.name', 'states.state as statename', 'polling_units.polling_name', 'wards.ward_details', 'local_constituencies.lga')
                    ->paginate(20);
            } elseif ($zonekey == 5) {
                $states = DB::table('states')
                    ->join('country', 'states.country_id', '=', 'country.id')
                    ->where('states.country_id', 158)
                    ->select('states.id as stateid', 'states.state as statename')
                    ->paginate(20);
            } elseif ($zonekey == 6) {
                $senatestates = DB::table('senatorial_states')
                    ->join('states', 'states.id', '=', 'senatorial_states.state_id')
                    ->where('states.country_id', 158)
                    ->select('senatorial_states.id as senatorialStateId', 'senatorial_states.sena_district as senatorialStateName', 'states.state as statename')
                    ->paginate(20);
            } elseif ($zonekey == 7) {
                $federalcons = DB::table('federal_constituencies')
                    ->join('states', 'states.id', '=', 'federal_constituencies.state_id')
                    ->where('states.country_id', 158)
                    ->select('federal_constituencies.id as federalConstituencyId', 'federal_constituencies.federal_name as federalConstituencyName', 'states.state as statename')
                    ->paginate(20);
            }

            return response()->json([
                'local_constituencies' => $local_constituencies,
                'wards' => $wards,
                'pollingUnits' => $pollingUnits,
                'pollingAgents' => $pollingAgents,
                'states' => $states,
                'senatestates' => $senatestates,
                'federalcons' => $federalcons
            ]);
        }

        // For Senatorial Campaign
        if ($campaign_details->campaign_type == 2) {
            if ($zonekey == 1) {
                $local_constituencies = Local_constituency::where('senatorial_state_id', $campaign_details->senatorial_district_id)->paginate(20);
            } elseif ($zonekey == 2) {
                $wards = DB::table('wards')
                    ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                    ->where('local_constituencies.senatorial_state_id', $campaign_details->senatorial_district_id)
                    ->select('wards.id as wardid', 'local_constituencies.lga as lganame', 'wards.ward_details as wardname')
                    ->paginate(20);
            } elseif ($zonekey == 3) {
                $pollingUnits = DB::table('polling_units')
                    ->join('wards', 'wards.id', '=', 'polling_units.ward_id')
                    ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                    ->where('local_constituencies.senatorial_state_id', $campaign_details->senatorial_district_id)
                    ->select('polling_units.id as id', 'polling_units.lga as lga', 'polling_units.ward_details as wardname', 'polling_units.polling_name as pollingname')
                    ->paginate(20);
            } elseif ($zonekey == 4) {
                $pollingAgents = DB::table('polling_agent')
                    ->join('polling_units', 'polling_units.id', '=', 'polling_agent.polling_units')
                    ->join('wards', 'wards.id', '=', 'polling_units.ward_id')
                    ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                    ->where('local_constituencies.senatorial_state_id', $campaign_details->senatorial_district_id)
                    ->select('polling_agent.id', 'polling_agent.name')
                    ->paginate(20);
            }

            // Set variables for the view
            return response()->json([
                'local_constituencies' => $local_constituencies,
                'wards' => $wards,
                'pollingUnits' => $pollingUnits,
                'pollingAgents' => $pollingAgents
            ]);
        }

        if ($campaign_details->campaign_type == 3) { // House of Representative Campaign
            if ($zonekey == 1) {
                $local_constituencies = Local_constituency::where('federal_constituency_id', $campaign_details->federal_constituency_id)->paginate(20);
            } else if ($zonekey == 2) {
                $wards = DB::table('wards')
                    ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                    ->where('local_constituencies.federal_constituency_id', $campaign_details->federal_constituency_id)
                    ->select('wards.id as wardid', 'local_constituencies.lga as lganame', 'wards.ward_details as wardname')
                    ->paginate(20);
            } else if ($zonekey == 3) {
                $pollingUnits = DB::table('polling_units')
                    ->join('wards', 'wards.id', '=', 'polling_units.ward_id')
                    ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                    ->where('local_constituencies.federal_constituency_id', $campaign_details->federal_constituency_id)
                    ->select('polling_units.id', 'polling_units.lga', 'polling_units.ward_details as wordname', 'polling_units.polling_name as pollingname')
                    ->paginate(20);
            } else if ($zonekey == 4) {
                $pollingAgents = DB::table('polling_agent')
                    ->join('polling_units', 'polling_units.id', '=', 'polling_agent.polling_units')
                    ->join('wards', 'wards.id', '=', 'polling_units.ward_id')
                    ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                    ->where('local_constituencies.federal_constituency_id', $campaign_details->federal_constituency_id)
                    ->whereNotNull('polling_agent.name')
                    ->select('polling_agent.id', 'polling_agent.name')
                    ->paginate(20);
            }

            return response()->json([
                'local_constituencies' => $local_constituencies,
                'wards' => $wards,
                'pollingUnits' => $pollingUnits,
                'pollingAgents' => $pollingAgents
            ]);
        }

        if ($campaign_details->campaign_type == 4) { // Governorship Campaign
            if ($zonekey == 1) {
                $local_constituencies = Local_constituency::where('state_id', $campaign_details->state)->paginate(20);
            } else if ($zonekey == 2) {
                $wards = DB::table('wards')
                    ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                    ->where('local_constituencies.state_id', $campaign_details->state)
                    ->select('wards.id as wardid', 'local_constituencies.lga as lganame', 'wards.ward_details as wardname')
                    ->paginate(20);
            } else if ($zonekey == 3) {
                $pollingUnits = DB::table('polling_units')
                    ->join('wards', 'wards.id', '=', 'polling_units.ward_id')
                    ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                    ->where('local_constituencies.state_id', $campaign_details->state)
                    ->select('polling_units.id', 'polling_units.lga', 'polling_units.ward_details as wordname', 'polling_units.polling_name as pollingname')
                    ->paginate(20);
            } else if ($zonekey == 4) {
                $pollingAgents = DB::table('polling_agent')
                    ->join('polling_units', 'polling_units.id', '=', 'polling_agent.polling_units')
                    ->join('wards', 'wards.id', '=', 'polling_units.ward_id')
                    ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                    ->where('local_constituencies.state_id', $campaign_details->state)
                    ->whereNotNull('polling_agent.name')
                    ->select('polling_agent.id', 'polling_agent.name')
                    ->paginate(20);
            }

            return response()->json([
                'local_constituencies' => $local_constituencies,
                'wards' => $wards,
                'pollingUnits' => $pollingUnits,
                'pollingAgents' => $pollingAgents
            ]);
        }

        if ($campaign_details->campaign_type == 5) { // House of Assembly
            if ($zonekey == 1) {
                $local_constituencies = Local_constituency::where('state_id', $campaign_details->state)->paginate(20);
            } else if ($zonekey == 2) {
                $wards = DB::table('wards')
                    ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                    ->where('local_constituencies.state_id', $campaign_details->state)
                    ->select('wards.id as wardid', 'local_constituencies.lga as lganame', 'wards.ward_details as wardname')
                    ->paginate(20);
            } else if ($zonekey == 3) {
                $pollingUnits = DB::table('polling_units')
                    ->join('wards', 'wards.id', '=', 'polling_units.ward_id')
                    ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                    ->where('local_constituencies.state_id', $campaign_details->state)
                    ->select('polling_units.id', 'polling_units.lga', 'polling_units.ward_details as wordname', 'polling_units.polling_name as pollingname')
                    ->paginate(20);
            } else if ($zonekey == 4) {
                $pollingAgents = DB::table('polling_agent')
                    ->join('polling_units', 'polling_units.id', '=', 'polling_agent.polling_units')
                    ->join('wards', 'wards.id', '=', 'polling_units.ward_id')
                    ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                    ->where('local_constituencies.state_id', $campaign_details->state)
                    ->whereNotNull('polling_agent.name')
                    ->select('polling_agent.id', 'polling_agent.name')
                    ->paginate(20);
            }

            return response()->json([
                'local_constituencies' => $local_constituencies,
                'wards' => $wards,
                'pollingUnits' => $pollingUnits,
                'pollingAgents' => $pollingAgents
            ]);
        }

        if ($campaign_details->campaign_type == 6) { // Local Government Chairman
            if ($zonekey == 1) {
                $local_constituencies = Local_constituency::where('id', $campaign_details->local_constituency_id)->paginate(20);
            } else if ($zonekey == 2) {
                $wards = DB::table('wards')
                    ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                    ->where('local_constituencies.id', $campaign_details->local_constituency_id)
                    ->select('wards.id as wardid', 'local_constituencies.lga as lganame', 'wards.ward_details as wardname')
                    ->paginate(20);
            } else if ($zonekey == 3) {
                $pollingUnits = DB::table('polling_units')
                    ->join('wards', 'wards.id', '=', 'polling_units.ward_id')
                    ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                    ->where('local_constituencies.id', $campaign_details->local_constituency_id)
                    ->select('polling_units.id', 'polling_units.lga', 'polling_units.ward_details as wordname', 'polling_units.polling_name as pollingname')
                    ->paginate(20);
            } else if ($zonekey == 4) {
                $pollingAgents = DB::table('polling_agent')
                    ->join('polling_units', 'polling_units.id', '=', 'polling_agent.polling_units')
                    ->join('wards', 'wards.id', '=', 'polling_units.ward_id')
                    ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                    ->where('local_constituency.id', $campaign_details->local_constituency_id)
                    ->whereNotNull('polling_agent.name')
                    ->select('polling_agent.id', 'polling_agent.name')
                    ->paginate(20);
            }

            return response()->json([
                'local_constituencies' => $local_constituencies,
                'wards' => $wards,
                'pollingUnits' => $pollingUnits,
                'pollingAgents' => $pollingAgents
            ]);
        }

        if ($campaign_details->campaign_type == 7) { // NGO/Pressure

            if ($zonekey == 1) {
                $local_constituencies = DB::table('local_constituencies')
                    ->join('states', 'local_constituencies.state_id', '=', 'states.id')
                    ->join('senatorial_states', 'senatorial_states.id', '=', 'local_constituencies.senatorial_state_id')
                    ->where('states.id', $campaign_details->state)
                    ->select('local_constituencies.id as lgaId', 'local_constituencies.lga as lganame', 'states.state as statename', 'senatorial_states.sena_district as senadist')
                    ->paginate(20);
            } else if ($zonekey == 2) {
                $wards = DB::table('wards')
                    ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                    ->join('states', 'states.id', '=', 'local_constituencies.state_id')
                    ->where('states.id', $campaign_details->state)
                    ->select('wards.id as wardid', 'local_constituencies.lga as lganame', 'wards.ward_details as wardname', 'states.state as statename')
                    ->paginate(20);
            } else if ($zonekey == 3) {
                $pollingUnits = DB::table('polling_units')
                    ->join('states', 'states.id', '=', 'polling_units.state_id')
                    ->where('states.id', $campaign_details->state)
                    ->select('polling_units.state_name as statename', 'polling_units.lga as lganame', 'polling_units.ward_details as wardname', 'polling_units.polling_name as pollingname', 'polling_units.polling_capacity as pollingcapacity', 'polling_units.Delims as delimname')
                    ->paginate(20);
            } else if ($zonekey == 4) {
                $pollingAgents = DB::table('polling_agent')
                    ->join('polling_units', 'polling_units.id', '=', 'polling_agent.polling_units')
                    ->join('wards', 'wards.id', '=', 'polling_units.ward_id')
                    ->join('local_constituencies', 'local_constituencies.id', '=', 'wards.lga_id')
                    ->where('local_constituencies.state_id', $campaign_details->state)
                    ->whereNotNull('polling_agent.name')
                    ->select('polling_agent.id', 'polling_agent.name')
                    ->paginate(20);
            } else if ($zonekey == 5) {
                $states = DB::table('states')
                    ->where('id', $campaign_details->state)
                    ->select('id as stateid', 'state as statename')
                    ->paginate(20);
            } else if ($zonekey == 6) {
                $senatestates = DB::table('senatorial_states')
                    ->join('states', 'states.id', '=', 'senatorial_states.state_id')
                    ->where('states.id', $campaign_details->state)
                    ->select('senatorial_states.id as senatorialStateId', 'senatorial_states.sena_district as senatorialStateName', 'states.state as statename')
                    ->paginate(20);
            } else if ($zonekey == 7) {
                $federalcons = DB::table('federal_constituencies')
                    ->join('states', 'states.id', '=', 'federal_constituencies.state_id')
                    ->where('states.id', $campaign_details->state)
                    ->select('federal_constituencies.id as federalConstituencyId', 'federal_constituencies.federal_name as federalConstituencyName', 'states.state as statename')
                    ->paginate(20);
            }

            return response()->json([
                'local_constituencies' => $local_constituencies,
                'wards' => $wards,
                'pollingUnits' => $pollingUnits,
                'pollingAgents' => $pollingAgents,
                'states' => $states,
                'senatestates' => $senatestates,
                'federalcons' => $federalcons
            ]);
        }


        // Default response if no conditions met
        return response()->json([
            'message' => 'No data found for the given parameters'
        ]);
    }


    public function getStateCounts($stateId)
    {
        $state = State::withCount([
            'localConstituencies',
            'senatorialStates',
            'federalConstituencies',
            'pollingUnits'
        ])->findOrFail($stateId);

        $wardsCount = Ward::whereHas('localConstituency', function ($query) use ($stateId) {
            $query->where('state_id', $stateId);
        })->count();

        $pollingAgentsCount = PollingAgent::whereHas('pollingUnit', function ($query) use ($stateId) {
            $query->where('state_id', $stateId);
        })->count();

        return response()->json([
            'state' => $state->state,
            'local_constituencies_count' => $state->local_constituencies_count,
            'senatorial_states_count' => $state->senatorial_states_count,
            'federal_constituencies_count' => $state->federal_constituencies_count,
            'wards_count' => $wardsCount,
            'polling_units_count' => $state->polling_units_count,
            'polling_agents_count' => $pollingAgentsCount,
        ]);
    }


    public function getLgaCounts($lgaId)
    {
        $localConstituency = Local_constituency::withCount([
            'wards',
        ])->findOrFail($lgaId);

        $pollingUnitsCount = Polling_unit::where('ward_id', $lgaId)
            ->count();

        $pollingAgentsCount = PollingAgent::whereHas('pollingUnit', function ($query) use ($lgaId) {
            $query->whereIn('ward_id', function ($query) use ($lgaId) {
                $query->select('id')
                    ->from('wards')
                    ->where('lga_id', $lgaId);
            });
        })->count();

        return response()->json([
            'lga' => $localConstituency->lga,
            'wards_count' => $localConstituency->wards_count,
            'polling_units_count' => $pollingUnitsCount,
            'polling_agents_count' => $pollingAgentsCount,
        ]);
    }

    public function getgrassrooterdata()
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userid = $user->id;

        $grassrooters = AddMember::where([
            ['role_type', '=', 2],
            ['is_active', '=', 1],
            ['user_id', '=', $userid]
        ])
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $grassrooters
        ], 200);
    }
}
