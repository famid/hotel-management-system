<?php
/**
 * Created by PhpStorm.
 * User: debu
 * Date: 8/9/19
 * Time: 12:21 PM
 */

namespace App\Http\Services;


use App\Http\Repository\UserRepository;
use App\Jobs\SendForgetPasswordEmailJob;
use App\Jobs\SendVerificationEmailJob;
use App\Models\MobileDevice;
use App\Models\PasswordReset;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ApiAuthService extends CommonService
{

    /**
     * ProfileService constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return array
     */
    public function signUp(Request $request)
    {
        $where = ['email' => $request->email, 'social_network_type' => $request->social_network_type];
        $hasEmail = $this->userRepository->whereFirst($where);
        if (!empty($hasEmail)) {
            return [
                'success' => false,
                'message' => __('This email is already used'),
                'data' => null
            ];
        }
        $where = ['phone' => $request->phone];
        $hasPhone = $this->userRepository->whereFirst($where);
        if (!empty($hasPhone)) {
            return [
                'success' => false,
                'message' => __('This phone number is already used'),
                'data' => null
            ];
        }
        $role = $request->role == 'ADMIN' ? ADMIN_ROLE : USER_ROLE;
        $randNo = randomNumber(6);
        $name = explode(' ', $request->name);
        $insert = [
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->get('password')),
            'first_name' => isset($name[0]) ? $name[0] : "",
            'last_name' => isset($name[1]) ? $name[1] : "",
            'role' => $role,
            'status' => USER_PENDING_STATUS,
            'email_verification_code' => $randNo,
        ];
        $token = null;
        DB::beginTransaction();
        try {
            $user = $this->userRepository->create($insert);
            $where = ['user_id' => $user->id];
            $data = [
                'device_type' => $request->device_type,
                'device_token' => $request->device_token
            ];
            $this->mobileDevicesRepository->updateOrCreate($where, $data);
            if ($user) {
                $token = $user->createToken($request->get('email'))->accessToken;
            }
            $defaultEmail = 'boilerplate@gmail.com';
            $defaultName = 'Boiler Plate';
            $logo =  asset('assets/images/laravelLogo.png');
            dispatch(new SendVerificationEmailJob($randNo, $defaultName, $logo, $user, $defaultEmail))->onQueue('email-send');
            DB::commit();
            $response = [
                'success' => true,
                'message' => __("Successfully Signed up! \n Please verify your account"),
                'data' => [
                    'access_token' => $token,
                    'access_type' => "Bearer",
                    'user_data' => [
                        'name' => $user->first_name . ' ' . $user->last_name,
                        'email' => $user->email,
                        'phone' => $user->phone
                    ]
                ]
            ];

            return $response;
        } catch (\Exception $exception) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => __('Something went wrong. Please try again! ') . $exception->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * @param Request $request
     * @return array
     * @throws AuthenticationException
     */
    public function signIn(Request $request)
    {
        $where = ['email' => $request->email];
        $user = $this->userRepository->whereFirst($where);
        if (!empty($user)) {
            if ($user->role != USER_ROLE && $user->role != ADMIN_ROLE) {
                return  [
                    'success' => false,
                    'message' => __("This is not user email"),
                    'data' => null
                ];
            }
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken($request->email)->accessToken;
                if ($user->email_verification_code == null && $user->status == USER_ACTIVE_STATUS) {
                    $response = [
                        'success' => true,
                        'email_verification' => true,
                        'message' => __("Successfully Signed in!"),
                        'data' => [
                            'access_token' => $token,
                            'access_type' => "Bearer",
                            'user_data' => [
                                'name' => $user->first_name . ' ' . $user->last_name,
                                'email' => $user->email,
                                'phone' => $user->phone
                            ]
                        ]
                    ];
                } elseif ($user->email_verification_code != null && $user->status == USER_PENDING_STATUS) {
                    $response = [
                        'success' => true,
                        'email_verification' => false,
                        'message' => __("Your account is not verified. Please verify your account."),
                        'data' => [
                            'access_token' => $token,
                            'access_type' => "Bearer",
                            'user_data' => [
                                'name' => $user->first_name . ' ' . $user->last_name,
                                'email' => $user->email,
                                'phone' => $user->phone
                            ]
                        ]
                    ];
                } else {
                    throw new AuthenticationException('You are not authorized');
                }

                $where = ['user_id' => $user->id];
                $data = [
                    'device_type' => $request->device_type,
                    'device_token' => $request->device_token
                ];
                $this->mobileDevicesRepository->updateOrCreate($where, $data);
            } else {
                $response = [
                    'success' => false,
                    'message' => __("Email or Password doesn't match"),
                    'data' => null
                ];
            }
        } else {
            $response = [
                'success' => false,
                'message' => __("This email does not exist"),
                'data' => null
            ];
        }

        return $response;
    }

    /**
     * @return array
     */
    public function resendEmailVerificationCode()
    {
        $user = Auth::user();
        if ($user->status == USER_ACTIVE_STATUS) {
            return [
                'success' => false,
                'message' => __('Your account is already verified'),
                'data' => null
            ];
        }
        $randNo = randomNumber(6);
        try {
            $defaultEmail = 'boilerplate@email.com';
            $defaultName = 'Boiler Plate';
            $logo =  asset('assets/images/laravelLogo.png');
            dispatch(new SendVerificationEmailJob($randNo, $defaultName, $logo, $user, $defaultEmail))->onQueue('email-send');
            $where = ['id' => $user->id];
            $data = ['email_verification_code' => $randNo];
            $this->userRepository->update($where, $data);

            return [
                'success' => true,
                'message' => __("Code has been sent to your email"),
                'data' => null
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => __('Something went wrong. Please try again! ') . $exception->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * @param $request
     * @return array
     */
    public function emailVerify($request)
    {
        $user = Auth::user();
        if ($user->status == USER_ACTIVE_STATUS) {
            return [
                'success' => false,
                'message' => __("Your account is already verified"),
                'data' => null
            ];
        }
        if ($user->email_verification_code == $request->email_verification_code) {
            DB::beginTransaction();
            try {
                $where = ['id' => $user->id];
                $data = [
                    'email_verification_code' => null,
                    'status' => USER_ACTIVE_STATUS
                ];
                $this->userRepository->update($where, $data);
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => __("Something went wrong. Please try again") . $exception->getMessage(),
                    'data' => null
                ];
            }

            return [
                'success' => true,
                'message' => __("Account is verified successfully."),
                'data' => null
            ];
        } else {
            return [
                'success' => false,
                'message' => __("Account verification code is invalid."),
                'data' => null
            ];
        }
    }

    /**
     * @param $request
     * @return array
     */
    public function sendForgetPasswordEmail($request)
    {
        $where = [
            'email' => $request->email,
            'is_social_login' => 0
        ];
        $user = $this->userRepository->whereFirst($where);
        if (empty($user)) {
            return [
                'success' => false,
                'message' =>  __('User not found'),
                'data' => null
            ];
        }
        $randNo = randomNumber(6);
        try {
            $defaultEmail = 'boilerplate@email.com';
            $defaultName = 'Boiler Plate';
            $logo =  asset('assets/images/laravelLogo.png');
            dispatch(new SendForgetPasswordEmailJob($randNo, $defaultName, $logo, $user, $defaultEmail));
            $data = [
                'user_id' => $user->id,
                'verification_code' => $randNo
            ];
            $this->passwordResetRepository->create($data);

            return [
                'success' => true,
                'message' =>  __('Code has been sent to ') . ' ' . $user->email,
                'data' => null
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' =>  __('Something went wrong. Please try again'),
                'data' => null
            ];
        }
    }

    /**
     * @param $request
     * @return array
     */
    public function resetPassword($request)
    {
        $where = [
            'verification_code' => $request->reset_password_code,
            'status' => PENDING_STATUS
        ];
        $passwordResetCode = $this->passwordResetRepository->whereFirst($where);
        if (!empty($passwordResetCode)) {
            $where = [
                'user_id' => $passwordResetCode->user_id,
                'status' => PENDING_STATUS
            ];
            $orderBy = ['id', 'desc'];
            $latestResetCode = $this->passwordResetRepository->whereFirst($where, $orderBy);
            if (($latestResetCode->verification_code != $request->reset_password_code)) {
                return [
                    'success' => false,
                    'message' =>   __('Your given reset password code is incorrect'),
                    'data' => null
                ];
            }
        } else {
            return [
                'success' => false,
                'message' =>   __('Your given reset password code is incorrect'),
                'data' => null
            ];
        }

        if (!empty($passwordResetCode)) {
            $totalDuration = Carbon::now()->diffInMinutes($passwordResetCode->created_at);
            if ($totalDuration > EXPIRE_TIME_OF_FORGET_PASSWORD_CODE) {
                return [
                    'success' => false,
                    'message' =>  __('Your code has been expired. Please give your code with in') . EXPIRE_TIME_OF_FORGET_PASSWORD_CODE . __('minutes'),
                    'data' => null
                ];
            }
            $where = ['id' => $passwordResetCode->user_id];
            $user = $this->userRepository->whereFirst($where);
            if (empty($user)) {
                return [
                    'success' => false,
                    'message' =>  __('User not found'),
                    'data' => null
                ];
            }
            $where = ['id' => $user->id];
            $data = ['password' => Hash::make($request->new_password)];
            $this->userRepository->update($where, $data);
            $where = ['id' => $passwordResetCode->id];
            $data = ['status' => ACTIVE_STATUS];
            $this->passwordResetRepository->update($where, $data);

            return [
                'success' => true,
                'message' =>  __('Password is reset successfully'),
                'data' => null
            ];
        }

        return [
            'success' => false,
            'message' =>   __('Your given reset password code is incorrect'),
            'data' => null
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function socialLogin(Request $request)
    {
        DB::beginTransaction();
        try {
            $name = explode(' ', $request->name);
            $insert = [
                'email' => $request->email,
                'password' => Hash::make($request->get('password')),
                'first_name' => isset($name[0]) ? $name[0] : "",
                'last_name' => isset($name[1]) ? $name[1] : "",
                'role' => USER_ROLE,
                'status' => USER_ACTIVE_STATUS,
                'is_social_login' => true,
                'social_network_id' => $request->social_network_id,
                'social_network_type' => $request->social_network_type
            ];
            $where = [
                'email' => $request->email,
                'is_social_login' => 1,
                'social_network_type' => $request->social_network_type
            ];
            $user = $this->userRepository->updateOrCreate($where, $insert);
            $where = ['user_id' => $user->id];
            $data = [
                'device_type' => $request->device_type,
                'device_token' => $request->device_token
            ];
            $this->mobileDevicesRepository->updateOrCreate($where, $data);
            DB::commit();
            $token = null;
            if ($user) {
                $token = $user->createToken($request->get('email'))->accessToken;
            }

            $response = [
                'success' => true,
                'email_verification' => true,
                'message' => __("Successfully Signed in!"),
                'data' => [
                    'access_token' => $token,
                    'access_type' => "Bearer",
                    'user_data' => [
                        'name' => $user->first_name . ' ' . $user->last_name,
                        'email' => $user->email,
                        'phone' => empty($user->phone) ? "" : $user->phone
                    ]
                ]
            ];
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                'success' => false,
                'message' => __('Something went wrong. Please try again') . $exception->getMessage(),
                'data' => null
            ];
        }

        return $response;
    }

    /**
     * @param $request
     * @return array
     */
    public function logout($request)
    {
        try {
            $where = [
                'user_id' => Auth::id(),
                'device_type' => $request->device_type,
                'device_token' => $request->device_token
            ];
            $this->mobileDevicesRepository->deleteWhere($where);
            $this->userRepository->deleteToken($request);

            return [
                'success' => true,
                'message' => __('Logged out successfully'),
                'data' => null
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => __('Something went wrong. Please try again'),
                'data' => null
            ];
        }
    }
}
