<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\EventWebsite;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userid = $user->id;
        $notifications = Notification::where('user_id', $userid)->where('admin_status', 2)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'message' => 'Notifications Retrieved Successfully',
            'count' => $notifications->count(),
            'notifications' => $notifications
        ]);
    }

    public function changeSingleNotifyStatus($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $notification->status = 1;
        $notification->save();

        $notificationType = DB::table('notifications')
            ->where('id', $id)
            ->value('notification_type');

        if ($notificationType == 'Survey') {
            return redirect()->to('broadcast/surveyresponse');
        }

        if ($notificationType == 'Event') {
            $userId = Auth::id();
            $eventDet = Notification::where('id', $id)
                ->where('user_id', $userId)
                ->first();

            if ($eventDet) {
                $getname = EventWebsite::where('title', $eventDet->name)
                    ->where('user_id', $userId)
                    ->first();

                if ($getname) {
                    return redirect()->to('broadcast/eventdetails/' . $getname->id);
                }
            }
        }

        return response()->json(['message' => 'Failed to change notification status'], 500);
    }

    public function changeNotifyStatusAdmin()
    {
        $affectedRows = DB::table('notifications')
            ->where('admin_status', 2)
            ->update(['admin_status' => 1]);

        if ($affectedRows) {
            return response()->json(['message' => 'Status updated successfully!'], 200);
        } else {
            return response()->json(['message' => 'Failed to update status. Please try again.'], 500);
        }
    }

}
