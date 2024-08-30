<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Electionresulttype;
use App\Models\ElectionType;
use App\Models\Party;
use App\Models\PartyVote;
use App\Models\State;
use Illuminate\Http\Request;

class ElectionResultController extends Controller
{
    public function electionresults()
    {
        $electiondata = Electionresulttype::get();
        $electiontype = [];
        foreach ($electiondata as $edata) {
            $electiontype[] = [
                'id' => $edata->id,
                'election_type' => $edata->type,
            ];
        }

        $party = Party::where('is_active', 1)->get();
        $partydata = [];
        foreach ($party as $p) {
            $partydata[] = [
                'id' => $p->id,
                'party_name' => $p->party_name,
                'party_acronym' => $p->party_acronym,
                'party_logo' => $p->party_img,
                'party_color' => $p->color,
            ];
        }

        $states = State::get();
        $statedata = [];
        foreach ($states as $s) {
            $statedata[] = [
                'id' => $s->id,
                'state_name' => $s->state,
            ];
        }

        return response()->json([
            'electiontype' => $electiontype,
            'political_parties' => $partydata,
            'statedata' => $statedata
        ]);
    }

    public function getyearontype(Request $request)
    {
        $electiontype = $request->get('election_type');

        $years = PartyVote::where('election_type', $electiontype)
            ->distinct()
            ->orderBy('election_year', 'asc')
            ->pluck('election_year');

        $electionyears = [];
        foreach ($years as $y) {
            $electionyears[] = $y;
        }

        return response()->json([
            'electionyears' => $electionyears
        ]);
    }

    public function filterelectionresults(Request $request)
    {
        $election_year = $request->get('election_year');
        $stateid = $request->get('state_id');
        $electiontype = $request->get('election_type');

        $parties = Party::where('is_active', 1)->get();
        $states = State::get();

        if (count($parties) > 0) {
            foreach ($parties as $partDetails) {
                if ($electiontype != 1) {
                    $totvotevalue = PartyVote::where('party_id', $partDetails->id)
                        ->where('election_year', $election_year)
                        ->where('election_type', $electiontype)
                        ->where('state_id', $stateid)
                        ->sum('vote_value');

                    $allpartyVotes = PartyVote::select('party_id')
                        ->selectRaw('SUM(vote_value) as total_votes')
                        ->where('state_id', $stateid)
                        ->where('election_year', $election_year)
                        ->where('election_type', $electiontype)
                        ->groupBy('party_id')
                        ->with('party')
                        ->orderBy('total_votes', 'DESC')
                        ->get();

                    $winparty = $allpartyVotes->first();
                } else {
                    $totvotevalue = PartyVote::where('party_id', $partDetails->id)
                        ->where('election_year', $election_year)
                        ->where('election_type', $electiontype)
                        ->sum('vote_value');

                    $allpartyVotes = PartyVote::select('party_id')
                        ->selectRaw('SUM(vote_value) as total_votes')
                        ->where('election_year', $election_year)
                        ->where('election_type', $electiontype)
                        ->groupBy('party_id')
                        ->with('party')
                        ->orderBy('total_votes', 'DESC')
                        ->get();

                    $winparty = $allpartyVotes->first();
                }

                $partyVoteCount = $totvotevalue;
                $partyVoteCounts[] = $partyVoteCount;
                $partyDetailss[] = $partDetails;
            }


            $cntWinn = [];
            $cntState = [];

            foreach ($parties as $partyDet) {
                $cntWin = 0;

                foreach ($states as $statDetCheck) {
                    if ($electiontype != 1) {
                        $winId = PartyVote::where('state_id', $statDetCheck->id)
                            ->where('state_id', $stateid)
                            ->where('election_year', $election_year)
                            ->where('election_type', $electiontype)
                            ->orderBy('vote_value', 'DESC')
                            ->first();
                    } else {
                        $winId = PartyVote::where('state_id', $statDetCheck->id)
                            ->where('election_year', $election_year)
                            ->where('election_type', $electiontype)
                            ->orderBy('vote_value', 'DESC')
                            ->first();
                    }

                    if ($winId && $winId->party_id == $partyDet->id) {
                        $cntWin++;
                    }
                }

                $cntWinn[] = $cntWin;
                $cntState[] = count($states) - $cntWin;
            }


            //Table Start

            $results = []; // Initialize results array

            // Iterate through all states
            foreach ($states as $stateDetRes) {
                $stateResult = [];
                $stateResult['state'] = $stateDetRes->state;

                // Determine the winning party for the current state
                if ($electiontype != 1) {
                    $winPartyDet = PartyVote::where('state_id', $stateDetRes->id)
                        ->where('state_id', $stateid)
                        ->where('election_year', $election_year)
                        ->where('election_type', $electiontype)
                        ->orderBy('vote_value', 'DESC')
                        ->first();
                } else {
                    $winPartyDet = PartyVote::where('state_id', $stateDetRes->id)
                        ->where('election_year', $election_year)
                        ->where('election_type', $electiontype)
                        ->orderBy('vote_value', 'DESC')
                        ->first();
                }

                // Iterate through all parties
                foreach ($parties as $partyDetRes) {
                    $partyData = [];
                    $partyData['party_acronym'] = $partyDetRes->party_acronym;
                    $backgroundColor = "";

                    // Check if the party is the winning party for the state
                    if ($winPartyDet && $winPartyDet->party_id == $partyDetRes->id) {
                        $backgroundColor = $partyDetRes->color;
                    }

                    $partyData['background_color'] = $backgroundColor;

                    // Fetch vote value for each party
                    if ($electiontype != 1) {
                        $partyVotes = PartyVote::where('party_id', $partyDetRes->id)
                            ->where('state_id', $stateDetRes->id)
                            ->where('state_id', $stateid)
                            ->where('election_year', $election_year)
                            ->where('election_type', $electiontype)
                            ->first();
                    } else {
                        $partyVotes = PartyVote::where('party_id', $partyDetRes->id)
                            ->where('state_id', $stateDetRes->id)
                            ->where('election_type', $electiontype)
                            ->where('election_year', $election_year)
                            ->first();
                    }

                    // Set vote value or default to '_'
                    $partyData['vote_value'] = $partyVotes ? $partyVotes->vote_value : '_';
                    $stateResult['parties'][] = $partyData; // Add party data to state result
                }

                $results['states'][] = $stateResult; // Add state result to results
            }

            // Calculate totals for each party across all states
            $totalResults = [];
            foreach ($parties as $partyDetRes) {
                if ($electiontype != 1) {
                    $totalVoteValue = PartyVote::where('party_id', $partyDetRes->id)
                        ->where('state_id', $stateid)
                        ->where('election_year', $election_year)
                        ->where('election_type', $electiontype)
                        ->sum('vote_value');
                } else {
                    $totalVoteValue = PartyVote::where('party_id', $partyDetRes->id)
                        ->where('election_year', $election_year)
                        ->where('election_type', $electiontype)
                        ->sum('vote_value');
                }

                $totalResults[] = [
                    'party_acronym' => $partyDetRes->party_acronym,
                    'total_vote_value' => $totalVoteValue
                ];
            }

            // End Table
            // dd($cntState);
        }

        return response()->json([
            'partyVoteCounts' => $partyVoteCounts,
            'partyDetailss' => $partyDetailss,
            'allpartyVotes' => $allpartyVotes,
            'winparty' => $winparty,
            'seatwinn' => $cntWinn,
            'table_results' => $results,
            'table_total_results' => $totalResults
        ]);
    }

