<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Party;
use App\Models\PartyVote;
use App\Models\Polling_unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Service;
use App\Models\State;
use App\Models\StateVote;
use App\Models\VoteImport;
use Illuminate\Support\Facades\Auth;

class ManageElectionController extends Controller
{
    public function list(Request $request)
    {
    }

    public function create()
    {
        // $pollingUnits = Polling_unit::select('polling_name')
        //     ->where('polling_name', '!=', '')
        //     ->orderBy('polling_name')
        //     ->distinct()
        //     ->get();
        return view('admin.manageelections.add');
    }


    public function store(Request $request)
    {
        $userID = Auth::id();
        $electiondd = new VoteImport();
        $electiondd->polling_units = $request->input('polling_units');
        $electiondd->election_year = $request->input('election_year');
        $electiondd->votes = $request->input('votes');
        $electiondd->political_party = $request->input('political_party');
        $electiondd->user_id = $userID;
        $electiondd->created_at = now();
        $electiondd->updated_at = now();

        $electiondd->save();

        return redirect()->route('manageservices.list')->with('message', 'Election Result Added Successfully !');
    }

    public function getPollingUnits()
    {

        // $pollingUnits = Polling_unit::where('polling_name', '!=', '')
        //     ->orderBy('polling_name')
        //     ->groupBy('polling_name')
        //     ->get(['id', 'polling_name']);

        $pollingUnits = Polling_unit::where('polling_name', '!=', '')
            ->orderBy('polling_name')
            ->groupBy('polling_name')
            ->get([
                DB::raw('MAX(id) as id'),
                'polling_name'
            ]);


        $data = $pollingUnits->map(function ($unit) {
            return [
                'id' => $unit->id,
                'text' => $unit->polling_name
            ];
        });

        return response()->json($data);
    }

    public function edit($id)
    {
    }


    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }

    public function status(Request $request)
    {
    }

    public function addImport()
    {
        return view('admin.votesimports.votes-import');
    }

    public function importPartyVotesResult(Request $request)
    {
        if ($request->hasFile('upload_csv')) {
            $file = $request->file('upload_csv');
            $path = $file->getRealPath();

            if (($handle = fopen($path, 'r')) !== FALSE) {
                $row = 1;
                while (($data = fgetcsv($handle, 0, ',')) !== FALSE) {
                    if ($row > 1) {
                        $allpost = $request->all();

                        $state = State::where('state', $data[0])->first();
                        $stateVote = StateVote::where('state_id', $state->id)
                            ->where('election_year', $data[3])
                            ->orderBy('id', 'desc')
                            ->first();

                        $allpost['state_vote_id'] = $stateVote->id ?? null;
                        $allpost['state_id'] = $state->id ?? '';

                        if (!empty($data[1])) {
                            $party = Party::where('party_name', $data[1])->first();
                            $allpost['party_id'] = $party->id ?? '';
                        } else {
                            $allpost['party_id'] = '';
                        }

                        $allpost['vote_value'] = !empty($data[2]) ? trim(str_replace(',', '', $data[2])) : '';
                        $allpost['election_year'] = $data[3] ?? '';
                        $allpost['created_at'] = now();

                        $newVote = new PartyVote($allpost);
                        $newVote->save();
                    }
                    $row++;
                }
                fclose($handle);
            }
            return redirect()->route('votesimport.addImport')->with('message', 'Party Vote Result Imported into the Database Successfully');
        }
        return redirect()->route('votesimport.addImport')->with('error', 'No CSV file uploaded');
    }

    public function importStatewiseVote(Request $request)
    {
        if ($request->hasFile('upload_csv2')) {
            $file = $request->file('upload_csv2');
            $path = $file->getRealPath();

            if (($handle = fopen($path, 'r')) !== FALSE) {
                $row = 1;
                while (($data = fgetcsv($handle, 0, ',')) !== FALSE) {
                    if ($row > 1) { // Skip header row
                        $allpost = $request->except('_token');

                        $state = State::where('state', $data[0])->first();

                        if ($state) {
                            $allpost['state_id'] = $state->id;

                            $allpost['accredited_votes'] = !empty($data[1]) ? trim(str_replace(',', '', $data[1])) : null;
                            $allpost['valid_votes'] = !empty($data[2]) ? trim(str_replace(',', '', $data[2])) : null;
                            $allpost['election_year'] = $data[3] ?? null;
                            $allpost['created'] = now();

                            $newStateVote = new StateVote($allpost);
                            $saveVotes = $newStateVote->save();
                        }
                    }
                    $row++;
                }
                fclose($handle);
            }
            return redirect()->route('votesimport.addImport')->with('message', 'Statewise Election Result Imported into the Database Successfully');
        }
        return redirect()->route('votesimport.addImport')->with('error', 'No CSV file uploaded');
    }
}
