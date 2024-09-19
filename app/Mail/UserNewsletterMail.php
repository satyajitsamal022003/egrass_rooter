<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserNewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $siteTitle;
    public $logo;
    public $userEmail;
    public $confirmationLink;
    public $unsubscribeLink;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($siteTitle, $logo, $userEmail, $confirmationLink, $unsubscribeLink)
    {
        $this->siteTitle = $siteTitle;
        $this->logo = $logo;
        $this->userEmail = $userEmail;
        $this->confirmationLink = $confirmationLink;
        $this->unsubscribeLink = $unsubscribeLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->siteTitle . ': Newsletter Confirmation')
                    ->view('admin.emails.user_newsletter');
    }
}
