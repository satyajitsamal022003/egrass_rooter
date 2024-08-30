<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Blog;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
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
            'cat_id' => 'required|exists:category,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'slug' => 'required|string|unique:blogs,slug',
            'blog_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'required|boolean',
        ]);

        $blog = new Blog();
        $blog->user_id = $request->input('user_id');
        $blog->cat_id = $request->input('cat_id');
        $blog->title = $request->input('title');
        $blog->slug = $request->input('slug');
        $blog->description = $request->input('description');
        $blog->is_active = $request->input('is_active');
        $blog->trending = $request->input('trending', 0);
        $blog->created = now();
        $blog->modified = now();

        if ($request->hasFile('blog_image')) {
            $image = $request->file('blog_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/blog'), $imageName);
            $blog->blog_image = $imageName;
        }

        $blog->save();

        return response()->json(['message' => 'Blog created successfully', 'data' => $blog], 201);
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


    // public function getBlogsData(Request $request)
    // {
    //     // Enable query logging
    //     DB::enableQueryLog();

    //     $blogs = Blog::orderBy('id', 'desc')->get();

    //     $blog_data = [];
    //     $category_blog_count = [];
    //     if (!$blogs->isEmpty()) {
    //         foreach ($blogs as $key => $blog) {
    //             $blog_data[$key]['id'] = $blog->id;
    //             $blog_data[$key]['title'] = $blog->title ?? "";
    //             $blog_data[$key]['description'] = $blog->description ?? "";
    //             $blog_data[$key]['slug'] = $blog->slug ?? "";
    //             $blog_data[$key]['blog_image'] = $blog->blog_image ? asset('images/blog/' . $blog->blog_image) : "";
    //             $blog_data[$key]['created'] = $blog->created ?? 'N/A';
    //             $blog_data[$key]['author_name'] = $blog->author_name ?? 'N/A';

    //             $cat_id = $blog->cat_id;
    //             if (isset($category_blog_count[$cat_id])) {
    //                 $category_blog_count[$cat_id]++;
    //             } else {
    //                 $category_blog_count[$cat_id] = 1;
    //             }
    //         }
    //     }

    //     $blogs_category = Category::orderBy('id', 'desc')->get();
    //     $blogs_category_data = [];
    //     if (!$blogs_category->isEmpty()) {
    //         foreach ($blogs_category as $key => $category) {
    //             $blogs_category_data[$key]['id'] = $category->id;
    //             $blogs_category_data[$key]['title'] = $category->title ?? "";
    //             $blogs_category_data[$key]['slug'] = $category->slug ?? "";
    //             $blogs_category_data[$key]['created'] = $category->created_at ?? 'N/A';
    //             $blogs_category_data[$key]['blog_count'] = $category_blog_count[$category->id] ?? 0;
    //         }
    //     }

    //     $latest_blogs = Blog::orderBy('created', 'desc')->take(5)->get();
    //     $latest_blog_data = [];
    //     if (!$latest_blogs->isEmpty()) {
    //         foreach ($latest_blogs as $key => $blog) {
    //             $latest_blog_data[$key]['id'] = $blog->id;
    //             $latest_blog_data[$key]['title'] = $blog->title ?? "";
    //             $latest_blog_data[$key]['description'] = $blog->description ?? "";
    //             $latest_blog_data[$key]['slug'] = $blog->slug ?? "";
    //             $latest_blog_data[$key]['blog_image'] = $blog->blog_image ? asset('images/blog/' . $blog->blog_image) : "";
    //             $latest_blog_data[$key]['created'] = $blog->created ?? 'N/A';
    //             $latest_blog_data[$key]['author_name'] = $blog->author_name ?? 'N/A';
    //         }
    //     }

    //     $popular_blogs = Blog::where('trending', 1)->take(5)->get();
    //     $popular_blog_data = [];
    //     if (!$popular_blogs->isEmpty()) {
    //         foreach ($popular_blogs as $key => $blog) {
    //             $popular_blog_data[$key]['id'] = $blog->id;
    //             $popular_blog_data[$key]['title'] = $blog->title ?? "";
    //             $popular_blog_data[$key]['description'] = $blog->description ?? "";
    //             $popular_blog_data[$key]['slug'] = $blog->slug ?? "";
    //             $popular_blog_data[$key]['blog_image'] = $blog->blog_image ? asset('images/blog/' . $blog->blog_image) : "";
    //             $popular_blog_data[$key]['created'] = $blog->created ?? 'N/A';
    //             $popular_blog_data[$key]['author_name'] = $blog->author_name ?? 'N/A';
    //         }
    //     }
 

    //     $recent_blogs = Blog::where('created', '>=', now()->subDays(7))->orderBy('created', 'desc')->get();
    //     $recent_blog_data = [];
    //     if (!$recent_blogs->isEmpty()) {
    //         foreach ($recent_blogs as $key => $blog) {
    //             $recent_blog_data[$key]['id'] = $blog->id;
    //             $recent_blog_data[$key]['title'] = $blog->title ?? "";
    //             $recent_blog_data[$key]['description'] = $blog->description ?? "";
    //             $recent_blog_data[$key]['slug'] = $blog->slug ?? "";
    //             $recent_blog_data[$key]['blog_image'] = $blog->blog_image ? asset('images/blog/' . $blog->blog_image) : "";
    //             $recent_blog_data[$key]['created'] = $blog->created ?? 'N/A';
    //             $recent_blog_data[$key]['author_name'] = $blog->author_name ?? 'N/A';
    //         }
    //     }


    //     return response()->json([
    //         'blogs' => $blog_data,
    //         'categories' => $blogs_category_data,
    //         'latest_posts' => $latest_blog_data,
    //         'popular_posts' => $popular_blog_data,
    //         'recent_posts' => $recent_blog_data,
    //     ], 200);
    // }

    public function getAllBlogs()
    {
        $blogs = Blog::orderBy('id', 'desc')->get();
        $blog_data = [];

        foreach ($blogs as $key => $blog) {
            $catDet = Category::where('id', $blog->cat_id)->first();
            $blog_data[$key] = [
                'id' => $blog->id,
                'title' => $blog->title ?? "",
                'description' => $blog->description ?? "",
                'slug' => $blog->slug ?? "",
                'blog_image' => $blog->blog_image ? asset('images/blog/' . $blog->blog_image) : "",
                'created' => Carbon::parse($blog->created)->format('F d, Y'),
                'author_name' => $blog->author_name ?? 'N/A',
                'category' => $catDet->title ?? 'N/A',
            ];
        }

        return response()->json($blog_data, 200);
    }

    public function getBlogCategories()
    {
        $blogs = Blog::orderBy('id', 'desc')->get();
        $category_blog_count = [];

        foreach ($blogs as $blog) {
            $cat_id = $blog->cat_id;
            $category_blog_count[$cat_id] = ($category_blog_count[$cat_id] ?? 0) + 1;
        }

        $categories = Category::orderBy('id', 'desc')->get();
        $blogs_category_data = [];

        foreach ($categories as $key => $category) {
            $blogs_category_data[$key] = [
                'id' => $category->id,
                'title' => $category->title ?? "",
                'slug' => $category->slug ?? "",
                'created' => Carbon::parse($category->created_at)->format('F d, Y'),
                'blog_count' => $category_blog_count[$category->id] ?? 0,
            ];
        }

        return response()->json($blogs_category_data, 200);
    }

    public function getLatestBlogs()
    {
        $latest_blogs = Blog::orderBy('created', 'desc')->take(5)->get();
        $latest_blog_data = [];

        foreach ($latest_blogs as $key => $blog) {
            $catDet = Category::where('id', $blog->cat_id)->first();
            $latest_blog_data[$key] = [
                'id' => $blog->id,
                'title' => $blog->title ?? "",
                'description' => $blog->description ?? "",
                'slug' => $blog->slug ?? "",
                'blog_image' => $blog->blog_image ? asset('images/blog/' . $blog->blog_image) : "",
                'created' => Carbon::parse($blog->created)->format('F d, Y'),
                'author_name' => $blog->author_name ?? 'N/A',
                'category' => $catDet->title ?? 'N/A',
            ];
        }

        return response()->json($latest_blog_data, 200);
    }

    public function getPopularBlogs()
    {
        $popular_blogs = Blog::where('trending', 1)->take(5)->get();
        $popular_blog_data = [];

        foreach ($popular_blogs as $key => $blog) {
            $catDet = Category::where('id', $blog->cat_id)->first();
            $popular_blog_data[$key] = [
                'id' => $blog->id,
                'title' => $blog->title ?? "",
                'description' => $blog->description ?? "",
                'slug' => $blog->slug ?? "",
                'blog_image' => $blog->blog_image ? asset('images/blog/' . $blog->blog_image) : "",
                'created' => Carbon::parse($blog->created)->format('F d, Y'),
                'author_name' => $blog->author_name ?? 'N/A',
                'category' => $catDet->title ?? 'N/A',
            ];
        }

        return response()->json($popular_blog_data, 200);
    }

    public function getRecentBlogs()
    {
        $recent_blogs = Blog::where('created', '>=', now()->subDays(7))->orderBy('created', 'desc')->get();
        $recent_blog_data = [];

        foreach ($recent_blogs as $key => $blog) {
            $catDet = Category::where('id', $blog->cat_id)->first();
            $recent_blog_data[$key] = [
                'id' => $blog->id,
                'title' => $blog->title ?? "",
                'description' => $blog->description ?? "",
                'slug' => $blog->slug ?? "",
                'blog_image' => $blog->blog_image ? asset('images/blog/' . $blog->blog_image) : "",
                'created' => Carbon::parse($blog->created)->format('F d, Y'),
                'author_name' => $blog->author_name ?? 'N/A',
                'category' => $catDet->title ?? 'N/A',
            ];
        }

        return response()->json($recent_blog_data, 200);
    }

    public function getRelatedBlogs($id)
    {
        $currentBlog = Blog::find($id);

        if (!$currentBlog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        $categoryId = $currentBlog->cat_id;

        $relatedBlogs = Blog::where('cat_id', $categoryId)
            ->where('id', '<>', $id)
            ->orderBy('created', 'desc')
            ->take(5)
            ->get();

        $related_blog_data = [];

        foreach ($relatedBlogs as $key => $blog) {
            $catDet = Category::where('id', $blog->cat_id)->first();
            $related_blog_data[$key] = [
                'id' => $blog->id,
                'title' => $blog->title ?? "",
                'description' => $blog->description ?? "",
                'slug' => $blog->slug ?? "",
                'blog_image' => $blog->blog_image ? asset('images/blog/' . $blog->blog_image) : "",
                'created' => Carbon::parse($blog->created)->format('F d, Y'),
                'author_name' => $blog->author_name ?? 'N/A',
                'category' => $catDet->title ?? 'N/A',
            ];
        }

        return response()->json($related_blog_data, 200);
    }

    // public function getBlogDetails($id)
    // {
    //     $blog = Blog::find($id);

    //     if (!$blog) {
    //         return response()->json(['message' => 'Blog not found'], 404);
    //     }

    //     $catDet = Category::where('id', $blog->cat_id)->first();
    //     $blog_data = [
    //         'id' => $blog->id,
    //         'title' => $blog->title ?? "",
    //         'description' => $blog->description ?? "",
    //         'slug' => $blog->slug ?? "",
    //         'blog_image' => $blog->blog_image ? asset('images/blog/' . $blog->blog_image) : "",
    //         'created' => Carbon::parse($blog->created)->format('F d, Y'),
    //         'author_name' => $blog->author_name ?? 'N/A',
    //         'category' => $catDet->title ?? 'N/A',
    //     ];

    //     $categoryId = $blog->cat_id;
    //     $relatedBlogs = Blog::where('cat_id', $categoryId)
    //         ->where('id', '<>', $id)
    //         ->orderBy('created', 'desc')
    //         ->take(5)
    //         ->get();

    //     $related_blog_data = [];
    //     foreach ($relatedBlogs as $key => $relatedBlog) {
    //         $catDet = Category::where('id', $blog->cat_id)->first();
    //         $related_blog_data[$key] = [
    //             'id' => $relatedBlog->id,
    //             'title' => $relatedBlog->title ?? "",
    //             'description' => $relatedBlog->description ?? "",
    //             'slug' => $relatedBlog->slug ?? "",
    //             'blog_image' => $relatedBlog->blog_image ? asset('images/blog/' . $relatedBlog->blog_image) : "",
    //             'created' => Carbon::parse($relatedBlog->created)->format('F d, Y'),
    //             'author_name' => $relatedBlog->author_name ?? 'N/A',
    //             'category' => $catDet->title ?? 'N/A',
    //         ];
    //     }

    //     return response()->json([
    //         'blog' => $blog_data,
    //         'related_blogs' => $related_blog_data,
    //     ], 200);
    // }

    public function getBlogDetails($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        $catDet = Category::where('id', $blog->cat_id)->first();

        $blog_data = [
            'id' => $blog->id,
            'title' => $blog->title ?? "",
            'description' => $blog->description ?? "",
            'slug' => $blog->slug ?? "",
            'blog_image' => $blog->blog_image ? asset('images/blog/' . $blog->blog_image) : "",
            'created' => Carbon::parse($blog->created)->format('F d, Y'),
            'author_name' => $blog->author_name ?? 'N/A',
            'category' => $catDet->title ?? 'N/A',
        ];

        $relatedBlogs = Blog::where('cat_id', $blog->cat_id)
            ->where('id', '<>', $id)
            ->orderBy('created', 'desc')
            ->take(5)
            ->get();

        $related_blog_data = [];
        foreach ($relatedBlogs as $key => $relatedBlog) {
            $catDet = Category::where('id', $relatedBlog->cat_id)->first();
            $related_blog_data[$key] = [
                'id' => $relatedBlog->id,
                'title' => $relatedBlog->title ?? "",
                'description' => $relatedBlog->description ?? "",
                'slug' => $relatedBlog->slug ?? "",
                'blog_image' => $relatedBlog->blog_image ? asset('images/blog/' . $relatedBlog->blog_image) : "",
                'created' => Carbon::parse($relatedBlog->created)->format('F d, Y'),
                'author_name' => $relatedBlog->author_name ?? 'N/A',
                'category' => $catDet->title ?? 'N/A',
            ];
        }

        $previousBlog = Blog::where('created', '<', $blog->created)
            ->orderBy('created', 'desc')
            ->first();

        $nextBlog = Blog::where('created', '>', $blog->created)
            ->orderBy('created', 'asc')
            ->first();

        $previous_blog_data = $previousBlog ? [
            'id' => $previousBlog->id,
            'title' => $previousBlog->title ?? "",
            'slug' => $previousBlog->slug ?? "",
        ] : null;

        $next_blog_data = $nextBlog ? [
            'id' => $nextBlog->id,
            'title' => $nextBlog->title ?? "",
            'slug' => $nextBlog->slug ?? "",
        ] : null;

        return response()->json([
            'blog' => $blog_data,
            'related_blogs' => $related_blog_data,
            'previous_blog' => $previous_blog_data,
            'next_blog' => $next_blog_data,
        ], 200);
    }
}
