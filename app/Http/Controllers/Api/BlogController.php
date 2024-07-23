<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        // Enable query logging
        DB::enableQueryLog();
        $blogs = Blog::get();
        // dd($blogs);

        $blog_data = [];
        if (!$blogs->isEmpty()) {
            foreach ($blogs as $key => $blog) {
                $blog_data[$key]['id'] = $blog->id;
                $blog_data[$key]['title'] = isset($blog->title) ? $blog->title : "";
                $blog_data[$key]['slug'] = isset($blog->slug) ? $blog->slug : "";
                $blog_data[$key]['description'] = isset($blog->description) ? $blog->description : "";
                $blog_data[$key]['blog_image'] = isset($blog->blog_image) ? $blog->blog_image : "";
                $blog_data[$key]['is_active'] = isset($blog->is_active) ? $blog->is_active : 0;
                $blog_data[$key]['trending'] = isset($blog->trending) ? $blog->trending : 0;
                $blog_data[$key]['author_name'] = isset($blog->author_name) ? $blog->author_name : "";
                $blog_data[$key]['created'] = isset($blog->created) ? $blog->created : null;
                $blog_data[$key]['modified'] = isset($blog->modified) ? $blog->modified : null;
                $blog_data[$key]['user_id'] = isset($blog->user_id) ? $blog->user_id : 0;
                $blog_data[$key]['cat_slug'] = isset($blog->cat_slug) ? $blog->cat_slug : "";
            }
        }

        return response()->json(['data' => $blog_data], 200);
    }
}
