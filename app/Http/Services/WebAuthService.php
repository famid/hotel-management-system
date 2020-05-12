<?php


namespace App\Http\Services;


use App\Jobs\SendForgetPasswordEmailJob;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WebAuthService extends CommonService
{
    /**
     * ProfileService constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $request
     * @return array
     */
    public function signInProcess($request)
    {
        $credentials = $this->credentials($request->except('_token'));
        $valid = Auth::attempt($credentials);
        if ($valid) {
            $user = Auth::user();
            if ($user->role == SUPER_ADMIN_ROLE) {// || $user->role == ADMIN_ROLE || $user->role == USER_ROLE . Admin and user web features are disabled for this app
                return [
                    'success' => true,
                    'message' => __('Congratulations! You have signed in successfully.'),
                    'data' => $user
                ];
            } else {
                Auth::logout();

                return [
                    'success' => false,
                    'message' => __('You are not authorized'),
                    'data' => null
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => __('Email or password is incorrect'),
                'data' => null
            ];
        }
    }

    /**
     * @param $data
     * @return array
     */
    private function credentials($data)
    {
        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return [
                'email' => $data['email'],
                'password' => $data['password']
            ];
        } else {
            return [
                'user_name' => $data['email'],
                'password' => $data['password']
            ];
        }
    }

    /**
     * @param $request
     * @return array
     */
    public function sendForgetPasswordEmail($request)
    {
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $where = ['email' => $request->email];
        } else {
            $where = ['user_name' => $request->email];
        }
        $user = $this->userRepository->whereFirst($where);
        if (empty($user)) {
            return [
                'success' => false,
                'message' =>  __('User not found'),
                'data' => null
            ];
        }
        if ($user->role == ADMIN_ROLE) {
            if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                return [
                    'success' => false,
                    'message' =>  __('Please enter your username instead of email'),
                    'data' => null
                ];
            }
        }

        $randNo = randomNumber(6);
        try {
            $defaultEmail = 'boilerplate@email.com';
            $defaultName = 'Boiler Plate';
            $logo =  asset('assets/images/laravelLogo.png');
            dispatch(new SendForgetPasswordEmailJob($randNo, $defaultName, $logo, $user, $defaultEmail));
            $this->passwordResetRepository->create([
                'user_id' => $user->id,
                'verification_code' => $randNo
            ]);
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' =>  __('Something went wrong. Please try again'),
                'data' => null
            ];
        }

        return [
            'success' => true,
            'message' =>  __('Code has been sent to ') . ' ' . $user->email,
            'data' => null
        ];
    }

    /**
     * @param $request
     * @return array
     */
    public function resetPassword($request)
    {
        $where = ['verification_code' => $request->reset_password_code, 'status' => PENDING_STATUS];
        $passwordResetCode = $this->passwordResetRepository->whereFirst($where);
        if (!empty($passwordResetCode)) {
            $where = ['user_id' => $passwordResetCode->user_id, 'status' => PENDING_STATUS];
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
}
