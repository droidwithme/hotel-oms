<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HotelPasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    public $hotel;

    /**
     * Create a new message instance.
     *
     * @param $hotel
     */
    public function __construct($hotel)
    {
        $this->hotel = $hotel;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Password Reset')->view('mail.hotel-password-reset');
    }
}
