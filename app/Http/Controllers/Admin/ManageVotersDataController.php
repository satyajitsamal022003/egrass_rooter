<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Federal_constituency;
use App\Models\ImportVotersdata;
use App\Models\Local_constituency;
use App\Models\Polling_unit;
use App\Models\Senatorial_state;
use App\Models\State;
use App\Models\State_constituency;
use App\Models\Ward;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

class ManageVotersDataController extends Controller
{
    public function create()
    {

    }

    public function store(Request $request)
    {


    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {

    }


    public function list(Request $request)
    {

        $getAllgender = ImportVotersdata::select('gender')->groupBy('gender')->orderByDesc('id')->get();
        $getAllemp = ImportVotersdata::select('employ_status')->groupBy('employ_status')->orderByDesc('id')->get();

        return view('admin.managevotersdata.list', compact('getAllgender', 'getAllemp'));
    }


    public function GetVotersData(Request $request)
    {
        $query = ImportVotersdata::query();

        if ($request->searchage) {
            switch ($request->searchage) {
                case 1:
                    $query->where('age', '>=', 18)->where('age', '<=', 20);
                    break;
                case 2:
                    $query->where('age', '>=', 21)->where('age', '<=', 30);
                    break;
                case 3:
                    $query->where('age', '>=', 31)->where('age', '<=', 40);
                    break;
                case 4:
                    $query->where('age', '>=', 41)->where('age', '<=', 50);
                    break;
                case 5:
                    $query->where('age', '>=', 51)->where('age', '<=', 60);
                    break;
                case 6:
                    $query->where('age', '>=', 61)->where('age', '<=', 70);
                    break;
                case 7:
                    $query->where('age', '>=', 71)->where('age', '<=', 80);
                    break;
                default:
                    // Handle default case if necessary
                    break;
            }
        }

        if ($request->searchgender) {
            $query->where('gender', $request->searchgender);
        }

        if ($request->searchemp) {
            $query->where('employ_status', 'like', '%' . $request->searchemp . '%');
        }

        if ($request->searchaddress) {
            $query->where('address', 'like', '%' . $request->searchaddress . '%');
        }

        if ($request->state) {
            $query->where('state', $request->state);
        }

        if ($request->senatorial_state) {
            $query->where('senatorial_state', $request->senatorial_state);
        }

        if ($request->federal_constituency) {
            $query->where('federal_constituency', $request->federal_constituency);
        }

        if ($request->local_constituency) {
            $query->where('local_constituency', $request->local_constituency);
        }

        if ($request->ward) {
            $query->where('ward', $request->ward);
        }

        if ($request->state_constituency) {
            $query->where('state_constituency', $request->state_constituency);
        }

        if ($request->polling_unit) {
            $query->where('polling_unit', $request->polling_unit);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('fullname', function ($row) {
                return $row->first_name . ' ' . $row->last_name;
            })
            ->addColumn('email', function ($row) {
                return $row->email;
            })
            ->addColumn('phone', function ($row) {
                return $row->phone;
            })
            ->addColumn('gender', function ($row) {
                return $row->gender;
            })
            ->addColumn('age', function ($row) {
                return $row->age;
            })
            ->addColumn('employ_status', function ($row) {
                return $row->employ_status;
            })
            ->addColumn('address', function ($row) {
                return $row->address;
            })
            ->addColumn('zipcode', function ($row) {
                return $row->zipcode;
            })
            ->addColumn('created_at', function ($row) {
                $created_at = date('d-m-Y', strtotime($row->created));
                return $created_at; // Format the date if necessary
    
            })
            ->addColumn('actions', function ($row) {
                return '<a class="btn btn-info" href="' . route('managevotersdata.edit', $row->id) . '" title="Edit"><i class="fa fa-edit"></i></a>
                    <a class="btn btn-danger" href="' . route('managevotersdata.destroy', $row->id) . '" onclick="return confirm(\'Are you sure to delete!\');" title="Delete"><i class="fa fa-remove"></i></a>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function getStateList()
    {
        $states = State::where('state', '!=', '')->orderBy('state')->get();

        $options = '<option value="">-- Search by State --</option>';
        foreach ($states as $state) {
            $options .= '<option value="' . htmlspecialchars($state->state) . '">' . htmlspecialchars($state->state) . '</option>';
        }

        return $options;
    }


    public function getSenatorialList()
    {
        $senatorialList = Senatorial_state::where('sena_district', '!=', '')->orderBy('sena_district')->get();

        $options = '<option value="">-- Search by Senatorial State --</option>';
        foreach ($senatorialList as $senatorial) {
            $options .= '<option value="' . htmlspecialchars($senatorial->sena_district) . '">' . htmlspecialchars($senatorial->sena_district) . '</option>';
        }

        return $options;
    }

    public function getFederalList()
    {
        $federalList = Federal_constituency::where('federal_name', '!=', '')->orderBy('federal_name')->get();

        $options = '<option value="">-- Search by Federal Constituency --</option>';
        foreach ($federalList as $federal) {
            $options .= '<option value="' . htmlspecialchars($federal->federal_name) . '">' . htmlspecialchars($federal->federal_name) . '</option>';
        }

        return $options;
    }

    public function getLocalConstituencyList()
    {
        $localConstituencyList = Local_constituency::where('lga', '!=', '')->orderBy('lga')->get();

        $options = '<option value="">-- Search by LGA --</option>';
        foreach ($localConstituencyList as $localConstituency) {
            $options .= '<option value="' . htmlspecialchars($localConstituency->lga) . '">' . htmlspecialchars($localConstituency->lga) . '</option>';
        }

        return $options;
    }

    public function getWardList()
    {
        $wardList = Ward::where('ward_details', '!=', '')->orderBy('ward_details')->get();

        $options = '<option value="">-- Search by Ward --</option>';
        foreach ($wardList as $ward) {
            $options .= '<option value="' . htmlspecialchars($ward->ward_details) . '">' . htmlspecialchars($ward->ward_details) . '</option>';
        }

        return $options;
    }

    public function getStateConstituencyList()
    {
        $stateConstituencyList = State_constituency::where('state_constituency', '!=', '')->orderBy('state_constituency')->get();

        $options = '<option value="">-- Search by State Constituency --</option>';
        foreach ($stateConstituencyList as $stateConstituency) {
            $options .= '<option value="' . htmlspecialchars($stateConstituency->state_constituency) . '">' . htmlspecialchars($stateConstituency->state_constituency) . '</option>';
        }

        return $options;
    }

    public function getPollingUnitList()
    {
        $pollingUnitList = Polling_unit::where('polling_name', '!=', '')->orderBy('polling_name')->get();

        $options = '<option value="">-- Search by Polling Unit --</option>';
        foreach ($pollingUnitList as $pollingUnit) {
            $options .= '<option value="' . htmlspecialchars($pollingUnit->polling_name) . '">' . htmlspecialchars($pollingUnit->polling_name) . '</option>';
        }

        return $options;
    }




    // public function filterList(Request $request)
    // {
    //     $stateList = State::where('state', '!=', '')->orderBy('state')->get();
    //     $senatorialList = Senatorial_state::where('sena_district', '!=', '')->orderBy('sena_district')->get();
    //     $federalList = Federal_constituency::where('federal_name', '!=', '')->orderBy('federal_name')->get();
    //     $localConList = Local_constituency::where('lga', '!=', '')->orderBy('lga')->get();
    //     $wardList = Ward::where('ward_details', '!=', '')->orderBy('ward_details')->get();
    //     $staList = State_constituency::where('state_constituency', '!=', '')->orderBy('state_constituency')->get();
    //     $pollingList = Polling_unit::where('polling_name', '!=', '')->orderBy('polling_name')->get();
    //     $getAllgender = ImportVotersdata::select('gender')->groupBy('gender')->orderByDesc('id')->get();
    //     $getAllemp = ImportVotersdata::select('employ_status')->groupBy('employ_status')->orderByDesc('id')->get();

    //     $whr = [];

    //     if ($request->has('search')) {
    //         $searchage = $request->input('searchage');
    //         $searchemp = $request->input('searchemp');
    //         $searchgender = $request->input('searchgender');
    //         $state = $request->input('state');
    //         $senatorial_state = $request->input('senatorial_state');
    //         $federal_constituency = $request->input('federal_constituency');
    //         $local_constituency = $request->input('local_constituency');
    //         $ward = $request->input('ward');
    //         $state_constituency = $request->input('state_constituency');
    //         $polling_unit = $request->input('polling_unit');
    //         $searchaddress = $request->input('searchaddress');

    //         if ($searchage == 1) {
    //             $whr[] = ['age', '>=', 18];
    //             $whr[] = ['age', '<=', 20];
    //         }
    //         if ($searchage == 2) {
    //             $whr[] = ['age', '>=', 21];
    //             $whr[] = ['age', '<=', 30];
    //         }
    //         if ($searchage == 3) {
    //             $whr[] = ['age', '>=', 31];
    //             $whr[] = ['age', '<=', 40];
    //         }
    //         if ($searchage == 4) {
    //             $whr[] = ['age', '>=', 41];
    //             $whr[] = ['age', '<=', 50];
    //         }
    //         if ($searchage == 5) {
    //             $whr[] = ['age', '>=', 51];
    //             $whr[] = ['age', '<=', 60];
    //         }
    //         if ($searchage == 6) {
    //             $whr[] = ['age', '>=', 61];
    //             $whr[] = ['age', '<=', 70];
    //         }
    //         if ($searchage == 7) {
    //             $whr[] = ['age', '>=', 71];
    //             $whr[] = ['age', '<=', 80];
    //         }
    //         if (!empty($searchemp)) {
    //             $whr[] = ['employ_status', 'like', '%' . trim($searchemp) . '%'];
    //         }
    //         if (!empty($searchgender)) {
    //             $whr[] = ['gender', 'like', '%' . trim($searchgender) . '%'];
    //         }
    //         if (!empty($searchaddress)) {
    //             $whr[] = ['address', 'like', '%' . trim($searchaddress) . '%'];
    //         }
    //         if (!empty($state)) {
    //             $whr[] = ['state', 'like', '%' . trim($state) . '%'];
    //         }
    //         if (!empty($senatorial_state)) {
    //             $whr[] = ['senatorial_state', 'like', '%' . trim($senatorial_state) . '%'];
    //         }
    //         if (!empty($federal_constituency)) {
    //             $whr[] = ['federal_constituency', 'like', '%' . trim($federal_constituency) . '%'];
    //         }
    //         if (!empty($local_constituency)) {
    //             $whr[] = ['local_constituency', 'like', '%' . trim($local_constituency) . '%'];
    //         }
    //         if (!empty($ward)) {
    //             $whr[] = ['ward', 'like', '%' . trim($ward) . '%'];
    //         }
    //         if (!empty($state_constituency)) {
    //             $whr[] = ['state_constituency', 'like', '%' . trim($state_constituency) . '%'];
    //         }
    //         if (!empty($polling_unit)) {
    //             $whr[] = ['polling_unit', 'like', '%' . trim($polling_unit) . '%'];
    //         }
    //     }

    //     $votersData = ImportVotersdata::where($whr)->orderByDesc('id')->get();

    //     return view('admin.managevotersdata.list', compact('stateList', 'senatorialList', 'federalList', 'localConList', 'wardList', 'staList', 'pollingList', 'getAllgender', 'getAllemp', 'votersData'));
    // }


    public function destroy($id)
    {
        $votersdata = ImportVotersdata::find($id); // Find the item by its ID
        if (!$votersdata) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $votersdata->delete(); // Delete the item

        return redirect()->route('managevotersdata.list')->with('message', 'VotersData deleted successfully.'); // Redirect to the index page with success message
    }


    public function getDistricts(Request $request)
    {
        $myresAry = [];

        if ($request->input('state') != '') {
            $state = State::where('state', $request->input('state'))->first();

            if ($state) {
                // Senatorial State
                $senatorialStates = Senatorial_state::where('state_id', $state->id)->get();
                $html1 = '<select name="senatorial_state" id="senatorial_state" data-rel="chosen" class="form-control">
                    <option value="">--Search by Senatorial State--</option>';
                if ($senatorialStates->count() > 0) {
                    foreach ($senatorialStates as $value) {
                        $html1 .= '<option value="' . $value->sena_district . '">' . $value->sena_district . '</option>';
                    }
                    $myresAry['status1'] = 1;
                } else {
                    $myresAry['status1'] = 2;
                }
                $html1 .= '</select>';
                $myresAry['html1'] = $html1;

                // Federal Constituency
                $federalConstituencies = Federal_constituency::where('state_id', $state->id)->get();
                $html2 = '<select name="federal_constituency" id="federal_constituency" data-rel="chosen" class="form-control">
                    <option value="">--Search by Federal Constituency--</option>';
                if ($federalConstituencies->count() > 0) {
                    foreach ($federalConstituencies as $value) {
                        $html2 .= '<option value="' . $value->federal_name . '">' . $value->federal_name . '</option>';
                    }
                    $myresAry['status2'] = 1;
                } else {
                    $myresAry['status2'] = 2;
                }
                $html2 .= '</select>';
                $myresAry['html2'] = $html2;

                // Local Constituency
                $localConstituencies = Local_constituency::where('state_id', $state->id)->get();
                $html3 = '<select name="local_constituency" id="local_constituency" data-rel="chosen" class="form-control">
                    <option value="">--Search by LGA--</option>';
                if ($localConstituencies->count() > 0) {
                    foreach ($localConstituencies as $value) {
                        $html3 .= '<option value="' . $value->lga . '">' . $value->lga . '</option>';
                    }
                    $myresAry['status3'] = 1;
                } else {
                    $myresAry['status3'] = 2;
                }
                $html3 .= '</select>';
                $myresAry['html3'] = $html3;

                // Ward
                $wards = Ward::where('state_id', $state->id)->get();
                $html4 = '<select name="ward" id="ward" data-rel="chosen" class="form-control">
                    <option value="">--Search by Ward--</option>';
                if ($wards->count() > 0) {
                    foreach ($wards as $value) {
                        $html4 .= '<option value="' . $value->ward_details . '">' . $value->ward_details . '</option>';
                    }
                    $myresAry['status4'] = 1;
                } else {
                    $myresAry['status4'] = 2;
                }
                $html4 .= '</select>';
                $myresAry['html4'] = $html4;

                // State Constituency
                $stateConstituencies = State_constituency::where('state_id', $state->id)->get();
                $html5 = '<select name="state_constituency" id="state_constituency" data-rel="chosen" class="form-control">
                    <option value="">--Search by State Constituency--</option>';
                if ($stateConstituencies->count() > 0) {
                    foreach ($stateConstituencies as $value) {
                        $html5 .= '<option value="' . $value->state_constituency . '">' . $value->state_constituency . '</option>';
                    }
                    $myresAry['status5'] = 1;
                } else {
                    $myresAry['status5'] = 2;
                }
                $html5 .= '</select>';
                $myresAry['html5'] = $html5;

                // Polling Unit
                $pollingUnits = Polling_unit::where('state_id', $state->id)->get();
                $html6 = '<select name="polling_unit" id="polling_unit" data-rel="chosen" class="form-control">
                    <option value="">--Search by Polling Unit--</option>';
                if ($pollingUnits->count() > 0) {
                    foreach ($pollingUnits as $value) {
                        $html6 .= '<option value="' . $value->polling_name . '">' . $value->polling_name . '</option>';
                    }
                    $myresAry['status6'] = 1;
                } else {
                    $myresAry['status6'] = 2;
                }
                $html6 .= '</select>';
                $myresAry['html6'] = $html6;

                $myresAry['status'] = 1;
            } else {
                $myresAry['status'] = 2;
            }
        } else {
            $myresAry['status'] = 2;
        }

        return response()->json($myresAry);
    }

    public function addImport(Request $request)
    {
        return view('admin.managevotersdata.importvotersdata');
    }
}
