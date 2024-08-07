<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\AdminContactRequestMail;
use App\Mail\UserContactRequestMail;
use App\Models\Contactus;
use App\Models\Donation;
use App\Models\Page;
use App\Models\Sitesetting;
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
}
