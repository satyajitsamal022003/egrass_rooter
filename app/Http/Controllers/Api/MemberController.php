<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AddMember;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function index(Request $request, $userid)
    {
        // Enable query logging
        DB::enableQueryLog();
        $blogs = Blog::where('user_id', $userid)
            ->orderBy('id', 'desc')
            ->get();
        // dd($blogs);

        $blog_data = [];
        if (!$blogs->isEmpty()) {
            foreach ($blogs as $key => $blog) {
                $catDet = Category::where('id', $blog->cat_id)->first();
                $blog_data[$key]['id'] = $blog->id;
                $blog_data[$key]['category'] = isset($catDet->title) ? $catDet->title : "";
                $blog_data[$key]['title'] = isset($blog->title) ? $blog->title : "";
                $blog_data[$key]['description'] = isset($blog->description) ? $blog->description : "";
                $blog_data[$key]['slug'] = isset($blog->slug) ? $blog->slug : "";
                $blog_data[$key]['blog_image'] = isset($blog->blog_image) ? asset('images/blog/' . $blog->blog_image) : "";
                // $filePath = asset('/images/blog/' . $blog->blog_image);
                // if (file_exists($filePath) && $blog->blog_image != "") {
                //     $blog_data[$key]['blog_image'] = asset('/images/blog/' . $blog->blog_image);
                // } else {
                //     $blog_data[$key]['blog_image'] = asset('/images/blog/noimage.jpg');
                // }

                if ($blog->is_active = 1) {
                    $blog_data[$key]['is_active'] = 'Publish';
                } else {
                    $blog_data[$key]['is_active'] = 'Unpublish';
                }
                $blog_data[$key]['created'] = isset($blog->created) ? $blog->created : null;
                $blog_data[$key]['trending'] = isset($blog->trending) ? $blog->trending : 0;
            }
        }

        return response()->json(['data' => $blog_data], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:campaign_users,id',
            'role_type' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'email_id' => 'required|string|email|max:255|unique:add_members,email_id',
            'dob' => 'required|date',
            'voters_id_number' => 'required|string|max:255',
            'state' => 'required|exists:states,id',
            'senatorial' => 'required|string|max:255',
            'voting_local_govt' => 'required|string|max:255',
            'voting_registration_area' => 'required|string|max:255',
            'polling_unit' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'occupation' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'date_of_registration' => 'required|date',
            'address' => 'nullable|string|max:255',
            'political_party' => 'required|exists:parties,id',
            'is_active' => 'required|boolean',
        ]);

        $member = new AddMember();
        $member->user_id = $request->input('user_id');
        $member->role_type = $request->input('role_type');
        $member->name = $request->input('name');
        $member->gender = $request->input('gender');
        $member->phone_number = $request->input('phone_number');
        $member->email_id = $request->input('email_id');
        $member->dob = $request->input('dob');
        $member->voters_id_number = $request->input('voters_id_number');
        $member->state = $request->input('state');
        $member->senatorial = $request->input('senatorial');
        $member->voting_local_govt = $request->input('voting_local_govt');
        $member->voting_registration_area = $request->input('voting_registration_area');
        $member->polling_unit = $request->input('polling_unit');
        $member->code = $request->input('code');
        $member->occupation = $request->input('occupation');
        $member->latitude = $request->input('latitude');
        $member->longitude = $request->input('longitude');
        $member->date_of_registration = $request->input('date_of_registration');
        $member->address = $request->input('address');
        $member->political_party = $request->input('political_party');
        $member->is_active = $request->input('is_active');
        $member->created = now();
        $member->modified = now();

        $member->save();

        return response()->json(['message' => 'Member created successfully', 'data' => $member], 201);
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
