<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function create()
    {
        return view('admin.pages.addpages');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $existPage = Page::where('slug', $request->input('slug_name'))->first();
        if ($existPage) {
            return back()->withErrors('The title has already been used!');
        }

        $pagedata = new Page();
        $pagedata->page_name = $request->input('title');
        $pagedata->slug  = $request->input('slug_name');
        $pagedata->content_title = $request->input('page_name');
        $pagedata->description = $request->input('description');
        $pagedata->short_description = $request->input('shortdesc');
        $pagedata->btm_description = $request->input('bottom_desc');
        $pagedata->page_banner_text = $request->input('banner_text');
        $pagedata->meta_title = $request->input('metatitle');
        $pagedata->meta_key = $request->input('meta_key');
        $pagedata->meta_desc = $request->input('meta_description');
        $pagedata->is_active = $request->input('status');
        $pagedata->created_at = NOW();
        $pagedata->updated_at = NOW();

        // Store Image
        if ($request->hasFile('image')) {
            // Generate a unique file name for the image
            $imageName = 'pages_' . time() . '.' . $request->file('image')->getClientOriginalExtension();

            $destinationDirectory = public_path('images/pages');

            if (!is_dir($destinationDirectory)) {
                mkdir($destinationDirectory, 0777, true);
            }

            // Move the file to the public/uploads directory
            $request->file('image')->move($destinationDirectory, $imageName);

            $pagedata->image = $imageName;
        }

        if ($request->hasFile('iconbanner_image')) {
            // Generate a unique file name for the image
            $iconBannerImageName = 'pages_' . time() . '.' . $request->file('iconbanner_image')->getClientOriginalExtension();

            $destinationDirectory = public_path('images/pages');

            if (!is_dir($destinationDirectory)) {
                mkdir($destinationDirectory, 0777, true);
            }

            // Move the file to the public/uploads directory
            $request->file('iconbanner_image')->move($destinationDirectory, $iconBannerImageName);

            $pagedata->banner_image = $iconBannerImageName;
        }

        $pagedata->save();

        return back()->with('message', 'Page Added Successfully !');
    }

    public function index()
    {
        return view('admin.pages.managepages');
    }

    public function destroy($id)
    {
        $pages = Page::find($id); // Find the item by its ID
        if (!$pages) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $pages->delete(); // Delete the item

        return redirect()->route('pages.index')->with('success', 'Item deleted successfully.'); // Redirect to the index page with success message
    }

    public function edit($id)
    {
        $editpages = Page::find($id);
        return view('admin.pages.editpages', compact('editpages'));
    }

    public function update(Request $request, $id)
    {

        $pagedata = Page::findOrFail($id);

        $existPage = Page::where('slug', $request->input('slug_name'))
            ->where('id', '!=', $id)
            ->first();

        if ($existPage) {
            return back()->withErrors('The slug has already been used!');
        }
        // Update page attributes
        $pagedata->page_name = $request->input('title');
        $pagedata->slug = $request->input('slug_name');
        $pagedata->content_title = $request->input('page_name');
        $pagedata->description = $request->input('description');
        $pagedata->short_description = $request->input('shortdesc');
        $pagedata->btm_description = $request->input('bottom_desc');
        $pagedata->page_banner_text = $request->input('banner_text');
        $pagedata->meta_title = $request->input('metatitle');
        $pagedata->meta_key = $request->input('meta_key');
        $pagedata->meta_desc = $request->input('meta_description');
        $pagedata->is_active = $request->input('status');
        $pagedata->updated_at = now();

        // Update Image
        if ($request->hasFile('image')) {
            // Generate a unique file name for the image
            $imageName = 'pages_' . time() . '.' . $request->file('image')->getClientOriginalExtension();

            $destinationDirectory = public_path('images/pages');

            if (!is_dir($destinationDirectory)) {
                mkdir($destinationDirectory, 0777, true);
            }

            // Move the file to the public/uploads directory
            $request->file('image')->move($destinationDirectory, $imageName);

            $existingimagepath = $destinationDirectory . '/' . $pagedata->image;

            if (!empty($pagedata->image)) {
                $existingimagepath = public_path('images/pages/' . $pagedata->image);
            
                if (file_exists($existingimagepath) && !is_dir($existingimagepath)) {
                    // Delete the file only if it's not a directory
                    unlink($existingimagepath);
                }
            }
            

            $pagedata->image = $imageName;
        }

        if ($request->hasFile('iconbanner_image')) {
            // Generate a unique file name for the image
            $iconBannerImageName = 'pages_' . time() . '.' . $request->file('iconbanner_image')->getClientOriginalExtension();

            $destinationDirectory = public_path('images/pages');

            if (!is_dir($destinationDirectory)) {
                mkdir($destinationDirectory, 0777, true);
            }

            // Move the file to the public/uploads directory
            $request->file('iconbanner_image')->move($destinationDirectory, $iconBannerImageName);

            $existingbannerimage = $destinationDirectory . '/' . $pagedata->banner_image;

            if (file_exists($existingbannerimage)) {
                // Delete the file
                unlink($existingbannerimage);
            }

            $pagedata->banner_image = $iconBannerImageName;
        }

        $pagedata->save();

        return back()->with('message', 'Page updated Successfully !');
    }

    public function status(Request $request)
    {
        $get_id = $request->id;
        $catstatus = DB::table('pages')
            ->select('is_active')
            ->where('id', '=', $get_id)
            ->first();


        $astatus = $catstatus->is_active;
        if ($astatus == '1') {
            $astatus = '0';
        } else {
            $astatus = '1';
        }
        $statusupdate = DB::table('pages')
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
