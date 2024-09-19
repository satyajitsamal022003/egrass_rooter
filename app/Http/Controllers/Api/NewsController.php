<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News_category;
use App\Models\News_Social;
use App\Models\Newslatest_update;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    public function newsdetails(Request $request, $slug)
    {
        $newdata = News_Social::where('slug', $slug)->first();

        if (!$newdata) {
            return response()->json([
                'error' => 'News not found.'
            ], 404);
        }

        $newscategory = News_category::where('id', $newdata->newscategory)->first();

        if (!$newscategory) {
            return response()->json([
                'error' => 'News category not found.'
            ], 404);
        }

        $newsdetails = [
            'news_category' => $newscategory->name,
            'news_title' => $newdata->title,
            'news_description' => $newdata->description,
            'news_image' => $newdata->image ? asset('images/news/' . $newdata->image) : "",
            'youtube_video_url' => $newdata->video_url,
            'date' => $newdata->created_at->format('d-M-Y'),
            'slug' => $newdata->slug, // Added slug
            'created_by' => $newdata->created_by,
        ];

        $categories = News_category::withCount('news')->get();

        $categorycountdata = $categories->map(function ($category) {
            return [
                'category_id' => $category->id,
                'category_name' => $category->name,
                'news_count' => $category->news_count
            ];
        });

        $relatedPosts = News_Social::where('newscategory', $newdata->newscategory)
            ->where('slug', '!=', $slug)
            ->limit(5)
            ->get()
            ->map(function ($relatedPost) {
                return [
                    'news_id' => $relatedPost->id,
                    'news_title' => $relatedPost->title,
                    'news_description' => $relatedPost->description,
                    'news_image' => $relatedPost->image ? asset('images/news/' . $relatedPost->image) : null,
                    'date' => $relatedPost->created_at->format('d-M-Y'),
                    'slug' => $relatedPost->slug, // Added slug
                    'created_by' => $relatedPost->created_by,

                ];
            });

        $latestVideos = News_Social::whereNotNull('video_url')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->pluck('video_url');

        $popularArticles = News_Social::where('is_popular', 1)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($article) {
                return [
                    'article_id' => $article->id,
                    'title' => $article->title,
                    'image' => $article->image ? asset('images/news/' . $article->image) : null,
                    'date' => $article->created_at->format('d-M-Y'),
                    'slug' => $article->slug, // Added slug
                    'created_by' => $article->created_by,
                ];
            });

        return response()->json([
            'newsdetails' => $newsdetails,
            'categorycountdata' => $categorycountdata,
            'relatedPosts' => $relatedPosts,
            'latestVideos' => $latestVideos,
            'popularArticles' => $popularArticles
        ]);
    }


    public function latestupdateandnews(Request $request)
    {
        $messages = [
            'email_id.required' => 'Please Enter Your Email Id.',
            'email_id.email' => 'Please provide a valid email address.',
            'email_id.unique' => 'This email is already subscribed.'
        ];

        $validator = Validator::make($request->all(), [
            'email_id' => 'required|email|unique:newslatest_updates,email_id'
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 400);
        }

        $stayupdate = new Newslatest_update();
        $stayupdate->email_id = $request->input('email_id');

        if ($stayupdate->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Thank You, You Will Recieve All the latest updates & news.'
            ], 201);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to add email.'
            ], 500);
        }
    }

    public function news()
    {
        // Fetch news with active status and eager load the 'category' relationship
        $newsdata = News_Social::where('status', 1)
            ->with('category') // Eager loading the category
            ->get();

        // Map the news data for the slider, including category name and description
        $allslidenews = $newsdata->map(function ($news) {
            return [
                'news_id' => $news->id,
                'news_title' => $news->title,
                'news_description' => $news->description,
                'category_name' => $news->category ? $news->category->name : null, // Including category name
                'image' => $news->image ? asset('images/news/' . $news->image) : null,
                'slug' => $news->slug,
                'created_by' => $news->created_by,
            ];
        });

        // Fetch latest posts with active status, including their categories
        $latestposts = News_Social::where('status', 1)
            ->with('category') // Eager loading the category
            ->orderBy('created_at', 'desc')
            ->get();

        // Map the latest posts data, including category name and description
        $latestpostsdata = $latestposts->map(function ($latpost) {
            return [
                'news_id' => $latpost->id,
                'news_category' => $latpost->category ? $latpost->category->name : null, // Including category name
                'news_title' => $latpost->title,
                'news_description' => $latpost->description,
                'image' => $latpost->image ? asset('images/news/' . $latpost->image) : null,
                'date' => $latpost->created_at->format('d-M-Y'),
                'slug' => $latpost->slug,
                'created_by' => $latpost->created_by,
            ];
        });

        // Fetch latest videos with a limit of 3
        $latestVideos = News_Social::whereNotNull('video_url')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->pluck('video_url');

        // Fetch popular articles and map them, including category name and description
        $popularArticles = News_Social::where('is_popular', 1)
            ->with('category') // Eager loading the category
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($article) {
                return [
                    'article_id' => $article->id,
                    'title' => $article->title,
                    'category_name' => $article->category ? $article->category->name : null, // Including category name
                    'image' => $article->image ? asset('images/news/' . $article->image) : null,
                    'date' => $article->created_at->format('d-M-Y'),
                    'slug' => $article->slug,
                    'created_by' => $article->created_by,
                    'news_description' => $article->description,
                ];
            });

        // Fetch popular posts and map them, including category name and description
        $popularposts = News_Social::where('is_popular', 1)
            ->with('category') // Eager loading the category
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($article) {
                return [
                    'article_id' => $article->id,
                    'title' => $article->title,
                    'category_name' => $article->category ? $article->category->name : null, // Including category name
                    'image' => $article->image ? asset('images/news/' . $article->image) : null,
                    'date' => $article->created_at->format('d-M-Y'),
                    'slug' => $article->slug,
                    'created_by' => $article->created_by,
                    'news_description' => $article->description,
                ];
            });

        // Fetch categories and their news count
        $categories = News_category::withCount('news')->get();

        $categorycountdata = $categories->map(function ($category) {
            return [
                'category_id' => $category->id,
                'category_name' => $category->name,
                'news_count' => $category->news_count
            ];
        });

        // Return the response with all the collected data
        return response()->json([
            'allslidenews' => $allslidenews,
            'latestpostsdata' => $latestpostsdata,
            'latestVideos' => $latestVideos,
            'popularArticles' => $popularArticles,
            'categorycountdata' => $categorycountdata,
            'popularposts' => $popularposts
        ]);
    }
}
