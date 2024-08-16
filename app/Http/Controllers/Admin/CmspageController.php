<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AboutusPage;
use Illuminate\Http\Request;
use App\Models\CmsPage;
use App\Models\HomeBanner;
use App\Models\Homepage;
use Brian2694\Toastr\Facades\Toastr;
use Goutte\Client;

class CmspageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $cmsPages = CmsPage::latest()->get();
        return view('admin.cmspages.index', compact('cmsPages'));
    }

    public function edit($id)
    {
        $cmsPage = CmsPage::find($id);

        if ($cmsPage->id == 1) {
            $homebanner = HomeBanner::latest()->get();
            $homePage = HomePage::find(1);

            return view('admin.cmspages.home.edit', compact('cmsPage', 'homebanner', 'homePage'));
        } else if ($cmsPage->id == 2) {
            $aboutus = AboutusPage::find(1);
            return view('admin.cmspages.about-us.edit', compact('cmsPage', 'aboutus'));
        }
    }


    public function update(Request $request, $id)
    {
        $cmsPage = CmsPage::find($id);
        $cmsPage->title = $request->title;
        $cmsPage->status = $request->status;
        if ($cmsPage->save()) {
            return redirect()->route('cmspages.index')->with('message', 'CMS Pages Updated Successfully !');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editHome(Request $request, $id)
    {
        $objHomeData = Homepage::find($id);
        return view('admin.cmspages.home.edit', compact('objHomeData'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateHome(Request $request, $id)
    {
        $objHomeData = Homepage::find($id);
        if (!$objHomeData) {
            return redirect()->back()->with('error', 'Home Page not found');
        }
        $objHomeData->about_heading = $request->about_heading ? $request->about_heading : $objHomeData->about_heading;
        $objHomeData->about_title = $request->about_title ? $request->about_title : $objHomeData->about_title;
        $objHomeData->about_desc = $request->about_desc ? $request->about_desc : $objHomeData->about_desc;
        $objHomeData->about_btn = $request->about_btn ? $request->about_btn : $objHomeData->about_btn;
        $objHomeData->about_btn_url = $request->about_btn_url ? $request->about_btn_url : $objHomeData->about_btn_url;

        $objHomeData->why_choose_us_title = $request->why_choose_us_title ? $request->why_choose_us_title : $objHomeData->why_choose_us_title;
        $objHomeData->why_choose_us_desc = $request->why_choose_us_desc ? $request->why_choose_us_desc : $objHomeData->why_choose_us_desc;

        $objHomeData->runforoffice_title = $request->runforoffice_title ? $request->runforoffice_title : $objHomeData->runforoffice_title;
        $objHomeData->runforoffice_desc = $request->runforoffice_desc ? $request->runforoffice_desc : $objHomeData->runforoffice_desc;

        $objHomeData->overview_title = $request->overview_title ? $request->overview_title : $objHomeData->overview_title;
        $objHomeData->overview_desc = $request->overview_desc ? $request->overview_desc : $objHomeData->overview_desc;


        if ($request->file('about_image')) {
            $destinationPath = '/homepage';
            $imgfile = $request->file('about_image');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
            $objHomeData->about_image = $image;
        }
        if ($request->file('why_choose_us_image')) {
            $destinationPath = '/homepage';
            $imgfile = $request->file('why_choose_us_image');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
            $objHomeData->why_choose_us_image = $image;
        }
        if ($objHomeData->save()) {
            return redirect()->back()->with('message', 'Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'Something went wrong !! Please Try again later');
        }
    }


    public function editHomeBanner($id)
    {

        $homebanner = HomeBanner::find($id);
        return view('admin.cmspages.home.banner_edit', compact('homebanner'));
    }

    public function storeHomeBanner(Request $request)
    {
        // dd($request->file('image'));
        $homebanner = new HomeBanner();
        $homebanner->banner_title = $request->banner_title;
        $homebanner->banner_desc = $request->input('banner_desc');
        $homebanner->banner_button_name = $request->input('banner_button_name');
        $homebanner->banner_button_url = $request->input('banner_button_url');
        $homebanner->is_active = 1;
        if ($request->file('banner_image')) {
            $destinationPath = '/homebanner';
            $imgfile = $request->file('banner_image');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
            $homebanner->banner_image = $image;
        }
        if ($homebanner->save()) {
            // return redirect()->back()->with(Toastr::success('Loan Type Section Added Successfully', '', ["positionClass" => "toast-top-right"]));
            return redirect()->back()->with('message', 'Banner Added Successfully!');
        }
    }
    public function updateHomeBanner(Request $request, $id)
    {
        $homebanner = HomeBanner::find($id);
        $homebanner->banner_title = $request->banner_title;
        $homebanner->banner_desc = $request->input('banner_desc');
        $homebanner->banner_button_name = $request->input('banner_button_name');
        $homebanner->banner_button_url = $request->input('banner_button_url');
        $homebanner->is_active = $request->is_active;

        if ($request->file('banner_image')) {
            $destinationPath = 'homebanner';
            $imgfile = $request->file('banner_image');
            $imgFilename = time() . '_' . $imgfile->getClientOriginalName();
            $imgfile->move(public_path($destinationPath), $imgFilename);
            $homebanner->banner_image = $imgFilename;
        }

        if ($homebanner->save()) {
            return redirect()->back()->with('message', 'Banner Updated Successfully!');
        }

        return redirect()->back()->with('error', 'Failed to update the banner.');
    }


    // public function destroyHomeBanner($id)
    // {
    //     $homebanner = HomeBanner::find($id);
    //     if ($homebanner->delete()) {
    //         return redirect()->back()->with('message', 'Banner Deleted Successfully!');
    //     }
    // }

    public function destroyHomeBanner($id)
    {
        $homebanner = HomeBanner::find($id);
        if (!$homebanner) {
            return redirect()->back()->with('error', 'Item not found.');
        }

        $homebanner->delete();

        // return redirect()->route('manageblog.list')->with('success', 'Banner Deleted Successfully!');
        return redirect()->back()->with('message', 'Banner Deleted Successfully!');
    }



    public function editAbout(Request $request, $id)
    {
        $objAboutusData = AboutusPage::find($id);
        return view('admin.cmspages.about-us.edit', compact('objAboutusData'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAbout(Request $request, $id)
    {
        $objAboutusData = AboutusPage::find($id);
        if (!$objAboutusData) {
            return redirect()->back()->with('error', 'About Us Page not found');
        }
        $objAboutusData->page_name = $request->page_name ? $request->page_name : $objAboutusData->page_name;
        $objAboutusData->who_we_are_heading = $request->who_we_are_heading ? $request->who_we_are_heading : $objAboutusData->who_we_are_heading;
        $objAboutusData->who_we_are_title = $request->who_we_are_title ? $request->who_we_are_title : $objAboutusData->who_we_are_title;
        $objAboutusData->who_we_are_desc1 = $request->who_we_are_desc1 ? $request->who_we_are_desc1 : $objAboutusData->who_we_are_desc1;
        $objAboutusData->who_we_are_desc2 = $request->who_we_are_desc2 ? $request->who_we_are_desc2 : $objAboutusData->who_we_are_desc2;

        $objAboutusData->year_of_exp = $request->year_of_exp ? $request->year_of_exp : $objAboutusData->year_of_exp;
        $objAboutusData->revenue_count = $request->revenue_count ? $request->revenue_count : $objAboutusData->revenue_count;
        $objAboutusData->sales_count = $request->sales_count ? $request->sales_count : $objAboutusData->sales_count;

        $objAboutusData->slogan_heading = $request->slogan_heading ? $request->slogan_heading : $objAboutusData->slogan_heading;
        $objAboutusData->slogan_title = $request->slogan_title ? $request->slogan_title : $objAboutusData->slogan_title;
        $objAboutusData->slogan_video_url = $request->slogan_video_url ? $request->slogan_video_url : $objAboutusData->slogan_video_url;


        if ($request->file('banner_image')) {
            $destinationPath = '/aboutuspage';
            $imgfile = $request->file('banner_image');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
            $objAboutusData->banner_image = $image;
        }
        if ($request->file('who_we_are_image1')) {
            $destinationPath = '/aboutuspage';
            $imgfile = $request->file('who_we_are_image1');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
            $objAboutusData->who_we_are_image1 = $image;
        }
        if ($request->file('who_we_are_image2')) {
            $destinationPath = '/aboutuspage';
            $imgfile = $request->file('who_we_are_image2');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
            $objAboutusData->who_we_are_image2 = $image;
        }
        if ($request->file('revenue_image')) {
            $destinationPath = '/aboutuspage';
            $imgfile = $request->file('revenue_image');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
            $objAboutusData->revenue_image = $image;
        }
        if ($request->file('sales_image')) {
            $destinationPath = '/aboutuspage';
            $imgfile = $request->file('sales_image');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
            $objAboutusData->sales_image = $image;
        }
        if ($request->file('slogan_bg_image')) {
            $destinationPath = '/aboutuspage';
            $imgfile = $request->file('slogan_bg_image');
            $imgFilename = $imgfile->getClientOriginalName();
            $imgfile->move(public_path() . $destinationPath, $imgfile->getClientOriginalName());
            $image = $imgFilename;
            $objAboutusData->slogan_bg_image = $image;
        }
        if ($objAboutusData->save()) {
            return redirect()->back()->with('message', 'Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'Something went wrong !! Please Try again later');
        }
    }
}
