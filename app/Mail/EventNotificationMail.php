<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $firstName;
    public $lastName;
    public $eventDate;
    public $emailImgpath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($firstName, $lastName, $eventDate, $emailImgpath)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->eventDate = $eventDate;
        $this->emailImgpath = $emailImgpath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('admin.emails.event_notification')
            ->subject('Event Notification')
            ->with([
                'first_name' => $this->firstName,
                'last_name' => $this->lastName,
                'event_date' => $this->eventDate,
                'emailImgpath' => $this->emailImgpath,
            ]);
    }
}
