<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminNewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $siteTitle;
    public $logo;
    public $userEmail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($siteTitle, $logo, $userEmail)
    {
        $this->siteTitle = $siteTitle;
        $this->logo = $logo;
        $this->userEmail = $userEmail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->siteTitle . ': New Newsletter Subscription')
                    ->view('admin.emails.admin_newsletter');
    }
}
