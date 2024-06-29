<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ManagemenuController extends Controller
{
    public function list(Request $request){
        $menulist = Menu::get(); 
            return view('admin.managemenu.list', compact('menulist'));
    }

    public function create(){
        return view('admin.managemenu.add');
    }

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'menu_name' => 'required',
        'menuposition' => 'required',
        'assign_type' => 'required|in:1,2,3',
        'menu_link1' => 'required_if:assign_type,1',
        'menulink2' => 'required_if:assign_type,2', 
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $clientadd = new Menu();
    $clientadd->menu_name = $request->input('menu_name');
    $clientadd->assign_type = $request->input('assign_type');
    $clientadd->menu_link =  $request->input('menulink2');
    $clientadd->select_page = $request->input('menu_link1');
    $clientadd->menu_position = $request->input('menuposition');
    $clientadd->select_parent = $request->input('parent');
    $clientadd->order_no = $request->input('orderno');
    $clientadd->is_active = $request->input('status');
    $clientadd->created_at = now();
    $clientadd->updated_at = now(); 
    $clientadd->save();

    return redirect()->route('managemenu.list')->with('message', 'Menu created Successfully !');
}

public function edit($id){
    $menuedit = Menu::find($id);
  return view('admin.managemenu.edit',compact('menuedit'));
}


public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'menu_name' => 'required',
        'menuposition' => 'required',
        'assign_type' => 'required|in:1,2,3',
        'menu_link1' => 'required_if:assign_type,1',
        'menulink2' => 'required_if:assign_type,2', 
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $clientupdate = Menu::find($id);
    if (!$clientupdate) {
        return redirect()->back()->with('error', 'Menu not found.');
    }

    $clientupdate->menu_name = $request->input('menu_name');
    $clientupdate->assign_type = $request->input('assign_type');
    $clientupdate->menu_link = $request->input('menulink2');
    $clientupdate->select_page = $request->input('menu_link1');
    $clientupdate->menu_position = $request->input('menuposition');
    $clientupdate->select_parent = $request->input('parent');
    $clientupdate->order_no = $request->input('orderno');
    $clientupdate->is_active = $request->input('status');
    $clientupdate->updated_at = now();
    $clientupdate->save();

    return redirect()->route('managemenu.list')->with('message', 'Menu updated Successfully!');
}


    public function status(Request $request){
        $get_id=$request->id;
        $catstatus=DB::table('menus')
        ->select('is_active')
        ->where('id','=',$get_id)
        ->first();
        

        $astatus=$catstatus->is_active;
         if($astatus == '1'){
             $astatus='0'; 
         } else{
             $astatus='1';
         }
         $statusupdate=DB::table('menus')
         ->where('id', $get_id)
         ->update(array('is_active'=>$astatus));

         if($statusupdate){
             return response()->json([
                 'status' => 'success',
                 'code' => 200,
             ]);
            }
        }

        public function destroy($id)
        {
            $locconst = Menu::find($id); // Find the item by its ID
            if (!$locconst) {
                return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
            }
    
            $locconst->delete(); // Delete the item
    
             return redirect()->route('managemenu.list')->with('message', 'Menu Removed successfully !.'); // Redirect to the index page with success message
        }
}
