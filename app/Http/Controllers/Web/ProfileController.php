<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\Web\ChangePasswordRequest;
use App\Http\Controllers\Controller;
use App\Http\Services\ProfileService;

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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function passwordChange()
    {
        $data['menuName'] = __('Change Password');

        return view('auth.password-change', $data);
    }

    /**
     * @param ChangePasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function passwordChangeProcess(ChangePasswordRequest $request)
    {
        $response = $this->service->updatePassword($request);
        if($response['success']){
            return redirect()->back()->with(['success' => $response['message']]);
        }
        else{
            return redirect()->back()->with(['error' => $response['message']]);
        }
    }
}
