<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Federal_constituency;
use App\Models\ImportVotersdata;
use App\Models\Local_constituency;
use App\Models\Polling_unit;
use App\Models\Senatorial_state;
use App\Models\State;
use App\Models\State_constituency;
use App\Models\Ward;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class ManageVotersDataController extends Controller
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
        $stateList = State::where('state', '!=', '')->orderBy('state')->get();
        $senatorialList = Senatorial_state::where('sena_district', '!=', '')->orderBy('sena_district')->get();
        $federalList = Federal_constituency::where('federal_name', '!=', '')->orderBy('federal_name')->get();
        $localConList = Local_constituency::where('lga', '!=', '')->orderBy('lga')->get();
        $wardList = Ward::where('ward_details', '!=', '')->orderBy('ward_details')->get();
        $staList = State_constituency::where('state_constituency', '!=', '')->orderBy('state_constituency')->get();
        $pollingList = Polling_unit::where('polling_name', '!=', '')->orderBy('polling_name')->get();
        $getAllgender = ImportVotersdata::select('gender')->groupBy('gender')->orderByDesc('id')->get();
        $getAllemp = ImportVotersdata::select('employ_status')->groupBy('employ_status')->orderByDesc('id')->get();

        $whr = [];

        if ($request->has('search')) {
            $searchage = $request->input('searchage');
            $searchemp = $request->input('searchemp');
            $searchgender = $request->input('searchgender');
            $state = $request->input('state');
            $senatorial_state = $request->input('senatorial_state');
            $federal_constituency = $request->input('federal_constituency');
            $local_constituency = $request->input('local_constituency');
            $ward = $request->input('ward');
            $state_constituency = $request->input('state_constituency');
            $polling_unit = $request->input('polling_unit');
            $searchaddress = $request->input('searchaddress');

            if ($searchage == 1) {
                $whr[] = ['age', '>=', 18];
                $whr[] = ['age', '<=', 20];
            }
            if ($searchage == 2) {
                $whr[] = ['age', '>=', 21];
                $whr[] = ['age', '<=', 30];
            }
            if ($searchage == 3) {
                $whr[] = ['age', '>=', 31];
                $whr[] = ['age', '<=', 40];
            }
            if ($searchage == 4) {
                $whr[] = ['age', '>=', 41];
                $whr[] = ['age', '<=', 50];
            }
            if ($searchage == 5) {
                $whr[] = ['age', '>=', 51];
                $whr[] = ['age', '<=', 60];
            }
            if ($searchage == 6) {
                $whr[] = ['age', '>=', 61];
                $whr[] = ['age', '<=', 70];
            }
            if ($searchage == 7) {
                $whr[] = ['age', '>=', 71];
                $whr[] = ['age', '<=', 80];
            }
            if (!empty($searchemp)) {
                $whr[] = ['employ_status', 'like', '%' . trim($searchemp) . '%'];
            }
            if (!empty($searchgender)) {
                $whr[] = ['gender', 'like', '%' . trim($searchgender) . '%'];
            }
            if (!empty($searchaddress)) {
                $whr[] = ['address', 'like', '%' . trim($searchaddress) . '%'];
            }
            if (!empty($state)) {
                $whr[] = ['state', 'like', '%' . trim($state) . '%'];
            }
            if (!empty($senatorial_state)) {
                $whr[] = ['senatorial_state', 'like', '%' . trim($senatorial_state) . '%'];
            }
            if (!empty($federal_constituency)) {
                $whr[] = ['federal_constituency', 'like', '%' . trim($federal_constituency) . '%'];
            }
            if (!empty($local_constituency)) {
                $whr[] = ['local_constituency', 'like', '%' . trim($local_constituency) . '%'];
            }
            if (!empty($ward)) {
                $whr[] = ['ward', 'like', '%' . trim($ward) . '%'];
            }
            if (!empty($state_constituency)) {
                $whr[] = ['state_constituency', 'like', '%' . trim($state_constituency) . '%'];
            }
            if (!empty($polling_unit)) {
                $whr[] = ['polling_unit', 'like', '%' . trim($polling_unit) . '%'];
            }
        }

        // $paged = $request->input('paged', 1);
        // $perpage = 20;
        // $startpoint = ($paged * $perpage) - $perpage;

        $totrecord = ImportVotersdata::orderByDesc('id')->get();
        $votersData = ImportVotersdata::where($whr)->orderByDesc('id')->get();
        return view('admin.managevotersdata.list', compact('stateList', 'senatorialList', 'federalList', 'localConList', 'wardList', 'staList', 'pollingList', 'getAllgender', 'getAllemp', 'votersData'));
    }

    public function destroy($id)
    {
        $votersdata = ImportVotersdata::find($id); // Find the item by its ID
        if (!$votersdata) {
            return redirect()->back()->with('error', 'Item not found.'); // Redirect back if item does not exist
        }

        $votersdata->delete(); // Delete the item

        return redirect()->route('managevotersdata.list')->with('message', 'VotersData deleted successfully.'); // Redirect to the index page with success message
    }
}
