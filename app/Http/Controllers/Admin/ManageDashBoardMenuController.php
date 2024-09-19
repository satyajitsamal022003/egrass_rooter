<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dashboardmenu;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManageDashBoardMenuController extends Controller
{
    public function list()
    {
        $menus = $this->getDashboardMenuList();
        return view('admin.managedashboardmenu.list', compact('menus'));
    }

    private function getDashboardMenuList($parent = 0, $level = 0, &$menuList = [])
    {
        $menus = Dashboardmenu::where('select_parent', $parent)
            ->orderBy('order_no', 'asc')
            ->get();

        foreach ($menus as $menu) {
            $menu->level = $level;
            $menuList[] = $menu;
            $this->getDashboardMenuList($menu->id, $level + 1, $menuList);
        }

        return $menuList;
    }


    public function create()
    {
        $menuParents = $this->getMenuParents();
        return view('admin.managedashboardmenu.add', compact('menuParents'));
    }

    private function getMenuParents($editid = '', $parentid = 0, $level = 0, $selectval = 0)
    {
        $query = Dashboardmenu::where('select_parent', $parentid);

        if ($editid != '') {
            $query->where('id', '!=', $editid)
                ->where('select_parent', '!=', $editid);
        }

        $results = $query->orderBy('menu_name', 'asc')->get();

        $menuList = [];

        foreach ($results as $menuResult) {
            $menuList[] = [
                'id' => $menuResult->id,
                'name' => str_repeat('— ', $level) . stripslashes($menuResult->menu_name),
                'level' => $level,
            ];

            $menuList = array_merge($menuList, $this->getMenuParents($editid, $menuResult->id, $level + 1, $selectval));
        }

        return $menuList;
    }
    public function store(Request $request)
    {
        // Validate incoming request data
        // $validatedData = $request->validate([
        //     'menu_name' => 'required|string',
        //     'menu_type' => 'required|integer',
        //     'menu_link' => 'required|string',
        //     'select_parent' => 'required|integer',
        //     'menu_class' => 'nullable|string',
        //     'order_no' => 'required|integer',
        //     'status' => 'nullable|boolean',
        // ]);

        if (!Auth::check() || Auth::user()->id !== 1) {
            return redirect()->route('admin.dashboard')->with('error', 'You do not have permission to perform this action. Please contact with superadmin');
        }

        // Check if menu already exists
        $existingMenu = Dashboardmenu::where('menu_name', $request->menu_name)
            ->where('select_parent', $request->select_parent)
            ->exists();

        if ($existingMenu) {
            // return redirect('dashboardmenu/add')->with('error', 'This menu Title already exists');
            return redirect()->back()->with('error', 'This menu Title already exists');
            // return redirect()->route('managedashboardmenu.create')->with('error', 'This menu Title already exists');
        }

        // Create new Dashboardmenu instance and save
        $dashboardmenu = new Dashboardmenu();
        $dashboardmenu->menu_name = addslashes($request['menu_name']);
        $dashboardmenu->menu_type = $request['menu_type'];
        $dashboardmenu->menu_link = $request['menu_link'];
        $dashboardmenu->select_parent = $request['select_parent'];
        $dashboardmenu->menu_class = $request['menu_class'];
        $dashboardmenu->order_no = $request['order_no'];
        $dashboardmenu->status = $request['status'] ?? 0; // Default to 0 if status is not provided
        $dashboardmenu->created = now();
        $dashboardmenu->modified = now();
        $dashboardmenu->save();

        return redirect()->back()->with('message', 'Menu Created Successfully');
    }

    public function edit($id)
    {
        $editmanagemenu = Dashboardmenu::find($id);
        $menuParents = $this->getMenuParents($id, 0, 0, $editmanagemenu->select_parent);
        return view('admin.managedashboardmenu.edit', compact('editmanagemenu', 'menuParents'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::check() || Auth::user()->id !== 1) {
            return redirect()->route('admin.dashboard')->with('error', 'You do not have permission to perform this action. Please contact with superadmin');
        }

        // Check if menu already exists
        // $existingMenu = Dashboardmenu::where('menu_name', $request->menu_name)
        //     ->where('select_parent', $request->select_parent)
        //     ->exists();

        // if ($existingMenu) {
        //     return redirect()->back()->with('error', 'This menu Title already exists');
        // }
        $dashboardmenu = Dashboardmenu::findOrFail($id);
        $dashboardmenu->menu_name = addslashes($request['menu_name']);
        $dashboardmenu->menu_type = $request['menu_type'];
        $dashboardmenu->menu_link = $request['menu_link'];
        $dashboardmenu->select_parent = $request['select_parent'];
        $dashboardmenu->menu_class = $request['menu_class'];
        $dashboardmenu->order_no = $request['order_no'];
        $dashboardmenu->status = $request['status'] ?? 0; // Default to 0 if status is not provided
        $dashboardmenu->created = now();
        $dashboardmenu->modified = now();
        $dashboardmenu->save();

        return redirect()->back()->with('message', 'Menu Updated Successfully');
    }


    public function destroy($id)
    {
        $dashboardmenu = Dashboardmenu::find($id); // Find the item by its ID
        if (!$dashboardmenu) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $dashboardmenu->delete(); // Delete the item

        return redirect()->route('managedashboardmenu.list')->with('success', 'Dashboard Menu deleted successfully.'); // Redirect to the index page with success message
    }

    public function status(Request $request)
    {
        $get_id = $request->id;
        $catstatus = DB::table('dashboardmenu')
            ->select('status')
            ->where('id', '=', $get_id)
            ->first();


        $astatus = $catstatus->status;
        if ($astatus == '1') {
            $astatus = '0';
        } else {
            $astatus = '1';
        }
        $statusupdate = DB::table('dashboardmenu')
            ->where('id', $get_id)
            ->update(array('status' => $astatus));

        if ($statusupdate) {
            return response()->json([
                'status' => 'success',
                'code' => 200,
            ]);
        }
    }
}
