<?php
/**
 * Created by PhpStorm.
 * User: debu
 * Date: 5/29/19
 * Time: 2:38 AM
 */

namespace App\Http\Services;


use App\Models\MobileDevice;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Support\Facades\Auth;

class PushNotificationService
{
    /**
     * @param $title
     * @param $body
     * @param $extraPayload
     * @param string $sound
     */
    public function send($title, $body, $extraPayload, $sound = 'default')
    {
        $receiverDevices = MobileDevice::whereHas('users', function ($users) {
            $users->whereHas('subscriber', function ($subscriber) {
                $subscriber->whereHas('customer', function ($customer) {
                   $customer->where('user_id', Auth::id());
                });
            })->where(['status' => USER_ACTIVE_STATUS]);
        })->get();
        //Send iOS Notification
        $iOsDeviceTokens = $receiverDevices->where('device_type', 'ios')->pluck('device_token')->toArray();
        if (!empty($iOsDeviceTokens)) {
            $aps['alert']['title'] = $title;
            $aps['sound'] = 'default';
            if (!empty($body)) {
                $aps['alert']['body'] = $body;
                $aps['content-available'] = 1;
            }
            $this->iOs($aps, $extraPayload, $iOsDeviceTokens);
        }
        //Send Android Notification
        $androidDeviceTokens = $receiverDevices->where('device_type', 'android')->pluck('device_token')->toArray();
        if (!empty($androidDeviceTokens)) {
            $notification['title'] = $title;
            $notification['body'] = $body;
            $this->android($extraPayload, $androidDeviceTokens);
        }
    }

    /**
     * @param $data
     * @param $deviceTokens
     */
    public function android($data, $deviceTokens)
    {
        if (!empty($data)) {
            $push = new PushNotification('fcm');
            $messages['data'] = $data;
            $push->setMessage($messages)->setDevicesToken($deviceTokens)->send();
        }
    }

    /**
     * @param array $aps
     * @param $extraPayload
     * @param $deviceTokens
     */
    public function iOs(array $aps, $extraPayload, $deviceTokens)
    {
        $push = new PushNotification('fcm');
        $messages['aps'] = $aps;
        if (!empty($extraPayload)) {
            $messages['notification'] = $extraPayload;
        }
        $push->setMessage($messages)->setDevicesToken($deviceTokens)->send();
    }

    /**
     * @param $title
     * @param $body
     * @param $extraPayload
     * @param $userId
     */
    public function sendConfirmation($title, $body, $extraPayload, $userId)
    {
        $receiverDevices = MobileDevice::where(['user_id' => $userId])->get();
        //Send iOS Notification
        $iOsDeviceTokens = $receiverDevices->where('device_type', 'ios')->pluck('device_token')->toArray();
        if (!empty($iOsDeviceTokens)) {
            $aps['alert']['title'] = $title;
            $aps['sound'] = 'default';
            if (!empty($body)) {
                $aps['alert']['body'] = $body;
                $aps['content-available'] = 1;
            }
            $this->iOs($aps, $extraPayload, $iOsDeviceTokens);
        }
        //Send Android Notification
        $androidDeviceTokens = $receiverDevices->where('device_type', 'android')->pluck('device_token')->toArray();
        if (!empty($androidDeviceTokens)) {
            $notification['title'] = $title;
            $notification['body'] = $body;
            $this->android($extraPayload, $androidDeviceTokens);
        }
    }
}
