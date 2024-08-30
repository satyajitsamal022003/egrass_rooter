<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminContactRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contact_name;
    public $contact_email;
    public $contact_phone;
    public $site_title;
    public $logo;

    public function __construct($contact_name, $contact_email, $contact_phone, $site_title, $logo)
    {
        $this->contact_name = $contact_name;
        $this->contact_email = $contact_email;
        $this->contact_phone = $contact_phone;
        $this->site_title = $site_title;
        $this->logo = $logo;
    }

    public function build()
    {
        return $this->view('admin.emails.contact_request_admin')
            ->subject($this->site_title . ': Contact Request');
    }
}
