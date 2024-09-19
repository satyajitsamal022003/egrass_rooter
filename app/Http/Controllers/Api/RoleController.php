<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userid = $user->id;
        // Enable query logging
        DB::enableQueryLog();
        $roles = Role::orderBy('id', 'desc')->get();
        // dd($blogs);

        $role_data = [];
        if (!$roles->isEmpty()) {
            foreach ($roles as $key => $role) {
                $role_data[$key]['id'] = $role->id;
                $role_data[$key]['role'] = isset($role->role) ? $role->role : "";
                $date = date("d-m-Y", strtotime($role->created));
                $role_data[$key]['created'] = isset($date) ? $date : null;
                if ($role->is_active = 1) {
                    $role_data[$key]['is_active'] = 'Publish';
                } else {
                    $role_data[$key]['is_active'] = 'Unpublish';
                }
                // $role_data[$key]['created'] = isset($role->created) ? $role->created : null;
            }
        }

        return response()->json(['data' => $role_data], 200);
    }
}
