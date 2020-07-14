<?php

namespace App\Jobs;

use App\Mail\HotelPasswordReset;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendHotelPasswordResetMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $hotel;

    /**
     * Create a new job instance.
     *
     * @param $hotel
     */
    public function __construct($hotel)
    {
        $this->hotel = $hotel;
    }

    /**
     * Execute the job.
     *
     * @return bool
     */
    public function handle()
    {
        if($this->hotel['email'] != null && $this->hotel['email'] != ""){
            Mail::to($this->hotel['email'])->send(new HotelPasswordReset($this->hotel));
        } else {
            return true;
        }
    }
}
