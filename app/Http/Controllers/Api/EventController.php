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
    public function index(Request $request, $userid)
    {
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
        dd($contact_ids);
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
        // Find the blog by its ID
        $blog = Blog::find($id);

        // Check if the blog exists
        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found'
            ], 404);
        }

        // Retrieve the category details
        $category = Category::find($blog->cat_id);

        // Prepare the blog data with category and image
        $blog_data = [
            'cat_id' => $blog->cat_id,
            'category' => $category ? $category->title : 'Category not found',
            'title' => $blog->title,
            'slug' => $blog->slug,
            'description' => $blog->description,
            'is_active' => $blog->is_active,
            'trending' => $blog->trending,
            'blog_image' => $blog->blog_image ? asset('images/blog/' . $blog->blog_image) : asset('images/blog/noimage.jpg'),
        ];

        // Return the response with blog data
        return response()->json([
            'success' => true,
            'blog_data' => $blog_data
        ]);
    }


    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'cat_id' => 'required|exists:category,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'slug' => 'required|string',
            'blog_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'required|boolean',
            'trending' => 'nullable|boolean',
        ]);

        $blog = Blog::find($request->id);

        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        $blog->cat_id = $request->input('cat_id');
        $blog->title = $request->input('title');
        $blog->description = $request->input('description');
        $blog->slug = $request->input('slug');
        $blog->is_active = $request->input('is_active');
        $blog->trending = $request->input('trending', 0);
        // $blog->created = now();
        $blog->modified = now();

        if ($request->hasFile('blog_image')) {
            $image = $request->file('blog_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/blog'), $imageName);
            $blog->blog_image = $imageName;
        }

        $blog->save();

        return response()->json(['message' => 'Blog updated successfully', 'data' => $blog], 200);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $blog = Blog::find($request->id);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found'
            ], 404);
        }

        // Delete the blog image from storage if it exists
        if ($blog->blog_image && file_exists(public_path('images/blog/' . $blog->blog_image))) {
            unlink(public_path('images/blog/' . $blog->blog_image));
        }

        // Delete the blog
        $blog->delete();

        return response()->json([
            'success' => true,
            'message' => 'Blog deleted successfully'
        ]);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'is_active' => 'required|boolean',
        ]);

        $blog = Blog::find($request->id);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found'
            ], 404);
        }

        $blog->is_active = $request->input('is_active');
        $blog->modified = now();
        $blog->save();

        return response()->json([
            'success' => true,
            'message' => 'Blog status updated successfully',
            'data' => $blog
        ]);
    }

    public function saveBlogcategory(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:campaign_users,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:blogs,slug',
            'category_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'required|string',
            'is_active' => 'required|boolean',
        ]);

        $blog = new Category();
        $blog->user_id = $request->input('user_id');
        $blog->title = $request->input('title');
        $blog->slug = $request->input('slug');
        $blog->description = $request->input('description');
        $blog->is_active = $request->input('is_active');
        $blog->created = now();
        $blog->modified = now();

        if ($request->hasFile('category_image')) {
            $image = $request->file('category_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/blog'), $imageName);
            $blog->category_image = $imageName;
        }

        $blog->save();

        return response()->json(['message' => 'Blog Category created successfully', 'data' => $blog], 201);
    }
}
