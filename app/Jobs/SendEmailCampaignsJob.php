<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailCampaignsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $data;
    public $emails;

    public function __construct($emails, $data)
    {
        $this->emails = $emails;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $email = $this->emails[0];
            Mail::send('email.email-campaigns', $this->data, function ($message) use ($email){
                $message->to($email)->subject($this->data['subject'])->from(
                    $this->data['defaultEmail'], $this->data['defaultName']
                );
            });

            if (($key = array_search($email, $this->emails)) !== false) {
                array_splice($this->emails, $key, 1);
            }

            if (count($this->emails) > 0) {
                dispatch(new SendEmailCampaignsJob($this->emails, $this->data))->onQueue('email-campaigns');
            }
        } catch (\Exception $exception) {
            Log::info($exception->getMessage() . ' ' . $exception->getLine());
        }
    }
}
