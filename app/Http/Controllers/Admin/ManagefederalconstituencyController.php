<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Federal_constituency;

class ManagefederalconstituencyController extends Controller
{
    public function create(){
        return view('admin.managefederal_constituency.add');
    }

    public function store(Request $request){
        dd('stop');
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

    public function edit($id){
        $fedconst = Federal_constituency::find($id);
        dd('implement needed');
      return view('admin.managefederal_constituency.edit',compact('fedconst'));
    }
}
