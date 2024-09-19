<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\AdminContactRequestMail;
use App\Mail\AdminNewsletterMail;
use App\Mail\UserContactRequestMail;
use App\Mail\UserNewsletterMail;
use App\Models\Contactus;
use App\Models\Donation;
use App\Models\Newsletter;
use App\Models\Page;
use App\Models\Sitesetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function subscribenewsletter(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'user_email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid email address'], 400);
        }

        $user_email = $request->input('user_email');

        // Check if email already exists
        $existingSubscription = Newsletter::where('user_email', $user_email)->first();
        if ($existingSubscription) {
            return response()->json(['message' => 'Already subscribed'], 200);
        }

        // Create new subscription
        $newsletter = new Newsletter();
        $newsletter->user_email = $user_email;
        $newsletter->created = now();
        $newsletter->status = 0;
        $newsletter->user_name = $user_email;

        if ($newsletter->save()) {
            $insertID = $newsletter->subscribe_id;

            // Fetch site settings
            $siteAdmin = Sitesetting::find(1);
            if (!$siteAdmin) {
                return response()->json(['message' => 'Site settings not found'], 500);
            }

            // Prepare email content
            $logo = file_exists(public_path('images/siteimage/' . $siteAdmin->logo))
                ? asset('images/siteimage/' . $siteAdmin->logo)
                : asset('images/logo.jpg');

            $confirmationLink = url("index/confirmation?email=" . $user_email . "&task=confirmation");
            $unsubscribeLink = url("index/unscribe?unscribe=" . base64_encode($insertID));

            // Send email to admin
            Mail::to($siteAdmin->admin_mail_id)->send(new AdminNewsletterMail(
                $siteAdmin->site_title,
                $logo,
                $user_email
            ));

            // Send email to user
            Mail::to($user_email)->send(new UserNewsletterMail(
                $siteAdmin->site_title,
                $logo,
                $user_email,
                $confirmationLink,
                $unsubscribeLink
            ));

            return response()->json(['message' => 'Successfully subscribed'], 201);
        } else {
            return response()->json(['message' => 'Failed to save subscription'], 500);
        }

        return response()->json(['message' => 'Invalid request method'], 405);
    }
}
