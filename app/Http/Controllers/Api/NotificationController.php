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
    public function index(Request $request, $userid)
    {
        // Fetch notifications with admin_status=2
        $notifications = Notification::where('user_id', $userid)->where('admin_status', 2)
            ->orderBy('id', 'desc')
            ->get();

        // Return notifications as JSON
        return response()->json([
            'count' => $notifications->count(),
            'notifications' => $notifications
        ]);
    }

    public function changeSingleNotifyStatus($id)
    {
        // Validate the ID
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        // Update the notification status
        $notification->status = 1;
        $notification->save();

        // Retrieve the notification type
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
        // Update the notifications with admin_status = 2 to admin_status = 1
        $affectedRows = DB::table('notifications')
            ->where('admin_status', 2)
            ->update(['admin_status' => 1]);

        if ($affectedRows) {
            // Success response
            return response()->json(['message' => 'Status updated successfully!'], 200);
        } else {
            // Failure response
            return response()->json(['message' => 'Failed to update status. Please try again.'], 500);
        }
    }
}
