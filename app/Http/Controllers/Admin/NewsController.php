<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News_Social;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function newsadd()
    {
        return view('admin.managenews.add');
    }

    public function newslist()
    {
        return view('admin.managenews.list');
    }

    public function newsstore(Request $request)
    {
        $validatedData = $request->only(['newscategory', 'title', 'description', 'video_url', 'status', 'is_popular']);

        // Generate the slug from the title
        $slug = Str::slug($validatedData['title'], '-');


        // Check for uniqueness
        if (News_Social::where('slug', $slug)->exists()) {
            // If slug exists, return an error response or handle accordingly
            return redirect()->back()->withErrors(['slug' => 'The slug has already been taken.'])->withInput();
        }
 
        // Add the unique slug to validated data
        $validatedData['slug'] = $slug;
        // dd($validatedData['slug']);

        // Handle file upload if needed
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/news'), $imageName);
            $validatedData['image'] = $imageName;
        }

        $newsocial = new News_Social();

        // Assign validated values to the fields
        $newsocial->newscategory = $validatedData['newscategory'];
        $newsocial->title = $validatedData['title'];
        $newsocial->description = $validatedData['description'];
        $newsocial->video_url = $validatedData['video_url'];
        $newsocial->status = $validatedData['status'];
        $newsocial->image = $validatedData['image'];
        $newsocial->is_popular = isset($validatedData['is_popular']) ? (bool) $validatedData['is_popular'] : false; // Handle is_popular with default false if not set
        $newsocial->slug = $slug; // Ensure $slug is defined and holds the desired value
        $newsocial->save();

        // Redirect or return a response
        return redirect('admin/manage/news-list')->with('message', 'News has been added successfully.');
    }

    public function editnews($id)
    {
        $editnewsdata = News_Social::find($id);
        return view('admin.managenews.edit', compact('editnewsdata'));
    }


    public function updatenews(Request $request, $id)
    {
        // Find the news item
        $news = News_Social::findOrFail($id);

        // Get the input data directly without validation
        $validatedData = $request->only(['newscategory', 'title', 'description', 'video_url', 'is_popular', 'status']);

        // Generate the slug from the title
        $slug = Str::slug($validatedData['title'], '-');

        // Check for uniqueness except for the current record
        if (News_Social::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            // If slug exists for a different record, return an error response or handle accordingly
            return redirect()->back()->withErrors(['slug' => 'The Title has already been taken.'])->withInput();
        }

        // Handle the image file upload if present
        if ($request->hasFile('image')) {
            // Check if the old image exists and delete it
            if ($news->image && file_exists(public_path('images/news/' . $news->image))) {
                if (!unlink(public_path('images/news/' . $news->image))) {
                    return redirect()->back()->with('error', 'Failed to delete old image.');
                }
            }

            // Upload new image
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();

            // Move the image to the desired directory
            if (!$image->move(public_path('images/news'), $imageName)) {
                return redirect()->back()->with('error', 'Failed to upload new image.');
            }
            $validatedData['image'] = $imageName;
        }

        // Assign validated values to the fields
        $news->newscategory = $validatedData['newscategory'];
        $news->title = $validatedData['title'];
        $news->description = $validatedData['description'];
        $news->video_url = $validatedData['video_url'];
        $news->status = $validatedData['status'];
        $news->is_popular = isset($validatedData['is_popular']) ? (bool) $validatedData['is_popular'] : false; // Handle is_popular with default false if not set
        $news->slug = $slug; // Use the unique slug

        // Update the news item with the provided data
        if (!$news->save()) {
            return redirect()->back()->with('error', 'Failed to update news.');
        }

        // Redirect back to the news list with a success message
        return redirect('admin/manage/news-list')->with('message', 'News has been updated successfully.');
    }


    public function status(Request $request)
    {
        $get_id = $request->id;
        $catstatus = DB::table('news_socials')
            ->select('status')
            ->where('id', '=', $get_id)
            ->first();


        $astatus = $catstatus->status;
        if ($astatus == '1') {
            $astatus = '0';
        } else {
            $astatus = '1';
        }
        $statusupdate = DB::table('news_socials')
            ->where('id', $get_id)
            ->update(array('status' => $astatus));

        if ($statusupdate) {
            return response()->json([
                'status' => 'success',
                'code' => 200,
            ]);
        }
    }


    public function deletenews($id)
    {
        $newsdelet = News_Social::find($id); // Find the item by its ID
        if (!$newsdelet) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $newsdelet->delete(); // Delete the item

        return redirect()->route('news-list')->with('message', 'News Removed successfully !.'); // Redirect to the index page with success message
    }
}
