<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class ManageadminController extends Controller
{
    public function list(Request $request){
        $userlist = User::get(); 
            return view('admin.manageadmins.list', compact('userlist'));
    }

    public function create(){
        return view('admin.manageadmins.add');
    }

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'fullname' => 'required|string|max:255',
        'username' => 'required|string|unique:users,uname|max:255',
        'phone_no' => 'required|string|numeric',
        'email' => 'required|string|email|unique:users,email|max:255',
        'password' => 'required|string|min:8',
        'usertype' => 'required',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Create the user
    $user = new User();
    $user->name = $request->input('fullname');
    $user->uname = $request->input('username');
    $user->email = $request->input('email');
    $user->contact_no = $request->input('phone_no');
    $user->user_type = $request->input('usertype');
    $user->is_active = $request->input('status');
    $user->password = bcrypt($request->input('password')); // Make sure to hash the password
    $user->created_at = NOW();
    $user->updated_at = NOW();

    
    $user->save();

    return redirect()->route('manageadmins.list')->with('message', 'Admin-User added successfully!');
}


public function edit($id){
    $adminedit = User::find($id);
  return view('admin.manageadmins.edit',compact('adminedit'));
}


public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'fullname' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users,uname,'.$id,
        'phone_no' => 'required|string|numeric',
        'email' => 'required|string|email|max:255|unique:users,email,'.$id,
        'password' => 'nullable|string|min:8',
        'usertype' => 'required',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    // Find the user
    $user = User::findOrFail($id);
    $user->name = $request->input('fullname');
    $user->uname = $request->input('username');
    $user->email = $request->input('email');
    $user->contact_no = $request->input('phone_no');
    $user->user_type = $request->input('usertype');
    $user->is_active = $request->input('status');

    if ($request->has('password')) {
        $user->password = bcrypt($request->input('password')); // Make sure to hash the password
    }

    $user->updated_at = now();
    
    $user->save();

    return redirect()->route('manageadmins.list')->with('message', 'Admin-User updated successfully!');
}



}
