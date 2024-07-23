<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $activationLink;
    public $logo;
    public $emailContent;

    public function __construct($activationLink, $logo, $emailContent)
    {
        $this->activationLink = $activationLink;
        $this->logo = $logo;
        $this->emailContent = $emailContent;
    }

    public function build()
    {
        return $this->subject('Registration Confirmation')
            ->view('admin.emails.registration') // Define this view to handle HTML email content
            ->with([
                'activationLink' => $this->activationLink,
                'logo' => $this->logo,
                'emailContent' => $this->emailContent,
            ]);
    }
}
