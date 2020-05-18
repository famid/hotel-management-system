<?php

namespace App\Console\Commands;

use App\Http\Services\Booking\RoomBookingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateRoomReservationStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:reservation-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update reservation status';

    private $roomBookingService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->roomBookingService = new RoomBookingService();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
//        $this->roomBookingService->updateReservationStatus();
//        return true;
        Log::info("Cron job worked");
    }
}
