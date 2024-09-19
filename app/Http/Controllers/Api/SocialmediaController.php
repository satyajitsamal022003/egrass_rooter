<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AddMember;
use App\Models\Campaign_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SocialmediaController extends Controller
{
    public function whatsappmessage()
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userid = $user->id;

        $whatsappRecord = Campaign_user::select('telephone')->where('id', $userid)->first();

        if ($whatsappRecord) {
            $whatsapp_number = $whatsappRecord->telephone;
        } else {
            $whatsapp_number = null;
        }

        return response()->json([
            'success' => true,
            'message' => 'Whatsapp Number of Campaign User Retrieved Successfully',
            'whatsapp_number' => $whatsapp_number,
        ]);
    }
}
