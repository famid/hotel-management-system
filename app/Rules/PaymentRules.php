<?php

namespace App\Rules;

use App\Http\Services\Booking\RoomBookingService;
use Illuminate\Contracts\Validation\Rule;

class PaymentRules implements Rule
{
    private $roomBookingService;

    /**
     * Create a new rule instance.
     *
     */
    public function __construct(){
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
        return  $this->roomBookingService->validRoomIdCheck($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Room is not found.';
    }
}
