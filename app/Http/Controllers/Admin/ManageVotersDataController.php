<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddMember;
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
    private function formatPuCode($puCode)
    {
        // Remove any non-numeric characters
        $puCode = preg_replace('/\D/', '', $puCode);

        // Check if puCode has exactly 9 digits after removing non-numeric characters
        if (strlen($puCode) !== 9) {
            return false; // Return false if the format is invalid
        }

        // Format as 12-34-56-789
        return preg_replace('/^(\d{2})(\d{2})(\d{2})(\d{3})$/', '$1-$2-$3-$4', $puCode);
    }

    public function importVotersData(Request $request)
    {
        // Validate the file upload
        $request->validate([
            'upload_csv' => 'required|mimes:csv,txt|max:2048',
        ]);

        // Retrieve the file
        $file = $request->file('upload_csv');

        $errors = []; // Initialize an array to store error messages

        // Open the file
        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            // Skip the header row if there is one
            fgetcsv($handle);

            // Read the file line by line
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $fullName = trim($data[0] . ' ' . $data[1]);

                // Format the pu_code
                $formattedPuCode = $this->formatPuCode($data[3]);

                // Check if the pu_code format is invalid
                if ($formattedPuCode === false) {
                    $errors[] = "Invalid PU Code format for voter: $fullName. PU Code: {$data[3]}"; // Store the error message
                    continue; // Skip to the next record
                }

                // Check if the email already exists
                $existingMember = AddMember::where('email_id', $data[5])->exists();

                if ($existingMember) {
                    // If the email already exists, store an error message
                    $errors[] = "Duplicate email ID for voter: $fullName. Email: {$data[5]}";
                    continue; // Skip to the next record
                }

                // If the email doesn't exist and PU Code is valid, create a new record
                AddMember::create([
                    'name' => $fullName,
                    'dob' => $data[2],
                    'code' => $formattedPuCode,
                    'occupation' => $data[4],
                    'email_id' => $data[5],
                    'latitude' => $data[6],
                    'longitude' => $data[7],
                ]);
            }

            // Close the file
            fclose($handle);
        }

        // Check if there were any errors
        if (!empty($errors)) {
            // Return with error messages if any validation failed
            return back()->with('error', 'Some voters data could not be imported due to errors.')->withErrors($errors);
        }

        return back()->with('message', 'Voters data imported successfully.');
    }


    public function edit($id)
    {
        $editcontactdata = AddMember::find($id);
        return view('admin.managevotersdata.edit', compact('editcontactdata'));

    }

    public function update(Request $request, $id) {}


    public function list(Request $request)
    {
        $contactdatalist = AddMember::get();

        return view('admin.managevotersdata.list', compact('contactdatalist'));
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
