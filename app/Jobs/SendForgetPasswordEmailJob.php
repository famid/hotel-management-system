<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendForgetPasswordEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $randNo;
    protected $defaultName;
    protected $logo;
    protected $user;
    protected $defaultEmail;

    /**
     * Create a new job instance.
     *
     * @param $randNo
     * @param $defaultName
     * @param $logo
     * @param $user
     * @param $defaultEmail
     */
    public function __construct($randNo, $defaultName, $logo, $user, $defaultEmail)
    {
        $this->randNo = $randNo;
        $this->defaultName = $defaultName;
        $this->logo = $logo;
        $this->user = $user;
        $this->defaultEmail = $defaultEmail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $user = $this->user;
            $defaultEmail = $this->defaultEmail;
            $defaultName = $this->defaultName;
            Mail::send('email.forget_password', ['key' => $this->randNo, 'company' => $defaultName, 'logo' => $this->logo], function ($message) use ($user, $defaultEmail, $defaultName) {
                $message->to($user->email)->subject(__('Forget Password'))->from(
                    $defaultEmail, $defaultName
                );
            });
        }catch (\Exception $exception){
            Log::info($exception->getMessage());
        }
    }
}
