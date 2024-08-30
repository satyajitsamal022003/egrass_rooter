<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News_category;
use App\Models\News_Social;
use App\Models\Newslatest_update;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function newsdetails(Request $request, $newsid)
    {
        $newdata = News_Social::where('id', $newsid)->first();

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
            ->where('id', '!=', $newsid)
            ->limit(5)
            ->get();

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
                    // 'description' => $article->description,
                    'image' => $article->image ? asset('images/news/' . $article->image) : null,
                    'date' => $article->created_at->format('d-M-Y'),
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
        $newsdata = News_Social::where('status', 1)->get();

        $allslidenews = $newsdata->map(function ($news) {
            return [
                'news_id' => $news->id,
                'news_title' => $news->title,
                'news_description' => $news->description
            ];
        });

        $latestposts = News_Social::where('status', 1)
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->get();

        $latestpostsdata = $latestposts->map(function ($latpost) {
            return [
                'news_id' => $latpost->id,
                'news_category' => $latpost->category['name'],
                'news_title' => $latpost->title,
                'news_description' => $latpost->description,
                'date' => $latpost->created_at->format('d-M-Y'),
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
                    // 'description' => $article->description,
                    'image' => $article->image ? asset('images/news/' . $article->image) : null,
                    'date' => $article->created_at->format('d-M-Y'),
                ];
            });

            $popularposts = News_Social::where('is_popular', 1)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($article) {
                return [
                    'article_id' => $article->id,
                    'title' => $article->title,
                    // 'description' => $article->description,
                    'image' => $article->image ? asset('images/news/' . $article->image) : null,
                    'date' => $article->created_at->format('d-M-Y'),
                ];
            });


        $categories = News_category::withCount('news')->get();

        $categorycountdata = $categories->map(function ($category) {
            return [
                'category_id' => $category->id,
                'category_name' => $category->name,
                'news_count' => $category->news_count
            ];
        });


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
