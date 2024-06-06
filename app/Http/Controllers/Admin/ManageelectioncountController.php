<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PartyVote;
use Illuminate\Support\Facades\DB;
use App\Models\Party_vote;

class ManageelectioncountController extends Controller
{
    public function list(Request $request){
        $partyvoteslist = PartyVote::get(); 
            return view('admin.manageelectioncount.list', compact('partyvoteslist'));
    }

    public function create(){
        return view('admin.manageelectioncount.add');
    }

    public function store(Request $request){
        $partyvoteadd = new Party_vote();
        // $partyvoteadd->state_vote_id->$request->input('');
        $partyvoteadd->state_id->$request->input('state');
        $partyvoteadd->party_id->$request->input('party');
        $partyvoteadd->vote_value->$request->input('votevalue');
        $partyvoteadd->created_at->$request->input('');
        $partyvoteadd->updated_at->$request->input('');

        return redirect()->route('manageelectionvoters.list')->with('message','Election Voter Added Successfuly !');
    }
}
