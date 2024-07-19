<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;
use App\Models\Federal_constituency;
use Yajra\DataTables\Facades\DataTables;

class ManagefederalconstituencyController extends Controller
{

    public function list()
    {
        return view('admin.managefederal_constituency.list');
    }

    public function getFederalConstituency(Request $request)
    {
        $query = Federal_constituency::query();

        if ($searchtxt = $request->get('searchtxt')) {
            $query->where('federal_name', 'like', '%' . $searchtxt . '%');
        }

        return DataTables::of($query)
            ->addColumn('state_name', function ($row) {
                $stateDet = State::where('id', $row->state_id)->orderBy('id', 'desc')->first();
                return $stateDet ? $stateDet->state : 'N/A';
            })
            ->addColumn('actions', function ($row) {
                $editUrl = route('federalconst.edit', $row->id);
                $deleteUrl = route('federalconst.destroy', $row->id);
                return '<td class="center" style="width: 102px;">
                            <a class="btn btn-info" href="' . $editUrl . '" title="Edit"><i class="fa fa-edit"></i></a>
                            <a class="btn btn-danger" href="' . $deleteUrl . '" onclick="return confirm(\'Are you sure to delete!\');" title="Delete"><i class="fa fa-remove"></i></a>
                        </td>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
    public function create()
    {
        return view('admin.managefederal_constituency.add');
    }

    public function store(Request $request)
    {
        // dd('stop');
        $federalcons = new Federal_constituency();
        $federalcons->state_id = $request->input('state');
        $federalcons->federal_name = $request->input('constituency');
        $federalcons->code = $request->input('code');
        $federalcons->collation_center = $request->input('collation_centre');
        $federalcons->created_at = now();
        $federalcons->updated_at = now();
        $federalcons->save();

        return redirect()->route('federalconst.list')->with('message', 'Federal constituency created Successfully!');
    }

    public function edit($id)
    {
        $editfederal = Federal_constituency::find($id);
        // dd($editfederal);
        return view('admin.managefederal_constituency.edit', compact('editfederal'));
    }


    public function update(Request $request,$id){
        $federalcons = Federal_constituency::findOrFail($id);
        $federalcons->state_id = $request->input('state');
        $federalcons->federal_name = $request->input('constituency');
        $federalcons->code = $request->input('code');
        $federalcons->collation_center = $request->input('collation_centre');
        $federalcons->created_at = now();
        $federalcons->updated_at = now();
        $federalcons->save();
        
        return redirect()->route('federalconst.list')->with('message', 'Federal constituency Updated Successfully!');
    }


    public function destroy($id)
    {
        $federalcons = Federal_constituency::find($id); // Find the item by its ID
        if (!$federalcons) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $federalcons->delete(); // Delete the item

         return redirect()->route('federalconst.list')->with('message', 'Federal constituency Removed successfully !.'); // Redirect to the index page with success message
    }
}
