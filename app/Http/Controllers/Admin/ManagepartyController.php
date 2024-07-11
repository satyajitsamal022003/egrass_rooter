<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Party;
use Illuminate\Support\Facades\DB;

class ManagepartyController extends Controller
{
    public function create()
    {
        return view('admin.manageparty.add');
    }

    public function list(Request $request)
    {

        $searchtxt = $request->input('searchtxt');

        // Check if search term is present
        if ($searchtxt) {
            $partylist = Party::where('party_name', 'like', '%' . $searchtxt . '%')->get();
        } else {
            $partylist = Party::all();
        }

        // Check if the request is an Ajax request
        if ($request->ajax()) {
            return view('admin.manageparty.filter', compact('partylist'));
        } else {
            return view('admin.manageparty.list', compact('partylist'));
        }
    }


    public function store(Request $request)
    {
        $partyadd = new Party();
        $partyadd->party_name = $request->input('party_name');
        $partyadd->owner_name = $request->input('chairman_name');
        $partyadd->color = $request->input('color');
        $partyadd->is_active = $request->input('status');
        $partyadd->created_at = now();
        $partyadd->updated_at = now();

        if ($request->hasFile('party_image')) {
            // Generate a unique file name for the image
            $partyimg = 'party_' . time() . '.' . $request->file('party_image')->getClientOriginalExtension();

            $destinationpartyimg = public_path('images/parties');

            if (!is_dir($destinationpartyimg)) {
                mkdir($destinationpartyimg, 0777, true);
            }

            // Move the file to the public/uploads directory
            $request->file('party_image')->move($destinationpartyimg, $partyimg);

            $partyadd->party_img = $partyimg;
        }

        if ($request->hasFile('candidate_img')) {
            // Generate a unique file name for the image
            $candidate_img = 'candidate_' . time() . '.' . $request->file('candidate_img')->getClientOriginalExtension();

            $destinationcandidateimg = public_path('images/parties');

            if (!is_dir($destinationcandidateimg)) {
                mkdir($destinationcandidateimg, 0777, true);
            }

            // Move the file to the public/uploads directory
            $request->file('candidate_img')->move($destinationcandidateimg, $candidate_img);

            $partyadd->candidate_img = $candidate_img;
        }
        $partyadd->save();

        return redirect()->route('manageparty.list')->with('message', 'Party Added Successfully!');
    }

    public function edit($id)
    {
        $editparty = Party::find($id);
        return view('admin.manageparty.edit', compact('editparty'));
    }

    public function update(Request $request, $id)
    {
        $partyadd = Party::findOrFail($id);
        $partyadd->party_name = $request->input('party_name');
        $partyadd->owner_name = $request->input('chairman_name');
        $partyadd->color = $request->input('color');
        $partyadd->is_active = $request->input('status');
        $partyadd->updated_at = now();

        if ($request->hasFile('party_image')) {
            // Generate a unique file name for the image
            $partyimg = 'party_' . time() . '.' . $request->file('party_image')->getClientOriginalExtension();
            $destinationpartyimg = public_path('images/parties');

            if (!is_dir($destinationpartyimg)) {
                mkdir($destinationpartyimg, 0777, true);
            }

            // Move the file to the public/uploads directory
            $request->file('party_image')->move($destinationpartyimg, $partyimg);

            // Check if an existing image file is set and delete it
            if (!empty($partyadd->party_img)) {
                $existingimagepath = $destinationpartyimg . '/' . $partyadd->party_img;
                if (file_exists($existingimagepath) && is_file($existingimagepath)) {
                    unlink($existingimagepath);
                }
            }

            $partyadd->party_img = $partyimg;
        }

        if ($request->hasFile('candidate_img')) {
            // Generate a unique file name for the image
            $candidate_img = 'candidate_' . time() . '.' . $request->file('candidate_img')->getClientOriginalExtension();
            $destinationcandidateimg = public_path('images/parties');

            if (!is_dir($destinationcandidateimg)) {
                mkdir($destinationcandidateimg, 0777, true);
            }

            // Move the file to the public/uploads directory
            $request->file('candidate_img')->move($destinationcandidateimg, $candidate_img);

            // Check if an existing image file is set and delete it
            if (!empty($partyadd->candidate_img)) {
                $existingcandidate = $destinationcandidateimg . '/' . $partyadd->candidate_img;
                if (file_exists($existingcandidate) && is_file($existingcandidate)) {
                    unlink($existingcandidate);
                }
            }

            $partyadd->candidate_img = $candidate_img;
        }

        $partyadd->save();

        return redirect()->route('manageparty.list')->with('message', 'Party Updated Successfully!');
    }



    public function destroy($id)
    {
        $party = Party::find($id); // Find the item by its ID
        if (!$party) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $party->delete(); // Delete the item

        return redirect()->route('manageparty.list')->with('message', 'Party Removed successfully !.'); // Redirect to the index page with success message
    }


    public function status(Request $request)
    {
        $get_id = $request->id;
        $catstatus = DB::table('parties')
            ->select('is_active')
            ->where('id', '=', $get_id)
            ->first();


        $astatus = $catstatus->is_active;
        if ($astatus == '1') {
            $astatus = '0';
        } else {
            $astatus = '1';
        }
        $statusupdate = DB::table('parties')
            ->where('id', $get_id)
            ->update(array('is_active' => $astatus));

        if ($statusupdate) {
            return response()->json([
                'status' => 'success',
                'code' => 200,
            ]);
        }
    }

    public function PartyImport(Request $request)
    {

        return view('admin.manageparty.party-import');
    }

    public function importParty(Request $request)
    {
        if ($request->hasFile('upload_csv')) {
            $file = $request->file('upload_csv');
            $path = $file->getRealPath();

            if (($handle = fopen($path, 'r')) !== FALSE) {
                $row = 1;
                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    if ($row > 1) {
                        $party = new Party();
                        $party->party_name = !empty($data[0]) ? $data[0] : '';
                        $party->owner_name = !empty($data[1]) ? $data[1] : '';
                        $party->color = !empty($data[2]) ? $data[2] : '';
                        $party->is_active = isset($data[3]) ? (int)$data[3] : 1;
                        $party->created_at = now();

                        // Debug: Log data
                        \Log::info('CSV Data: ' . json_encode($data));
                        \Log::info('Party Data: ' . json_encode($party));

                        $party->save();
                    }
                    $row++;
                }
                fclose($handle);
                // return redirect()->route('manageparty.list')->with('success', 'CSV Data Imported into the Database');
                return redirect()->route('manageparty.addImport')->with('message', 'CSV Data Imported into the Database');
                
            }
        }
        return redirect()->route('manageparty.addImport')->with('error', 'Problem in Importing CSV Data');
    }
}
