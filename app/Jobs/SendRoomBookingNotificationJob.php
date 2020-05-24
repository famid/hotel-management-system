<?php

namespace App\Jobs;

use App\Http\Services\Booking\RoomBookingService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Auth;

class SendRoomBookingNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $roomBookingService;
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
        $this->data['user_id'] = Auth::id();
        $this->roomBookingService = new RoomBookingService();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       $this->roomBookingService->booking($this->data);
    }
}
