<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vote_import;
use App\Models\Polling_unit;

class ManageelectionresultController extends Controller
{
    public function list(Request $request){
        $votesresultslist = Vote_import::get(); 
        // dd($votesresultslist);
            return view('admin.manageelectionresult.list', compact('votesresultslist'));
    }

    public function fetchPollingUnits(Request $request)
    {
        $search = $request->get('q');
        $page = $request->get('page', 1);
        $perPage = 30;

        $query = Polling_unit::query();

        if ($search) {
            $query->where('polling_name', 'like', '%' . $search . '%');
        }

        $results = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'items' => $results->items(),
            'total' => $results->total()
        ]);
    }

    public function create(){
        
        return view('admin.manageelectionresult.add');
    }

    public function electpollingunits(Request $request)
    {
        $search = $request->get('q');
        
        $pollingUnits = Polling_unit::query()
            ->when($search, function ($query, $search) {
                return $query->where('polling_name', 'like', "%{$search}%");
            })
            ->select('id', 'polling_name')
            ->get();
        
        return response()->json($pollingUnits);
    }

    public function store(Request $request){
        $partyvoteadd = new Vote_import();
        $partyvoteadd->polling_units->$request->input('state');
        $partyvoteadd->election_year->$request->input('party');
        $partyvoteadd->votes->$request->input('votevalue');
        $partyvoteadd->political_party->$request->input('votevalue');
        $partyvoteadd->user_id->Auth::user()->id;
        $partyvoteadd->created_at->NOW();
        $partyvoteadd->updated_at->NOW();

        return redirect()->route('manageelectionresult.list')->with('message','Election Result Added Successfuly !');
    }

    public function destroy($id)
        {
            $locconst = Vote_import::find($id); // Find the item by its ID
            if (!$locconst) {
                return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
            }
    
            $locconst->delete(); // Delete the item
    
             return redirect()->route('manageelectionresult.list')->with('message', 'Voter-result deleted successfully !.'); // Redirect to the index page with success message
        }
}
