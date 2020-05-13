<?php

namespace App\Http\Controllers\Web\SuperAdmin;

use App\Http\Services\Settings\SettingsService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function settings()
    {
        $data['settings'] = allSetting();
        $data['mainMenu'] = 'settings';
        $data['menuName'] = __('Settings');

        return view('super_admin.settings.general_settings', $data);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function settingsSaveProcess(Request $request)
    {
        $service = new SettingsService();
        $response = $service->SaveSuperAdminSettings($request);
        if($response['success']){
            return redirect()->back()->withInput()->with(['success' => $response['message']]);
        }
        else {
            return redirect()->back()->with(['error' => $response['message']])->withInput();
        }
    }
}
