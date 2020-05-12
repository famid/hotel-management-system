<?php

namespace App\Http\Controllers\Web\SuperAdmin;

use App\Http\Services\SettingsService;
use App\Models\AdminSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
     * @return \Illuminate\Http\RedirectResponse
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
