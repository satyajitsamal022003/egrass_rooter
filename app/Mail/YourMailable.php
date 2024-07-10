<?php

// In YourMailable.php (app/Mail/YourMailable.php)

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class YourMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $umsg;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $umsg)
    {
        $this->subject = $subject;
        $this->umsg = $umsg;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('testmaastrix.solutions@gmail.com', 'Egrassrooter')
                    ->subject($this->subject)
                    ->view('admin.emails.your_mailable')
                    ->with(['umsg' => $this->umsg]);
    }
}

