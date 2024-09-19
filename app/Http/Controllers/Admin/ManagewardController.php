<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Local_constituency;
use Illuminate\Http\Request;
use App\Models\Ward;
use Yajra\DataTables\Facades\DataTables;

class ManagewardController extends Controller
{
    public function list(Request $request)
    {

        // $searchtxt = $request->input('searchtxt');

        // // Check if search term is present
        // if ($searchtxt) {
        //     $localcont = Ward::where('lga', 'like', '%' . $searchtxt . '%')->get();
        // } else {
        //     $localcont = Ward::all();
        // }

        return view('admin.managewards.list');
    }

    public function GetWardlist(Request $request)
    {
        $query = Ward::query();

        if ($request->searchtxt) {
            $query->whereHas('localConstituency', function ($q) use ($request) {
                $q->where('lga', 'like', '%' . $request->searchtxt . '%');
            });
        }

        return DataTables::of($query)
            ->addColumn('state', function ($row) {
                $state = $row->localConstituency;
                return $state ? $state->lga : '';
            })
            ->addColumn('actions', function ($row) {
                return '<a class="btn btn-info" href="' . route('manageward.edit', $row->id) . '" title="Edit"><i class="fa fa-edit"></i></a>
                    <a class="btn btn-danger" href="' . route('manageward.destroy', $row->id) . '" onclick="return confirm(\'Are you sure to delete!\');" title="Delete"><i class="fa fa-remove"></i></a>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.managewards.add');
    }

    public function store(Request $request)
    {
        $lga = new Ward();
        $lga->lga_id = $request->input('lga');
        $lga->ward_details = $request->input('wardname');
        $lga->ward_no = $request->input('wardno');
        $lga->created_at = now();
        $lga->updated_at = now();
        $lga->save();

        return redirect()->route('manageward.list')->with('message', 'Ward created Successfully !');
    }

    public function edit($id)
    {
        $editward = Ward::find($id);
        return view('admin.managewards.edit', compact('editward'));
    }


    public function update(Request $request, $id)
    {
        $lga = Ward::findOrFail($id);
        $lga->lga_id = $request->input('lga');
        $lga->ward_details = $request->input('wardname');
        $lga->ward_no = $request->input('wardno');
        $lga->updated_at = now();
        $lga->save();

        return redirect()->route('manageward.list')->with('message', 'Ward Updated Successfully !');
    }

    public function destroy($id)
    {
        $locconst = Ward::find($id); // Find the item by its ID
        if (!$locconst) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $locconst->delete(); // Delete the item

        return redirect()->route('manageward.list')->with('message', 'Ward Removed successfully !.'); // Redirect to the index page with success message
    }
}
