<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Polling_unit;
use Illuminate\Support\Facades\DB;
use App\Models\Local_constituency;
use App\Models\Ward;
use Yajra\DataTables\Facades\DataTables;

class ManagepollingunitController extends Controller
{
    public function list(Request $request)
    {
        return view('admin.managepollingunits.list');
    }

    public function GetPollingUnitlist(Request $request)
    {
        $pollingUnits = Polling_unit::query();

        return DataTables::of($pollingUnits)
            ->addColumn('actions', function ($row) {
                $btn = '<a href="' . route('managepollings.edit', $row->id) . '" class="edit btn btn-info btn-sm"><i class="fa fa-edit"></i></a>';
                $btn .= ' <a href="' . route('managepollings.destroy', $row->id) . '" class="delete btn btn-danger btn-sm" onclick="return confirm(\'Are you sure to delete!\');"><i class="fa fa-remove"></i></a>';
                return $btn;
            })
            ->editColumn('created_at', function ($row) {
                return !empty($row->created_at) && $row->created_at != '0000-00-00 00:00:00'
                    ? date('d-M-Y', strtotime($row->created_at))
                    : 'N/A';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }


    public function create()
    {
        return view('admin.managepollingunits.add');
    }

    public function store(Request $request)
    {
        $all_post = $request->only(['polling_name', 'polling_capacity', 'ward_details']);

        // Check if the polling unit already exists
        $check = Polling_unit::where('polling_name', $all_post['polling_name'])
            ->where('polling_capacity', $all_post['polling_capacity'])
            ->where('ward_details', $all_post['ward_details'])
            ->first();

        if ($check) {
            return back()->withErrors('Polling Unit already exists');
        } else {
            $statename = DB::table('states')->where('state_id', $request->state_id)->first();
            $lganame = DB::table('local_constituencies')->where('id', $request->lga)->first();
            $polling = new Polling_unit();
            $polling->political_zone = $request->input('political_zone');
            $polling->state_id = $request->input('state_id');
            $polling->state_name = $statename->state;
            $polling->lga = $lganame;
            $polling->ward_details = $request->input('ward_details');
            $polling->ward_id = $request->input('imagalt');
            $polling->polling_name = $request->input('polling_name');
            $polling->Delims = $request->input('delims');
            $polling->polling_capacity = $request->input('polling_capacity');
            $polling->created_at = now();
            $polling->updated_at = now();
            $polling->save();
        }

        return redirect()->route('managepollings.list')->with('message', 'Polling Unit created Successfully !');
    }

    public function edit($id)
    {
        $editpollingunit = Polling_unit::findOrFail($id);
        $states = DB::table('states')->pluck('state', 'id');
        // Fetch LGAs related to the state of the polling unit
        $lgas = DB::table('local_constituencies')->where('state_id', $editpollingunit->state_id)->pluck('lga', 'id');

        $lgaid = DB::table('local_constituencies')->where('lga', $editpollingunit->lga)->value('id');

        // Fetch wards related to the selected LGA
        $wards = Ward::where('lga_id', $lgaid)->pluck('ward_details', 'id');

        return view('admin.managepollingunits.edit', compact('editpollingunit', 'states', 'lgas', 'wards'));
    }


    public function update(Request $request, $id)
    {
        $all_post = $request->only(['polling_name', 'polling_capacity', 'ward_details']);

        // Find the existing polling unit record
        $polling = Polling_unit::findOrFail($id);

        // Check if another polling unit with the same criteria exists
        $check = Polling_unit::where('polling_name', $all_post['polling_name'])
            ->where('polling_capacity', $all_post['polling_capacity'])
            ->where('ward_details', $all_post['ward_details'])
            ->where('id', '!=', $id)
            ->first();

        if ($check) {
            return back()->withErrors('Polling Unit already exists');
        } else {
            $statename = DB::table('states')->where('state_id', $request->state_id)->first();
            $lganame = DB::table('local_constituencies')->where('id', $request->lga)->first();
            // Update the polling unit with the new data
            $polling->political_zone = $request->input('political_zone');
            $polling->state_id = $request->input('state_id');
            $polling->state_name = $statename->state;
            $polling->lga = $lganame;
            $polling->ward_details = $request->input('ward_details');
            $polling->ward_id = $request->input('imagalt');
            $polling->polling_name = $request->input('polling_name');
            $polling->Delims = $request->input('delims');
            $polling->polling_capacity = $request->input('polling_capacity');
            $polling->updated_at = now();
            $polling->save();
        }

        return redirect()->route('managepollings.list')->with('message', 'Polling Unit updated Successfully !');
    }

    public function destroy($id)
    {
        $locconst = Polling_unit::find($id); // Find the item by its ID
        if (!$locconst) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $locconst->delete(); // Delete the item

        return redirect()->route('managepollings.list')->with('message', 'Site content Removed successfully !.'); // Redirect to the index page with success message
    }

    public function getLgas(Request $request)
    {
        $stateId = $request->input('id');
        $lgas = Local_constituency::where('state_id', $stateId)->get();

        if ($lgas->isEmpty()) {
            return response()->json(['code' => 200, 'status' => []]);
        }

        return response()->json(['code' => 200, 'status' => $lgas]);
    }

    public function getwards(Request $request)
    {
        $lgaid = $request->input('id');
        $lgas = Ward::where('lga_id', $lgaid)->get();

        if ($lgas->isEmpty()) {
            return response()->json(['code' => 300, 'status' => []]);
        }

        return response()->json(['code' => 300, 'status' => $lgas]);
    }


    // public function status(Request $request){
    //     $get_id=$request->id;
    //     $catstatus=DB::table('polling_units')
    //     ->select('is_active')
    //     ->where('id','=',$get_id)
    //     ->first();


    //     $astatus=$catstatus->is_active;
    //      if($astatus == '1'){
    //          $astatus='0'; 
    //      } else{
    //          $astatus='1';
    //      }
    //      $statusupdate=DB::table('polling_units')
    //      ->where('id', $get_id)
    //      ->update(array('is_active'=>$astatus));

    //      if($statusupdate){
    //          return response()->json([
    //              'status' => 'success',
    //              'code' => 200,
    //          ]);
    //         }
    //     }

}
