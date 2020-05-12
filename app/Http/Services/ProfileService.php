<?php
/**
 * Created by PhpStorm.
 * User: debu
 * Date: 8/9/19
 * Time: 12:21 PM
 */

namespace App\Http\Services;


use App\Http\Repository\UserRepository;
use App\Jobs\SendPhoneVerificationMessageJob;
use App\Jobs\SendVerificationEmailJob;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileService extends CommonService
{
    /**
     * ProfileService constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * @return array
     */
    public function getUserProfile()
    {
        $user = Auth::user();
        try {
            $data = [
                'name' => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
                'phone' => empty($user->phone) ? "" : $user->phone
            ];

            return [
                'success' => true,
                'message' => '',
                'data' => $data
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => __('Something went wrong. Please try again'),
                'data' => null
            ];
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function updateUserProfile(Request $request)
    {
        $authUser = Auth::user();
        $where = [
            ['email' , $request->email],
            ['is_social_login', '!=', 1],
            ['id' , '!=', Auth::id()]
        ];
        $hasEmail = $this->userRepository->whereFirst($where);
        if (!empty($hasEmail)) {
            return [
                'success' => false,
                'message' => __('This email is already used'),
                'data' => null
            ];
        }
        $where = [
            ['phone' , $request->phone],
            ['id' , '!=', Auth::id()]
        ];
        $hasPhone = $this->userRepository->whereFirst($where);
        if (!empty($hasPhone)) {
            return [
                'success' => false,
                'message' => __('This phone number is already used'),
                'data' => null
            ];
        }

        DB::beginTransaction();
        try {
            $authorized = true;
            $name = explode(' ', $request->name);
            $data = [
                'first_name' => isset($name[0]) ? $name[0] : "",
                'last_name' => isset($name[1]) ? $name[1] : "",
                'email' => $request->email,
                'phone' => $request->phone,
            ];
            if ($request->phone != $authUser->phone) {
                $data['is_phone_verified'] = PENDING_STATUS;
            }
            if ($request->email != $authUser->email) {
                $user = $authUser;
                if ($user->is_social_login) {
                    DB::rollBack();

                    return [
                        'success' => false,
                        'message' => __('You can not change your email'),
                        'data' => [
                            'authorized' => $authorized
                        ]
                    ];
                }

                $randNo = randomNumber(6);
                $data['status'] = USER_PENDING_STATUS;
                $data['email_verification_code'] = $randNo;
                $authorized = false;
                $defaultEmail = 'boilerplate@email.com';
                $defaultName = 'Boiler Plate';
                $logo =  asset('assets/images/laravelLogo.png');
                dispatch(new SendVerificationEmailJob($randNo, $defaultName, $logo, $user, $defaultEmail))->onQueue('email-send');
            }
            $this->userRepository->update(['id' => Auth::id()], $data);
            DB::commit();

            return [
                'success' => true,
                'message' => __('Your profile has been updated successfully'),
                'data' => [
                    'authorized' => $authorized
                ]
            ];
        } catch (\Exception $exception) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => __('Something went wrong. Please try again'),
                'data' => null
            ];
        }
    }

    /**
     * @param $request
     * @return array
     */
    public function updatePassword($request)
    {
        $user = Auth::user();
        if (Hash::check($request->old_password, $user->password)) {
            $where = ['id' => $user->id];
            $data = ['password' => Hash::make($request->new_password)];
            $this->userRepository->update($where, $data);

            return [
                'success' => true,
                'message' => __('Password is changed successfully'),
                'data' => null
            ];
        }

        return [
            'success' => false,
            'message' => __('Your given old password is incorrect'),
            'data' => null
        ];
    }

    /**
     * @return array
     */
    public function sendPhoneVerificationCode()
    {
        $user = Auth::user();
        if ($user->is_phone_verified == ACTIVE_STATUS) {
            return [
                'success' => false,
                'message' => __('Your phone is already verified'),
                'data' => null
            ];
        }
        if (empty($user->phone)) {
            return [
                'success' => false,
                'message' => __('Please add your phone first'),
                'data' => null
            ];
        }
        $randNo = randomNumber(6);
        try {
            $where = ['id' => $user->id];
            $data = ['phone_verification_code' => $randNo];
            $this->userRepository->update($where, $data);
            dispatch(new SendPhoneVerificationMessageJob($user, $randNo))->onQueue('sms-send');
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => __('Something went wrong. Please try again! ') . $exception->getMessage(),
                'data' => null
            ];
        }

        return [
            'success' => true,
            'message' => __("Code has been sent to your phone"),
            'data' => null
        ];
    }

    /**
     * @param $request
     * @return array
     */
    public function phoneVerify($request)
    {
        $user = Auth::user();
        if ($user->phone_verification_code == $request->phone_verification_code) {
            DB::beginTransaction();
            try {
                $where = ['id' => $user->id];
                $data = [
                    'phone_verification_code' => null,
                    'is_phone_verified' => ACTIVE_STATUS
                ];
                $this->userRepository->update($where, $data);
                DB::commit();

                return [
                    'success' => true,
                    'message' => __("Phone is verified successfully."),
                    'data' => null
                ];
            } catch (\Exception $exception) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => __("Something went wrong. Please try again") . $exception->getMessage(),
                    'data' => null
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => __("Phone verification code is invalid."),
                'data' => null
            ];
        }
    }

    /**
     * @return array
     */
    public function languageList()
    {
        return [
            'success' => true,
            'message' => '',
            'data' => [
                'languages' => languageFullName()
            ]
        ];
    }

    /**
     * @param LanguageSetRequest $request
     * @return array
     */
    public function setLanguage(LanguageSetRequest $request)
    {
        $user = Auth::user();
        $where = ['id' => $user->id];
        $data = ['language' => $request->language];
        $this->userRepository->update($where, $data);

        return [
                'success' => true,
                'message' => __('Language has been updated successfully'),
                'data' => null
            ];
    }
}
