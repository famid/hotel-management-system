<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\LanguageSetRequest;
use App\Http\Requests\Api\PhoneVerificationRequest;
use App\Http\Requests\Api\UpdatePasswordRequest;
use App\Http\Requests\Api\UpdateProfileRequest;
use App\Http\Services\ProfileService;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    private $service;

    /**
     * ProfileController constructor.
     * @param ProfileService $service
     */
    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserProfile()
    {
        $response = $this->service->getUserProfile();

        return response()->json($response);
    }

    /**
     * @param UpdateProfileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserProfile(UpdateProfileRequest $request)
    {
        $response = $this->service->updateUserProfile($request);

        return response()->json($response);
    }

    /**
     * @param UpdatePasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $response = $this->service->updatePassword($request);

        return response()->json($response);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendPhoneVerificationCode()
    {
        $response = $this->service->sendPhoneVerificationCode();

        return response()->json($response);
    }

    /**
     * @param PhoneVerificationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function phoneVerify(PhoneVerificationRequest $request)
    {
        $response = $this->service->phoneVerify($request);

        return response()->json($response);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function languageList()
    {
        $response = $this->service->languageList();

        return response()->json($response);
    }

    /**
     * @param LanguageSetRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setLanguage(LanguageSetRequest $request)
    {
        $response = $this->service->setLanguage($request);

        return response()->json($response);
    }
}
