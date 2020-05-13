<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\EmailVerificationRequest;
use App\Http\Requests\Api\LogoutRequest;
use App\Http\Requests\Api\ResetPasswordRequest;
use App\Http\Requests\Api\SendForgotPasswordEmailRequest;
use App\Http\Requests\Api\SignInRequest;
use App\Http\Requests\Api\SignUpRequest;
use App\Http\Requests\Api\SocialLoginRequest;
use App\Http\Services\Authentication\ApiAuthService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    private $service;

    /**
     * AuthController constructor.
     * @param ApiAuthService $service
     */
    public function __construct(ApiAuthService $service) {
        $this->service = $service;
    }

    /**
     * @param SignUpRequest $request
     * @return JsonResponse
     */
    public function signUp(SignUpRequest $request) {
        $response = $this->service->signUp($request);

        return response()->json($response);
    }

    /**
     * @param SignInRequest $request
     * @return JsonResponse
     * @throws AuthenticationException
     */
    public function signIn(SignInRequest $request) {
        $response = $this->service->signIn($request);

        return response()->json($response);
    }

    /**
     * @return JsonResponse
     */
    public function resendEmailVerificationCode() {
        $response = $this->service->resendEmailVerificationCode();

        return response()->json($response);
    }

    /**
     * @param EmailVerificationRequest $request
     * @return JsonResponse
     */
    public function emailVerify(EmailVerificationRequest $request) {
        $response = $this->service->emailVerify($request);

        return response()->json($response);
    }

    /**
     * @param SendForgotPasswordEmailRequest $request
     * @return JsonResponse
     */
    public function sendForgetPasswordEmail(SendForgotPasswordEmailRequest $request) {
        $response = $this->service->sendForgetPasswordEmail($request);

        return response()->json($response);
    }

    /**
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request) {
        $response = $this->service->resetPassword($request);

        return response()->json($response);
    }

    /**
     * @param SocialLoginRequest $request
     * @return JsonResponse
     */
    public function socialLogin(SocialLoginRequest $request) {
        $response = $this->service->socialLogin($request);

        return response()->json($response);
    }

    /**
     * @param LogoutRequest $request
     * @return JsonResponse
     */
    public function logout(LogoutRequest $request) {
        $response = $this->service->logout($request);

        return response()->json($response);
    }
}
