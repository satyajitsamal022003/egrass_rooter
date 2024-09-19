<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Campaign_organization;

class ManagecampaignorganizationController extends Controller
{
    public function list(Request $request){

       $campaignorgslist = DB::table('campaign_organizations')
        ->join('parties','parties.id','=','campaign_organizations.political_party')
        ->select('campaign_organizations.*','parties.party_name as partyname')
        ->get();
           
            return view('admin.managecampaignorganization.list', compact('campaignorgslist'));
        
    }

    public function create(){
        return view('admin.managecampaignorganization.add');
    }

    public function edit($id){
        $editcamporg = Campaign_organization::find($id);
      return view('admin.managecampaignorganization.edit',compact('editcamporg'));
    }

    public function destroy($id)
    {
        $locconst = Campaign_organization::find($id); // Find the item by its ID
        if (!$locconst) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }
        $locconst->delete(); // Delete the item

         return redirect()->route('managecampaignorgs.list')->with('message', 'Campaign organization Deleted successfully !.'); // Redirect to the index page with success message
    }

    public function store(Request $request){
        $lga = new Campaign_organization();
        $lga->name = $request->input('campaignorganization');
        $lga->political_office = $request->input('political_office');
        $lga->political_party = $request->input('politicalparty');
        $lga->date_register = $request->input('dateregistered');
        $lga->created_at = now();
        $lga->updated_at = now(); 
        $lga->save();
        
        return redirect()->route('managecampaignorgs.list')->with('message', 'Campaign organization created Successfully !');
    }

    public function update(Request $request,$id){
        $lga = Campaign_organization::findOrFail($id);
        $lga->name = $request->input('campaignorganization');
        $lga->political_office = $request->input('political_office');
        $lga->political_party = $request->input('politicalparty');
        $lga->date_register = $request->input('dateregistered');
        $lga->updated_at = now(); 
        $lga->save();
        
        return redirect()->route('managecampaignorgs.list')->with('message', 'Campaign organization updated Successfully !');
    }
}
