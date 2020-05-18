<?php

namespace App\Rules;

use App\Http\Services\Booking\RoomBookingService;
use Illuminate\Contracts\Validation\Rule;

class CheckInRules implements Rule
{
    private $roomBookingService;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->roomBookingService = new RoomBookingService();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return  $this->roomBookingService->validRoomCheckInDate($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Can not check in  room before current datetime , invalid check_in datetime.');
    }
}
