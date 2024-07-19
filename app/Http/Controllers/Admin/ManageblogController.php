<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class ManageblogController extends Controller
{
    public function create()
    {
        return view('admin.manageblogs.add');
    }

    public function store(Request $request)
    {

        $catid = $request->get('category');
        $catslug = DB::table('blog_categories')->select('slug')->where('cat_id', $catid)->value('slug');

        $tagid = $request->get('tag');
        $tagslug = DB::table('blog_tags')->select('slug')->where('tag_id', $tagid)->value('slug');

        $blogdata = new Blog();
        $blogdata->cat_slug = $catslug; // Assuming 'cat_slug' is a string field
        $blogdata->tag_slug = $tagslug; // Assuming 'tag_slug' is a string field
        $blogdata->page_name = $request->input('title');
        $blogdata->slug = $request->input('slug');
        $blogdata->description = $request->input('description');
        $blogdata->meta_title = $request->input('metatitle');
        $blogdata->meta_key = $request->input('meta_key');
        $blogdata->meta_desc = $request->input('meta_description');
        $blogdata->is_active = $request->input('status');
        $blogdata->created_at = now();
        $blogdata->updated_at = now();

        if ($request->hasFile('image')) {
            // Generate a unique file name for the image
            $imageName = 'blogs_' . time() . '.' . $request->file('image')->getClientOriginalExtension();

            $destinationDirectory = public_path('images/blogs');

            if (!is_dir($destinationDirectory)) {
                mkdir($destinationDirectory, 0777, true);
            }

            // Move the file to the public/uploads directory
            $request->file('image')->move($destinationDirectory, $imageName);

            $blogdata->blog_image = $imageName;
        }

        $blogdata->save();

        return back()->with('message', 'Blog Added Successfully!');
    }

    public function edit($id)
    {

        $editblogs = Blog::find($id);
        // dd($editblogs);
        $editblogcat = DB::table('blog_categories')->where('slug', $editblogs->cat_slug)->first();
        $editblogtag = DB::table('blog_tags')->where('slug', $editblogs->tag_slug)->first();

        return view('admin.manageblogs.edit', compact('editblogs', 'editblogcat', 'editblogtag'));
    }

    public function update(Request $request, $id)
    {
        $catid = $request->get('category');
        $catslug = DB::table('blog_categories')->select('slug')->where('cat_id', $catid)->value('slug');

        $tagid = $request->get('tag');
        $tagslug = DB::table('blog_tags')->select('slug')->where('tag_id', $tagid)->value('slug');

        $blogdata = Blog::findOrFail($id);
        $blogdata->cat_slug = $catslug; // Assuming 'cat_slug' is a string field
        $blogdata->tag_slug = $tagslug; // Assuming 'tag_slug' is a string field
        $blogdata->page_name = $request->input('title');
        $blogdata->slug = $request->input('slug');
        $blogdata->description = $request->input('description');
        $blogdata->meta_title = $request->input('metatitle');
        $blogdata->meta_key = $request->input('meta_key');
        $blogdata->meta_desc = $request->input('meta_description');
        $blogdata->is_active = $request->input('status');
        $blogdata->updated_at = now();

        // Update Image
        if ($request->hasFile('image')) {
            // Generate a unique file name for the image
            $imageName = 'blogs_' . time() . '.' . $request->file('image')->getClientOriginalExtension();

            $destinationDirectory = public_path('images/blogs');

            if (!is_dir($destinationDirectory)) {
                mkdir($destinationDirectory, 0777, true);
            }

            // Move the file to the public/uploads directory
            $request->file('image')->move($destinationDirectory, $imageName);

            // Delete existing image file if it exists
            if (!empty($blogdata->image)) {
                $existingImagePath = $destinationDirectory . '/' . $blogdata->image;
                if (file_exists($existingImagePath) && is_file($existingImagePath)) {
                    unlink($existingImagePath);
                }
            }

            $blogdata->blog_image = $imageName;
        }

        $blogdata->save();

        return back()->with('message', 'Blog updated Successfully !');
    }


    public function list(Request $request)
    {
        $catSlug = $request->input('cat_slug');
        $tagSlug = $request->input('tag_slug');
        $searchTxt = $request->input('searchtxt');

        $query = Blog::query();

        if (!empty($catSlug)) {
            $query->where('cat_slug', $catSlug);
        }

        if (!empty($tagSlug)) {
            $query->where('tag_slug', $tagSlug);
        }

        if (!empty($searchTxt)) {
            $query->where('page_name', 'like', '%' . $searchTxt . '%');
        }

        $blogs = $query->get();
        $categories = BlogCategory::all();
        $tags = BlogTag::all();

        return view('admin.manageblogs.list', compact('blogs', 'categories', 'tags'));
    }


    public function commentlist($id)
    {
        dd($id);
    }

    public function destroy($id)
    {
        $blogs = Blog::find($id); // Find the item by its ID
        if (!$blogs) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $blogs->delete(); // Delete the item

        return redirect()->route('manageblog.list')->with('success', 'Blog deleted successfully.'); // Redirect to the index page with success message
    }

    public function filter(Request $request)
    {
        $catSlug = $request->input('cat_slug');
        $tagSlug = $request->input('tag_slug');
        $searchTxt = $request->input('searchtxt');

        $query = Blog::query();

        if (!empty($catSlug)) {
            $query->where('cat_slug', $catSlug);
        }

        if (!empty($tagSlug)) {
            $query->where('tag_slug', $tagSlug);
        }

        if (!empty($searchTxt)) {
            $query->where('page_name', 'like', '%' . $searchTxt . '%');
        }

        $blogs = $query->get();

        // Load the view and pass the filtered blogs data
        $html = View::make('admin.manageblogs.list', compact('blogs'))->render();

        return response()->json(['html' => $html]);
    }


    public function status(Request $request)
    {
        $get_id = $request->id;
        $catstatus = DB::table('blogs')
            ->select('is_active')
            ->where('id', '=', $get_id)
            ->first();


        $astatus = $catstatus->is_active;
        if ($astatus == '1') {
            $astatus = '0';
        } else {
            $astatus = '1';
        }
        $statusupdate = DB::table('blogs')
            ->where('id', $get_id)
            ->update(array('is_active' => $astatus));

        if ($statusupdate) {
            return response()->json([
                'status' => 'success',
                'code' => 200,
            ]);
        }
    }
}
