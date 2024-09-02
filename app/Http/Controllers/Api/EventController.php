<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\EventNotificationMail;
use App\Models\Blog;
use App\Models\Campaign_user;
use App\Models\Category;
use App\Models\EventWebsite;
use App\Models\ImportVotersdata;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EventController extends Controller
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
        $events = EventWebsite::where('user_id', $userid)
            ->orderBy('id', 'desc')
            ->get();
        // dd($blogs);

        $event_data = [];
        if (!$events->isEmpty()) {
            foreach ($events as $key => $event) {
                $event_data[$key]['id'] = $event->id;
                $event_data[$key]['title'] = isset($event->title) ? $event->title : "";
                $event_data[$key]['description'] = isset($event->description) ? $event->description : "";
                $event_data[$key]['event_image'] = isset($event->event_image) ? asset('images/eventwebsite/' . $event->event_image) : "";

                if ($event->is_active = 1) {
                    $event_data[$key]['is_active'] = 'Publish';
                } else {
                    $event_data[$key]['is_active'] = 'Unpublish';
                }
                $date = date("d-m-Y", strtotime($event->event_date));
                $event_data[$key]['event_date'] = isset($date) ? $date : null;
                $event_data[$key]['address'] = isset($event->address) ? $event->address : "";
                $event_data[$key]['created'] = isset($event->created) ? $event->created : null;
                $event_data[$key]['slug'] = isset($event->slug) ? $event->slug : "";
            }
        }

        return response()->json(['data' => $event_data], 200);
    }

    public function store(Request $request)
    {
        // Check if the user is authenticated
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Get the authenticated user's ID
        $userid = $user->id;

        // Validate the incoming request data
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string',
            'event_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'event_type' => 'required|string',
            'event_url' => 'required',
            'event_date' => 'required|date_format:m/d/y',
            'event_time' => 'required|string',
            'address' => 'required|string',
            'is_active' => 'required|boolean',
            'description' => 'required|string',
        ]);

        $eventDate = \DateTime::createFromFormat('m/d/y', $request->input('event_date'));
        if (!$eventDate) {
            return response()->json(['message' => 'Invalid date format. Expected format is mm/dd/yy'], 422);
        }
        $formattedEventDate = $eventDate->format('Y-m-d');

        // Create a new event
        $event = new EventWebsite();
        $event->user_id = $userid;
        $event->title = $request->input('title', '');
        $event->slug = $request->input('slug', '');
        $event->description = $request->input('description', '');
        $event->video_url = $request->input('video_url', '');
        $event->event_type = $request->input('event_type', '');
        $event->event_url = $request->input('event_url', '');
        $event->event_date = $formattedEventDate;  // Use formatted date
        $event->event_time = $request->input('event_time');
        $event->address = $request->input('address', '');
        $event->is_active = $request->input('is_active', '0');
        $event->created = now();
        $event->modified = now();

        // Handle file upload if provided
        if ($request->hasFile('event_image')) {
            $image = $request->file('event_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/eventwebsite'), $imageName);
            $event->event_image = $imageName;
        }

        // Get email addresses and names of contacts
        $contact_ids = ImportVotersdata::where('user_id', $userid)->get(['email', 'name']);
        // dd($contact_ids);
        $allemails = $contact_ids->pluck('email')->toArray();
        // dd($allemails);
        $allnames = $contact_ids->pluck('name')->toArray();

        // Save the event and handle success or failure
        if ($event->save()) {
            // Create a notification for the event
            $notificationData = [
                'user_id' => $userid,
                'notification_type' => 'Event',
                'status' => 0,
                'admin_status' => 2,
                'name' => $request->title,
                'desc' => $request->description,
                'date' => $formattedEventDate,
                'created' => now(),
                'modified' => now(),
                'member_id' => 0
            ];
            Notification::create($notificationData);

            // Send email notifications to contacts
            $emailImgpath = 'https://media.istockphoto.com/id/824227092/photo/youre-invited.jpg?s=612x612&w=0&k=20&c=TMdkqL_OVP2ebkiSNXu8Wpl5Nc3tK7nCwcjDsqJLhcE=';
            foreach ($allemails as $index => $mailid) {
                $subject = 'Hello, ' . $allnames[$index];
                Mail::to($mailid)->send(new EventNotificationMail(
                    $user->first_name,
                    $user->last_name,
                    date("M-d", strtotime($formattedEventDate)),
                    $emailImgpath
                ));
            }

            return response()->json(['message' => 'Event created successfully'], 201);
        } else {
            return response()->json(['message' => 'Failed to create event! Please try again'], 500);
        }
    }


    public function edit($id)
    {
        // Check if the user is authenticated
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Find the event by ID
        $event = EventWebsite::where('user_id', $user->id)->find($id);
        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found'
            ], 404);
        }

        // Prepare the blog data with category and image
        $event_data = [
            'title' => $event->title,
            'slug' => $event->slug,
            'description' => $event->description,
            'video_url' => $event->video_url,
            'event_type' => $event->event_type,
            'event_url' => $event->event_url,
            'event_date' => $event->event_date,
            'event_time' => $event->event_time,
            'address' => $event->address,
            'is_active' => $event->is_active,
            'trending' => $event->trending,
            'event_image' => $event->event_image ? asset('images/eventwebsite/' . $event->event_image) : asset('images/blog/noimage.jpg'),
        ];

        // Return the response with blog data
        return response()->json([
            'success' => true,
            'event_data' => $event_data
        ]);
    }


    public function update(Request $request)
    {
        // Check if the user is authenticated
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Validate the incoming request data
        $request->validate([
            'id' => 'required',
            'title' => 'required|string|max:255',
            'slug' => 'required|string',
            'event_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'event_type' => 'required|string',
            'event_url' => 'required',
            'event_date' => 'required|date_format:m/d/y',
            'event_time' => 'required|string',
            'address' => 'required|string',
            'is_active' => 'required|boolean',
            'description' => 'required|string',
        ]);

        $eventDate = \DateTime::createFromFormat('m/d/y', $request->input('event_date'));
        if (!$eventDate) {
            return response()->json(['message' => 'Invalid date format. Expected format is mm/dd/yy'], 422);
        }
        $formattedEventDate = $eventDate->format('Y-m-d');

        // Find the event by ID
        $event = EventWebsite::where('user_id', $user->id)->find($request->id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        // Update the event details
        $event->title = $request->input('title', '');
        $event->slug = $request->input('slug', '');
        $event->description = $request->input('description', '');
        $event->video_url = $request->input('video_url', '');
        $event->event_type = $request->input('event_type', '');
        $event->event_url = $request->input('event_url', '');
        $event->event_date = $formattedEventDate;  // Use formatted date
        $event->event_time = $request->input('event_time');
        $event->address = $request->input('address', '');
        $event->is_active = $request->input('is_active', '0');
        $event->modified = now();

        // Handle file upload if provided
        if ($request->hasFile('event_image')) {
            $image = $request->file('event_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/eventwebsite'), $imageName);
            $event->event_image = $imageName;
        }

        // Save the updated event and handle success or failure
        if ($event->save()) {
            return response()->json(['message' => 'Event updated successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to update event! Please try again'], 500);
        }
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $event = EventWebsite::find($request->id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        }

        // Delete the blog image from storage if it exists
        if ($event->event_image && file_exists(public_path('images/eventwebsite/' . $event->event_image))) {
            unlink(public_path('images/eventwebsite/' . $event->event_image));
        }

        // Delete the blog
        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully'
        ]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'is_active' => 'required|boolean',
        ]);

        $event = EventWebsite::find($request->id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        }

        $event->is_active = $request->input('is_active');
        $event->modified = now();
        $event->save();

        return response()->json([
            'success' => true,
            'message' => 'Event status updated successfully',
            'data' => $event
        ]);
    }
}
