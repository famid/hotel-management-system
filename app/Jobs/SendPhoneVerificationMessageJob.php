<?php

namespace App\Jobs;

use Aloha\Twilio\Twilio;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SendPhoneVerificationMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $user;
    private $randNo;

    /**
     * Create a new job instance.
     *
     * @param $user
     * @param $randNo
     */
    public function __construct($user, $randNo)
    {
        $this->user = $user;
        $this->randNo = $randNo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->user;
        $randNo = $this->randNo;
        try {
            $settings = allSetting(['twilio_sid', 'twilio_token', 'twilio_from']);
            $twilio = new Twilio($settings['twilio_sid'], $settings['twilio_token'], $settings['twilio_from']);
            $twilio->message($user->phone, __('Your verification code is ') . $randNo);
        }catch (\Exception $e){
            Log::info($e->getMessage());
        }
    }
}
