<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\AdminContactRequestMail;
use App\Mail\UserContactRequestMail;
use App\Models\AboutusPage;
use App\Models\Contactus;
use App\Models\Donation;
use App\Models\Feature_section;
use App\Models\HomeBanner;
use App\Models\Homepage;
use App\Models\Page;
use App\Models\Service;
use App\Models\Sitesetting;
use App\Models\Testimonial;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function getPageData($id)
    {
        $page = Page::find($id);

        if ($page) {
            return response()->json($page, 200);
        } else {
            return response()->json(['message' => 'Page not found'], 404);
        }
    }

    public function getContactUsData(Request $request)
    {
        try {
            $contactsRes = Page::find(5);

            if (!$contactsRes) {
                return response()->json([
                    'success' => false,
                    'message' => 'Page not found',
                ], 404);
            }

            $pagBannerImage = $contactsRes->banner_image;
            $bannerImgpath = file_exists(public_path('uploads/pages/' . $pagBannerImage))
                ? asset('uploads/pages/' . $pagBannerImage)
                : asset('frontfile/images/banner_main.jpg');

            $pagBannerText = $contactsRes->page_banner_text;
            $pagName = $contactsRes->page_name;
            $pagDesc = $contactsRes->description;

            $contactList = Sitesetting::find(1);

            if (!$contactList) {
                return response()->json([
                    'success' => false,
                    'message' => 'Site settings not found',
                ], 404);
            }

            $contactAddress = $contactList->location;
            $contactPhone = $contactList->contact;
            $contactEmail = $contactList->admin_mail_id;

            $responseData = [
                'banner_image' => $bannerImgpath,
                'page_banner_text' => $pagBannerText,
                'page_name' => $pagName,
                'description' => $pagDesc,
                'contact_address' => $contactAddress,
                'contact_phone' => $contactPhone,
                'contact_email' => $contactEmail,
            ];

            return response()->json([
                'success' => true,
                'data' => $responseData,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve contact us data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function contactUsStore(Request $request)
    {
        // Validate request inputs
        $request->validate([
            'contact_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:15',
            'message' => 'required|string|max:1000',
        ]);

        // Create a new Contactus instance
        $contactdata = new Contactus();
        $contactdata->contact_name = $request->input('contact_name');
        $contactdata->contact_email = $request->input('contact_email');
        $contactdata->contact_phone = $request->input('contact_phone');
        $contactdata->message = $request->input('message');
        $contactdata->is_active = $request->input('is_active', '0');
        $contactdata->sent_date = now();

        // Save the contact data
        if ($contactdata->save()) {
            // Fetch site settings
            $siteAdmin = Sitesetting::find(1);
            if (!$siteAdmin) {
                return response()->json(['message' => 'Site settings not found'], 500);
            }

            // Prepare admin email content
            $adminEmail = new AdminContactRequestMail(
                $contactdata->contact_name,
                $contactdata->contact_email,
                $contactdata->contact_phone,
                $siteAdmin->site_title,
                file_exists(public_path('images/siteimage/' . $siteAdmin->logo)) ? asset('images/siteimage/' . $siteAdmin->logo) : asset('images/logo.jpg')
            );

            // Prepare user email content
            $userEmail = new UserContactRequestMail(
                $contactdata->contact_name,
                $siteAdmin->site_title,
                $contactdata->message,
                file_exists(public_path('images/siteimage/' . $siteAdmin->logo)) ? asset('images/siteimage/' . $siteAdmin->logo) : asset('images/logo.jpg')
            );

            // Send email to admin
            Mail::to($siteAdmin->admin_mail_id)->send($adminEmail);

            // Send email to user
            Mail::to($contactdata->contact_email)->send($userEmail);

            return response()->json(['message' => 'Thank you for contacting us! We will get in touch with you shortly.'], 201);
        } else {
            return response()->json(['message' => 'Failed to save contact request! Please try again.'], 500);
        }
    }

    public function donationDataStore(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile_no' => 'required|string|max:15',
            'party' => 'required|string|max:1000',
        ]);

        $donationdata = new Donation();
        $donationdata->full_name = $request->input('full_name');
        $donationdata->email = $request->input('email');
        $donationdata->mobile_no = $request->input('mobile_no');
        $donationdata->party = $request->input('party');
        $donationdata->donation_purpose = $request->input('donation_purpose');
        $donationdata->amount = $request->input('amount');
        $donationdata->is_active = $request->input('is_active', '0');
        $donationdata->created = now();

        if ($donationdata->save()) {
            return response()->json(['message' => 'Donation Created Successfully.'], 201);
        } else {
            return response()->json(['message' => 'Failed to save Donation Please try again.'], 500);
        }
    }


    public function getSiteData()
    {
        $sitesetting = Sitesetting::find(1);

        if (!$sitesetting) {
            return response()->json(['message' => 'Site Data not found'], 404);
        }


        return response()->json($sitesetting, 200);
    }

    public function getHomePageData()
    {
        $homecontents = Homepage::find(1);

        if (!$homecontents) {
            return response()->json(['message' => 'Home Page Data not found'], 404);
        }

        $home_contents = [
            'id' => $homecontents->id,
            'about_heading' => $homecontents->about_heading ?? "",
            'about_title' => $homecontents->about_title ?? "",
            'about_desc' => $homecontents->about_desc ?? "",
            'about_btn' => $homecontents->about_btn ?? "",
            'about_btn_url' => $homecontents->about_btn_url ?? "",
            'about_image' => $homecontents->about_image ? asset('homepage/' . $homecontents->about_image) : "",
            'why_choose_us_title' => $homecontents->why_choose_us_title ?? "",
            'why_choose_us_desc' => $homecontents->why_choose_us_desc ?? "",
            'why_choose_us_image' => $homecontents->why_choose_us_image ? asset('homepage/' . $homecontents->why_choose_us_image) : "",
            'runforoffice_title' => $homecontents->runforoffice_title ?? "",
            'runforoffice_desc' => $homecontents->runforoffice_desc ?? "",
            'overview_title' => $homecontents->overview_title ?? "",
            'overview_desc' => $homecontents->overview_desc ?? "",
            'created_at' => Carbon::parse($homecontents->created_at)->format('F d, Y H:i:s'),
        ];

        $homeBanners = HomeBanner::where('is_active', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        $homeBannerData = [];
        foreach ($homeBanners as $key => $banner) {
            $homeBannerData[$key] = [
                'id' => $banner->id,
                'banner_title' => $banner->banner_title ?? "",
                'banner_desc' => $banner->banner_desc ?? "",
                'banner_button_name' => $banner->banner_button_name ?? "",
                'banner_button_url' => $banner->banner_button_url ?? "",
                'banner_image' => $banner->banner_image ? asset('homebanner/' . $banner->banner_image) : "",
                'created_at' => Carbon::parse($banner->created_at)->format('F d, Y'),
            ];
        }


        $services = Service::where('is_active', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        $servicesData = [];
        foreach ($services as $key => $service) {
            $servicesData[$key] = [
                'id' => $service->id,
                'title' => $service->title ?? "",
                'content' => $service->content ?? "",
                'icon' => $service->icon ?? "",
                // 'icon' => $service->icon ? asset('services/' . $banner->icon) : "",
                'created_at' => Carbon::parse($banner->created_at)->format('F d, Y'),
            ];
        }

        $features = Feature_section::where('is_active', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        $featuresData = [];
        foreach ($features as $key => $feature) {
            $featuresData[$key] = [
                'id' => $feature->id,
                'title' => $feature->title ?? "",
                'slug' => $feature->slug ?? "",
                'description' => $feature->description ?? "",
                'icon' => $feature->icon ?? "",
                'created_at' => Carbon::parse($feature->created_at)->format('F d, Y'),
            ];
        }

        $testimonials = Testimonial::where('is_active', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        $testimonialsData = [];
        foreach ($testimonials as $key => $testimonial) {
            $testimonialsData[$key] = [
                'id' => $testimonial->id,
                'client_name' => $testimonial->client_name ?? "",
                'position' => $testimonial->position ?? "",
                'description' => $testimonial->description ?? "",
                'client_image' => $testimonial->client_image ? asset('images/testimonials/' . $testimonial->client_image) : "",
                'created_at' => Carbon::parse($banner->created_at)->format('F d, Y'),
            ];
        }

        $response = [
            'home_contents' => $home_contents,
            'home_banners' => $homeBannerData,
            'services' => $servicesData,
            'features' => $featuresData,
            'testimonials' => $testimonialsData,
        ];

        return response()->json($response, 200);
    }


    public function getAboutUsPageData()
    {
        $aboutusData = AboutusPage::find(1);

        if (!$aboutusData) {
            return response()->json(['message' => 'About Us Data not found'], 404);
        }

        $aboutus_data = [
            'id' => $aboutusData->id,
            'page_name' => $aboutusData->page_name ?? "",
            'banner_image' => $aboutusData->banner_image ? asset('aboutuspage/' . $aboutusData->banner_image) : "",
            'who_we_are_heading' => $aboutusData->who_we_are_heading ?? "",
            'who_we_are_title' => $aboutusData->who_we_are_title ?? "",
            'who_we_are_desc1' => $aboutusData->who_we_are_desc1 ?? "",
            'who_we_are_desc2' => $aboutusData->who_we_are_desc2 ?? "",
            'who_we_are_image1' => $aboutusData->who_we_are_image1 ? asset('aboutuspage/' . $aboutusData->who_we_are_image1) : "",
            'who_we_are_image2' => $aboutusData->who_we_are_image2 ? asset('aboutuspage/' . $aboutusData->who_we_are_image2) : "",
            'year_of_exp' => $aboutusData->year_of_exp ?? "",
            'revenue_count' => $aboutusData->revenue_count ?? "",
            'revenue_image' => $aboutusData->revenue_image ? asset('aboutuspage/' . $aboutusData->revenue_image) : "",
            'sales_count' => $aboutusData->sales_count ?? "",
            'sales_image' => $aboutusData->sales_image ? asset('aboutuspage/' . $aboutusData->sales_image) : "",
            'slogan_heading' => $aboutusData->slogan_heading ?? "",
            'slogan_title' => $aboutusData->slogan_title ?? "",
            'slogan_bg_image' => $aboutusData->slogan_bg_image ? asset('aboutuspage/' . $aboutusData->slogan_bg_image) : "",
            'slogan_video_url' => $aboutusData->slogan_video_url ?? null,
            'created_at' => Carbon::parse($aboutusData->created_at)->format('F d, Y H:i:s'),
        ];

        return response()->json($aboutus_data, 200);
    }
}
