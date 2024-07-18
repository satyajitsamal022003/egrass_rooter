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
                return '<a class="btn btn-info" href="' . $editUrl . '" title="Edit"><i class="fa fa-edit"></i></a>
                        <a class="btn btn-danger" href="' . $deleteUrl . '" onclick="return confirm(\'Are you sure to delete!\');" title="Delete"><i class="fa fa-remove"></i></a>';
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
        $partyadd = new Federal_constituency();
        $partyadd->state_id = $request->input('state');
        $partyadd->federal_name = $request->input('constituency');
        $partyadd->code = $request->input('code');
        $partyadd->collation_center = $request->input('collation_centre');
        $partyadd->created_at = now();
        $partyadd->updated_at = now();
        $partyadd->save();

        return redirect()->route('federalconst.list')->with('message', 'Federal constituency created Successfully!');
    }

    public function edit($id)
    {
        $fedconst = Federal_constituency::find($id);
        // dd('implement needed');
        return view('admin.managefederal_constituency.edit', compact('fedconst'));
    }
}
