<?php
/**
 * Created by PhpStorm.
 * User: debu
 * Date: 6/1/19
 * Time: 5:54 PM
 */

namespace App\Http\Controllers\Web;


use App\Http\Controllers\Controller;
use App\Http\Requests\Web\ResetPasswordRequest;
use App\Http\Requests\Web\SendForgotPasswordEmailRequest;
use App\Http\Requests\Web\SignInRequest;
use App\Http\Services\Authentication\WebAuthService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    private $service;

    /**
     * AuthController constructor.
     * @param WebAuthService $service
     */
    public function __construct(WebAuthService $service) {
        $this->service = $service;
    }

    /**
     * @return RedirectResponse
     */
    public function index() {
        $user = Auth::user();
        if (!empty($user) && $user->role == SUPER_ADMIN_ROLE) {

            return redirect()->route('superAdmin.dashboard');
        } else {

            return redirect()->route('signIn');
        }
    }

    /**
     * @return Application|Factory|View
     */
    public function signIn() {

        return view('auth.login');
    }

    /**
     * @param SignInRequest $request
     * @return RedirectResponse
     */
    public function signInProcess(SignInRequest $request) {
        $response = $this->service->signInProcess($request);
        if($response['success']){

            return redirect()->route('superAdmin.dashboard')->with(['success' => $response['message']]);
        }else{

            return redirect()->back()->with(['error' => $response['message']]);
        }
    }

    /**
     * @return RedirectResponse
     */
    public function signOut() {
        Auth::logout();
        session()->flush();

        return redirect()->route('signIn');
    }

    /**
     * @return Application|Factory|View
     */
    public function forgetPassword() {

        return view('auth.forget_password_email');
    }

    /**
     * @param SendForgotPasswordEmailRequest $request
     * @return RedirectResponse
     */
    public function forgetPasswordEmailSendProcess(SendForgotPasswordEmailRequest $request) {
        $response = $this->service->sendForgetPasswordEmail($request);
        if($response['success']){

            return redirect()->route('forgetPasswordCode')->with(['success' => $response['message']]);
        }else{

            return redirect()->back()->with(['error' => $response['message']]);
        }
    }

    /**
     * @return Application|Factory|View
     */
    public function forgetPasswordCode() {

        return view('auth.forget_password');
    }

    /**
     * @param ResetPasswordRequest $request
     * @return RedirectResponse
     */
    public function forgetPasswordCodeProcess(ResetPasswordRequest $request) {
        $response = $this->service->resetPassword($request);
        if($response['success']){

            return redirect()->route('signIn')->with(['success' => $response['message']]);
        }else{

            return redirect()->back()->with(['error' => $response['message']]);
        }
    }
}
