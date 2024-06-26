<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Polling_unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Service;

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

        $electiondd = new Votesimport();
        $electiondd->polling_units = $request->input('polling_units');
        $electiondd->election_year = $request->input('election_year');
        $electiondd->votes = $request->input('votes');
        $electiondd->political_party = $request->input('political_party');
        $electiondd->created_at = now();
        $electiondd->updated_at = now();

        $electiondd->save();

        return redirect()->route('manageservices.list')->with('message', 'Service created Successfully !');
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

}