    public function getStateVoteTooltip(Request $request)
    {
        if ($request->isMethod('post')) {
            $election_year = $request->input('election_year');
            $choosestateid = $request->input('state_id');
            $electiontype = $request->input('election_type');
            $stateid = $request->input('regionclick_stateid');

            // Check if both stateid and choosestateid are set and not empty
            if (!empty($stateid) && !empty($choosestateid)) {
                // Check if the state ID from the hover matches the chosen state ID
                if ($stateid == $choosestateid) {
                    $stateDet = State::where('id', $stateid)->first();

                    if ($electiontype != 1) {
                        $partyVotes = PartyVote::where([
                            ['state_id', '=', $stateDet->id],
                            ['election_year', '=', $election_year],
                            ['election_type', '=', $electiontype]
                        ])->orderBy('vote_value', 'DESC')->get();
                    } else {
                        $partyVotes = PartyVote::where([
                            ['state_id', '=', $stateDet->id],
                            ['election_year', '=', $election_year],
                            ['election_type', '=', $electiontype]
                        ])->orderBy('vote_value', 'DESC')->get();
                    }

                    $response = [
                        'state' => $stateDet->state,
                        'parties' => []
                    ];

                    if (count($partyVotes) > 0) {
                        foreach ($partyVotes as $partyVoteDetails) {
                            $partyDetails = Party::where('id', $partyVoteDetails->party_id)->orderBy('id', 'desc')->first();
                            $response['parties'][] = [
                                'party_name' => $partyDetails->party_name,
                                'vote_value' => $partyVoteDetails->vote_value,
                                'color' => $partyDetails->color
                            ];
                        }
                    } else {
                        $response['message'] = 'No data available for the selected year';
                    }

                    return response()->json($response);
                }
            } else if (!empty($stateid)) {
                $stateDet = State::where('id', $stateid)->first();

                if ($electiontype != 1) {
                    $partyVotes = PartyVote::where([
                        ['state_id', '=', $stateDet->id],
                        ['election_year', '=', $election_year],
                        ['election_type', '=', $electiontype]
                    ])->orderBy('vote_value', 'DESC')->get();
                } else {
                    $partyVotes = PartyVote::where([
                        ['state_id', '=', $stateDet->id],
                        ['election_year', '=', $election_year],
                        ['election_type', '=', $electiontype]
                    ])->orderBy('vote_value', 'DESC')->get();
                }

                $response = [
                    'state' => $stateDet->state,
                    'parties' => []
                ];

                if (count($partyVotes) > 0) {
                    foreach ($partyVotes as $partyVoteDetails) {
                        $partyDetails = Party::where('id', $partyVoteDetails->party_id)->orderBy('id', 'desc')->first();
                        $response['parties'][] = [
                            'party_name' => $partyDetails->party_name,
                            'vote_value' => $partyVoteDetails->vote_value,
                            'color' => $partyDetails->color
                        ];
                    }
                } else {
                    $response['message'] = 'No data available for the selected year';
                }

                return response()->json($response);
            }
        }

        return response()->json(['message' => 'Invalid request (Some Keys Values Mismatches)'], 400);
    }


    public function getstatevoteonregionclick(Request $request){
        
    }
}
