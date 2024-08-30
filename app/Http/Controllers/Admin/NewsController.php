<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News_Social;
use Illuminate\Support\Facades\DB;

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

        $validatedData = $request->only(['newscategory', 'title', 'description', 'video_url', 'status']);
        // Handle file upload if needed
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/news'), $imageName);
            $validatedData['image'] = $imageName;
        }

        News_Social::create($validatedData);

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
        $news = News_Social::findOrFail($id);

        $validatedData = $request->only(['newscategory', 'title', 'description', 'video_url', 'status']);

        if ($request->hasFile('image')) {
            if ($news->image && file_exists(public_path('images/news/' . $news->image))) {
                unlink(public_path('images/news/' . $news->image));
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/news'), $imageName);
            $validatedData['image'] = $imageName;
        }

        $news->update($validatedData);

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
