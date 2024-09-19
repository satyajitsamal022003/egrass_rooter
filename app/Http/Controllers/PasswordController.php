<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PasswordController extends Controller
{
    public function showResetForm($userid)
    {
        return view('reset-password', ['userid' => $userid]);
    }
}
