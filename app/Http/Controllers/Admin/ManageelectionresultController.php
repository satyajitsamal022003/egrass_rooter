<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Party_vote;
use Illuminate\Http\Request;
use App\Models\Vote_import;
use App\Models\Polling_unit;
use App\Models\State;

class ManageelectionresultController extends Controller
{
    public function list(Request $request)
    {
        $votesresultslist = Vote_import::get();
        // dd($votesresultslist);
        return view('admin.manageelectionresult.list', compact('votesresultslist'));
    }

    public function add()
    {
        return view('admin.manageelectionresult.add');
    }

    public function store(Request $request)
    {

        $all_post = $request->only(['election_type', 'state_id', 'party_id', 'vote_value', 'election_year']);

        // Check if the Vote value already exists
        $check = Party_vote::where('election_type', $all_post['election_type'])
            ->where('state_id', $all_post['state_id'])
            ->where('party_id', $all_post['party_id'])
            ->where('election_year', $all_post['election_year'])
            ->first();

        if ($check) {
            return back()->withErrors('Data for this entry has already been added. Please edit it instead!');
        }


        $partyvoteadd = new Party_vote();
        $partyvoteadd->election_type = $request->input('election_type');
        $partyvoteadd->state_id = $request->input('state_id');
        $partyvoteadd->party_id = $request->input('party_id');
        $partyvoteadd->vote_value = $request->input('vote_value');
        $partyvoteadd->election_year = $request->input('election_year');
        $partyvoteadd->created_at = NOW();
        $partyvoteadd->updated_at = NOW();

        $state = State::where('id', $request->input('state_id'))->first();

        return redirect()->route('manageelectionresult.list')->with('message', 'Election Result Added for ' . $state->state . 'State Successfully!');
    }

    public function destroy($id)
    {
        $locconst = Party_vote::find($id); // Find the item by its ID
        if (!$locconst) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $locconst->delete(); // Delete the item

        return redirect()->route('manageelectionresult.list')->with('message', 'Election-result deleted successfully !.'); // Redirect to the index page with success message
    }

    public function edit($id)
    {
        $editelectionresult = Party_vote::find($id);

        // dd($editelectionresult);
        return view('admin.manageelectionresult.edit', compact('editelectionresult'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request data (optional but recommended)
        $validatedData = $request->validate([
            'election_type' => 'required',
            'state_id' => 'required',
            'party_id' => 'required',
            'vote_value' => 'required',
            'election_year' => 'required',
        ]);

        // Find the existing Party_vote record by ID
        $partyvote = Party_vote::find($id);

        if (!$partyvote) {
            return back()->withErrors('The record was not found.');
        }

        // Check if the data is already present for another record
        $check = Party_vote::where('election_type', $validatedData['election_type'])
            ->where('state_id', $validatedData['state_id'])
            ->where('party_id', $validatedData['party_id'])
            ->where('election_year', $validatedData['election_year'])
            ->where('id', '<>', $id) // Exclude the current record
            ->first();

        if ($check) {
            return back()->withErrors('Data for this entry already exists. Please choose different values.');
        }

        // Update the Party_vote record
        $partyvote->election_type = $validatedData['election_type'];
        $partyvote->state_id = $validatedData['state_id'];
        $partyvote->party_id = $validatedData['party_id'];
        $partyvote->vote_value = $validatedData['vote_value'];
        $partyvote->election_year = $validatedData['election_year'];
        $partyvote->updated_at = NOW(); // Use Laravel's now() helper

        // Save the updated record
        $partyvote->save();

        // Fetch the state name for the success message
        $state = State::where('id', $validatedData['state_id'])->first();

        // Redirect with success message
        return redirect()->route('manageelectionresult.list')
            ->with('message', 'Election Result Updated for ' . $state->state . ' State Successfully!');
    }
}
