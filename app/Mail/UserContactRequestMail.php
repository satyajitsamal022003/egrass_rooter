<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserContactRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contact_name;
    public $site_title;
    public $subject;
    public $logo;

    public function __construct($contact_name, $site_title, $subject, $logo)
    {
        $this->contact_name = $contact_name;
        $this->site_title = $site_title;
        $this->subject = $subject;
        $this->logo = $logo;
    }

    public function build()
    {
        return $this->view('admin.emails.contact_request_user')
            ->subject($this->site_title . ': Contact Request');
    }
}
